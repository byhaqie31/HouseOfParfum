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
    public function stats(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'users'            => User::count(),
            'perfumes'         => DiscoveryPerfume::count(),
            'wardrobe_items'   => WardrobeItem::count(),
            'journal_entries'  => JournalEntry::count(),
        ]);
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

    // Perfumes (discovery) — editorial fields only

    public function perfumeIndex(Request $request): \Illuminate\Http\JsonResponse
    {
        $perfumes = DiscoveryPerfume::when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->through(fn ($p) => [
                'id'           => $p->id,
                'name'         => $p->name,
                'brand'        => $p->brand,
                'release_year' => $p->release_year,
                'rating'       => $p->rating,
                'image'        => !empty($p->image),
                'has_history'  => !empty($p->history),
            ]);

        return response()->json($perfumes);
    }

    public function updatePerfume(Request $request, DiscoveryPerfume $discoveryPerfume): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'image'   => 'nullable|url',
            'history' => 'nullable|string|max:5000',
        ]);

        $discoveryPerfume->update($validated);

        return response()->json($discoveryPerfume);
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
