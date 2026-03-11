<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Chat::where('user_id', $request->user()->id)->get());
    }

    public function store(Request $request)
    {
        $chat = Chat::create(array_merge($request->all(), ['user_id' => $request->user()->id]));
        return response()->json($chat, 201);
    }

    public function show($id)
    {
        return response()->json(Chat::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);
        $chat->update($request->all());
        return response()->json($chat);
    }

    public function destroy($id)
    {
        Chat::findOrFail($id)->delete();
        return response()->json(['message' => 'Chat deleted']);
    }
}
