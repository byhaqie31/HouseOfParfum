<?php

namespace App\Support;

use App\Models\DiscoveryPerfume;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Shared query construction for the discovery catalogue — the search / filter /
 * sort logic behind both `DiscoveryPerfumeController` (raw discovery shape) and
 * `PerfumeController` (transformed into the legacy storefront shape).
 *
 * Supported query params:
 *   search      free text matched against name + brand
 *   brand       exact brand match
 *   gender      exact gender match (men | unisex | women)
 *   season      exact season match (summer | autumn | winter | spring)
 *   rating_min  minimum rating, inclusive
 *   letter      A–Z index — names beginning with this letter
 *   notes       comma-separated notes, match ALL (each must be in the pyramid)
 *   note        legacy single-note alias for `notes`
 *   accord      single accord — matched inside the accords array
 *   sort        rating | votes | release_year | name   (default: rating)
 *   direction   asc | desc                             (default: desc)
 *   per_page    1–100                                  (default: 24)
 */
class DiscoveryQuery
{
    /** Columns allowed in ?sort= — guards against arbitrary-column ordering. */
    private const SORTABLE = ['rating', 'votes', 'release_year', 'name'];

    public static function build(Request $request): Builder
    {
        $query = DiscoveryPerfume::query();

        // Free-text search across name + brand.
        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Exact-match column filters.
        if ($brand = $request->query('brand')) {
            $query->where('brand', $brand);
        }
        if ($gender = $request->query('gender')) {
            $query->where('gender', $gender);
        }
        if ($season = $request->query('season')) {
            // suit_season is stored title-cased (Summer / Autumn / Winter / Spring).
            $query->where('suit_season', ucfirst(mb_strtolower(trim($season))));
        }
        if (is_numeric($ratingMin = $request->query('rating_min'))) {
            $query->where('rating', '>=', (float) $ratingMin);
        }

        // A–Z index strip — names beginning with the given letter.
        if ($letter = $request->query('letter')) {
            $query->where('name', 'like', mb_substr(trim($letter), 0, 1).'%');
        }

        // Note filter — accepts `notes` (comma-separated, match ALL) or the
        // legacy single `note`. Each note must appear in one of the three
        // pyramid columns; stored note values are lower-case.
        $noteParam = $request->query('notes') ?? $request->query('note');
        if ($noteParam) {
            $notes = collect(explode(',', $noteParam))
                ->map(fn ($n) => mb_strtolower(trim($n)))
                ->filter()
                ->all();
            foreach ($notes as $note) {
                $query->where(function ($q) use ($note) {
                    $q->whereJsonContains('notes_top', $note)
                        ->orWhereJsonContains('notes_middle', $note)
                        ->orWhereJsonContains('notes_base', $note);
                });
            }
        }

        if ($accord = $request->query('accord')) {
            $query->whereJsonContains('accords', mb_strtolower($accord));
        }

        // Whitelisted sort; defaults to highest-rated first.
        $sort = in_array($request->query('sort'), self::SORTABLE, true)
            ? $request->query('sort')
            : 'rating';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        return $query;
    }

    /** Per-page size, clamped to 1–100 so a client can't pull the whole table. */
    public static function perPage(Request $request, int $default = 24): int
    {
        return min(max((int) $request->query('per_page', $default), 1), 100);
    }
}
