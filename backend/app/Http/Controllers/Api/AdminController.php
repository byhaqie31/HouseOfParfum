<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\User;
use App\Models\WardrobeItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats()
    {
        return response()->json([
            'users'           => User::count(),
            'wardrobe_items'  => WardrobeItem::count(),
            'journal_entries' => JournalEntry::count(),
        ]);
    }

    public function users(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($users);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:0,admin',
        ]);

        $user->update(['role' => $request->role]);

        return response()->json($user->only('id', 'name', 'email', 'role'));
    }
}
