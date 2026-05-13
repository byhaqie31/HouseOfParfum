<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $user->update($request->all());
        return response()->json($user);
    }

    public function show($id) { return response()->json([]); }
    public function store(Request $request) { return response()->json([]); }
    public function destroy($id) { return response()->json([]); }
}
