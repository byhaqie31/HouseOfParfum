<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->user()
            ->journalEntries()
            ->latest('worn_at')
            ->get();

        return response()->json($entries);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id'               => 'required|uuid|unique:journal_entries,id',
            'wardrobe_item_id' => 'nullable|string|max:36',
            'brand'            => 'required|string|max:255',
            'name'             => 'required|string|max:255',
            'worn_at'          => 'required|date',
            'experience'       => 'nullable|string',
            'compliments'      => 'nullable|string',
            'longevity'        => 'nullable|string|in:brief,settled,lasting,all-day,into-night',
            'notes'            => 'nullable|string',
        ]);

        $entry = $request->user()->journalEntries()->create($data);

        return response()->json($entry, 201);
    }

    public function update(Request $request, $id)
    {
        $entry = $request->user()->journalEntries()->findOrFail($id);

        $data = $request->validate([
            'experience'  => 'nullable|string',
            'compliments' => 'nullable|string',
            'longevity'   => 'nullable|string|in:brief,settled,lasting,all-day,into-night',
            'notes'       => 'nullable|string',
            'worn_at'     => 'nullable|date',
        ]);

        $entry->update($data);

        return response()->json($entry);
    }

    public function destroy(Request $request, $id)
    {
        $entry = $request->user()->journalEntries()->findOrFail($id);
        $entry->delete();

        return response()->json(['message' => 'Removed']);
    }
}
