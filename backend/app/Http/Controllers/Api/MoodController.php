<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyMood;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    /** Return today's mood for the authenticated user, or null. */
    public function show(Request $request)
    {
        $mood = DailyMood::where('user_id', $request->user()->id)
            ->where('date', now()->toDateString())
            ->first();

        return response()->json($mood);
    }

    /** Create or replace today's mood. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mood'     => 'required|string|in:calm,playful,romantic,bold,cocooning',
            'weather'  => 'required|string|in:hot_dry,hot_humid,cool,cold,rainy',
            'occasion' => 'required|string|in:work,leisure,evening,date,active',
        ]);

        $mood = DailyMood::updateOrCreate(
            ['user_id' => $request->user()->id, 'date' => now()->toDateString()],
            $data,
        );

        return response()->json($mood, 201);
    }
}
