<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perfume;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        return response()->json(Perfume::select('suit')->distinct()->get());
    }

    public function show($id) { return response()->json([]); }
    public function store(Request $request) { return response()->json([]); }
    public function update(Request $request, $id) { return response()->json([]); }
    public function destroy($id) { return response()->json([]); }
}
