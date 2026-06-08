<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlmanacChapter;
use App\Models\AlmanacEntry;
use App\Models\DiscoveryPerfume;
use App\Models\JournalEntry;
use App\Models\User;
use App\Models\WardrobeItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    /** The seven scent families, in display order (mirrors the frontend FAMILY_ORDER). */
    private const FAMILIES = ['floral', 'woody', 'aquatic', 'spicy', 'powdery', 'citrus', 'gourmand'];

    public function stats(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'users'            => User::count(),
            'perfumes'         => DiscoveryPerfume::count(),
            'wardrobe_items'   => WardrobeItem::count(),
            'journal_entries'  => JournalEntry::count(),
        ]);
    }

    /**
     * Overview dashboard — every figure the scent-driven admin renders, in one
     * call: the four totals + month-over-month growth, the family distribution
     * (catalogue coverage vs member engagement), a 14-day wear trend, the most
     * logged fragrances, and the catalogue-completeness health metric.
     */
    public function dashboard(): \Illuminate\Http\JsonResponse
    {
        // ── Family distribution ──────────────────────────────────────────────
        // Catalogue = how many registry rows sit in each family (coverage).
        $catalogue = DiscoveryPerfume::whereNotNull('family')
            ->selectRaw('family, COUNT(*) as c')->groupBy('family')->pluck('c', 'family');
        $uncategorised = DiscoveryPerfume::whereNull('family')->count();

        // Wears = engagement, via journal_entries → the member's wardrobe item's family.
        $wearsByFamily = JournalEntry::query()
            ->join('wardrobe_items', 'journal_entries.wardrobe_item_id', '=', 'wardrobe_items.id')
            ->whereNotNull('wardrobe_items.family')
            ->selectRaw('wardrobe_items.family as family, COUNT(*) as c')
            ->groupBy('wardrobe_items.family')->pluck('c', 'family');

        $familyRows = collect(self::FAMILIES)->map(fn ($k) => [
            'key'       => $k,
            'catalogue' => (int) ($catalogue[$k] ?? 0),
            'wears'     => (int) ($wearsByFamily[$k] ?? 0),
        ])->values();

        // ── 14-day wear trend ────────────────────────────────────────────────
        $since = now()->subDays(13)->startOfDay();
        $byDay = JournalEntry::where('worn_at', '>=', $since)
            ->selectRaw('DATE(worn_at) as d, COUNT(*) as c')->groupBy('d')->pluck('c', 'd');
        $series = collect(range(0, 13))->map(function ($i) use ($byDay) {
            $key = now()->subDays(13 - $i)->format('Y-m-d');
            return ['date' => $key, 'value' => (int) ($byDay[$key] ?? 0)];
        })->values();

        // ── Most logged (by fragrance), with family resolved from the wardrobe ─
        $top = JournalEntry::selectRaw('name, brand, COUNT(*) as wears')
            ->groupBy('name', 'brand')->orderByDesc('wears')->limit(6)->get();
        $meta = WardrobeItem::select('name', 'brand', 'family', 'catalog_id')->get()
            ->keyBy(fn ($w) => mb_strtolower(trim($w->name).'|'.trim($w->brand)));
        $topLogged = $top->map(function ($t) use ($meta) {
            $m = $meta[mb_strtolower(trim($t->name).'|'.trim($t->brand))] ?? null;
            return [
                'name'       => $t->name,
                'brand'      => $t->brand,
                'wears'      => (int) $t->wears,
                'family'     => $m->family ?? null,
                'perfume_id' => $m->catalog_id ?? null,
            ];
        })->values();

        // ── Catalogue completeness ───────────────────────────────────────────
        // 4 editorial fields per row; computed with count queries (no row scan).
        $total          = DiscoveryPerfume::count();
        $missingFamily  = DiscoveryPerfume::whereNull('family')->count();
        $missingImage   = DiscoveryPerfume::whereRaw($this->blank('image'))->count();
        $missingHistory = DiscoveryPerfume::whereRaw($this->blank('history'))->count();
        $missingNotes   = DiscoveryPerfume::whereRaw($this->notesBlank())->count();
        $fullyDone      = DiscoveryPerfume::whereNotNull('family')
            ->whereRaw('NOT '.$this->blank('image'))
            ->whereRaw('NOT '.$this->blank('history'))
            ->whereRaw('NOT ('.$this->notesBlank().')')
            ->count();
        $overallPct = $total
            ? (int) round((4 * $total - $missingFamily - $missingNotes - $missingImage - $missingHistory) / (4 * $total) * 100)
            : 0;

        return response()->json([
            'stats' => [
                'users'           => User::count(),
                'perfumes'        => $total,
                'wardrobe_items'  => WardrobeItem::count(),
                'journal_entries' => JournalEntry::count(),
            ],
            'growth' => [
                'users'           => $this->growth(User::class),
                'perfumes'        => $this->growth(DiscoveryPerfume::class),
                'wardrobe_items'  => $this->growth(WardrobeItem::class),
                'journal_entries' => $this->growth(JournalEntry::class),
            ],
            'families'     => ['rows' => $familyRows, 'uncategorised' => $uncategorised],
            'wear_series'  => $series,
            'top_logged'   => $topLogged,
            'completeness' => [
                'overall_pct' => $overallPct,
                'fully_done'  => $fullyDone,
                'total'       => $total,
                'missing'     => [
                    'family'  => $missingFamily,
                    'notes'   => $missingNotes,
                    'image'   => $missingImage,
                    'history' => $missingHistory,
                ],
            ],
        ]);
    }

    /** Month-over-month growth (%) for a model, by created_at. */
    private function growth(string $modelClass): float
    {
        $last30 = $modelClass::where('created_at', '>=', now()->subDays(30))->count();
        $prev30 = $modelClass::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();

        if ($prev30 === 0) {
            return $last30 > 0 ? 100.0 : 0.0;
        }

        return round(($last30 - $prev30) / $prev30 * 100, 1);
    }

    /** SQL predicate: a text/JSON column counts as empty (NULL, '' or '[]'). */
    private function blank(string $column): string
    {
        return "($column IS NULL OR $column = '' OR $column = '[]')";
    }

    /** SQL predicate: the whole notes pyramid is empty (all three tiers blank). */
    private function notesBlank(): string
    {
        return $this->blank('notes_top').' AND '.$this->blank('notes_middle').' AND '.$this->blank('notes_base');
    }

    /** Shape one registry row for the curation screen. */
    private function registryRow(DiscoveryPerfume $p): array
    {
        return [
            'id'      => $p->id,
            'name'    => $p->name,
            'brand'   => $p->brand,
            'year'    => $p->release_year,
            'rating'  => $p->rating,
            'family'  => $p->family,
            'notes'   => [
                'top'   => $p->notes_top ?? [],
                'heart' => $p->notes_middle ?? [],   // DB calls the heart tier "middle"
                'base'  => $p->notes_base ?? [],
            ],
            'image'   => $p->image,
            'history' => $p->history,
            'wears'   => (int) ($p->wears ?? 0),
        ];
    }

    /** A registry query carrying the correlated wear count (member logs per row). */
    private function registryQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return DiscoveryPerfume::query()
            ->select('discovery_perfumes.*')
            ->selectRaw(
                '(SELECT COUNT(*) FROM journal_entries je '
                .'JOIN wardrobe_items wi ON je.wardrobe_item_id = wi.id '
                .'WHERE wi.catalog_id = discovery_perfumes.id) as wears'
            );
    }

    public function users(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($users);
    }

    public function updateUser(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'role' => 'required|in:0,admin',
        ]);

        $user->update(['role' => $request->role]);

        return response()->json($user->only('id', 'name', 'email', 'role'));
    }

    // Perfumes (discovery) — the curation registry

    public function perfumeIndex(Request $request): \Illuminate\Http\JsonResponse
    {
        $perfumes = $this->registryQuery()
            ->when($request->search, fn ($q, $search) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")))
            ->when(in_array($request->family, self::FAMILIES, true), fn ($q) => $q->where('family', $request->family))
            ->when($request->filter === 'uncategorised', fn ($q) => $q->whereNull('family'))
            ->when($request->filter === 'incomplete', fn ($q) => $this->scopeIncomplete($q))
            ->orderBy('name')
            ->paginate(20)
            ->through(fn ($p) => $this->registryRow($p));

        // The header badge counts every row that still needs curating, registry-wide.
        return response()->json(array_merge($perfumes->toArray(), [
            'incomplete_total' => $this->scopeIncomplete(DiscoveryPerfume::query())->count(),
        ]));
    }

    public function perfumeShow(DiscoveryPerfume $discoveryPerfume): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->registryRow($this->registryQuery()->find($discoveryPerfume->id)));
    }

    /** Constrain a registry query to rows missing any of the 4 editorial fields. */
    private function scopeIncomplete(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(fn ($w) => $w
            ->whereNull('family')
            ->orWhereRaw($this->blank('image'))
            ->orWhereRaw($this->blank('history'))
            ->orWhereRaw('('.$this->notesBlank().')'));
    }

    public function updatePerfume(Request $request, DiscoveryPerfume $discoveryPerfume): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'family'        => 'nullable|string|in:floral,woody,aquatic,spicy,powdery,citrus,gourmand',
            'image'         => 'nullable|url',
            'history'       => 'nullable|string|max:5000',
            'notes'         => 'nullable|array',
            'notes.top'     => 'nullable|array',
            'notes.top.*'   => 'string|max:120',
            'notes.heart'   => 'nullable|array',
            'notes.heart.*' => 'string|max:120',
            'notes.base'    => 'nullable|array',
            'notes.base.*'  => 'string|max:120',
        ]);

        $discoveryPerfume->fill([
            'family'  => $validated['family'] ?? null,
            'image'   => $validated['image'] ?? null,
            'history' => $validated['history'] ?? null,
        ]);

        // The pyramid is replaced wholesale when present; "heart" maps to "middle".
        if (array_key_exists('notes', $validated)) {
            $notes = $validated['notes'] ?? [];
            $discoveryPerfume->notes_top    = $notes['top']   ?? [];
            $discoveryPerfume->notes_middle = $notes['heart'] ?? [];
            $discoveryPerfume->notes_base   = $notes['base']  ?? [];
        }

        $discoveryPerfume->save();

        return response()->json($this->registryRow(
            $this->registryQuery()->find($discoveryPerfume->id)
        ));
    }

    // Almanac

    public function almanacChapters(): \Illuminate\Http\JsonResponse
    {
        $chapters = AlmanacChapter::orderBy('sort_order')
            ->with('entries:id,chapter_id,question,answer,sort_order')
            ->get();

        return response()->json($chapters);
    }

    public function storeChapter(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'slug'       => 'required|string|unique:almanac_chapters,slug',
            'number'     => 'required|string',
            'title'      => 'required|string',
            'intro'      => 'required|string',
            'sort_order' => 'integer',
        ]);

        $validated['sort_order'] ??= AlmanacChapter::count();

        $chapter = AlmanacChapter::create($validated);

        return response()->json($chapter, 201);
    }

    public function updateChapter(Request $request, AlmanacChapter $chapter): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'slug'       => "required|string|unique:almanac_chapters,slug,{$chapter->id}",
            'number'     => 'required|string',
            'title'      => 'required|string',
            'intro'      => 'required|string',
            'sort_order' => 'integer',
        ]);

        $chapter->update($validated);

        return response()->json($chapter);
    }

    public function destroyChapter(AlmanacChapter $chapter): Response
    {
        $chapter->delete();

        return response()->noContent();
    }

    public function storeEntry(Request $request, AlmanacChapter $chapter): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'integer',
        ]);

        $validated['sort_order'] ??= $chapter->entries()->count();

        $entry = $chapter->entries()->create($validated);

        return response()->json($entry, 201);
    }

    public function updateEntry(Request $request, AlmanacEntry $entry): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'integer',
        ]);

        $entry->update($validated);

        return response()->json($entry);
    }

    public function destroyEntry(AlmanacEntry $entry): Response
    {
        $entry->delete();

        return response()->noContent();
    }
}
