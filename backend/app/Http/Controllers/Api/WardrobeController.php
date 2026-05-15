<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WardrobeController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()
            ->wardrobeItems()
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id'         => 'required|uuid|unique:wardrobe_items,id',
            'catalog_id' => 'nullable|integer',
            'brand'      => 'required|string|max:255',
            'name'       => 'required|string|max:255',
            'size'       => 'required|integer|min:1',
            'acquired'   => 'nullable|string|max:255',
            'notes'      => 'nullable|string',
        ]);

        $item = $request->user()->wardrobeItems()->create($data);

        return response()->json($item, 201);
    }

    public function destroy(Request $request, $id)
    {
        $item = $request->user()->wardrobeItems()->findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Removed']);
    }
}
