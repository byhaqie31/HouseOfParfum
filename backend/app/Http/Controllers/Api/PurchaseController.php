<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Order::where('cust_id', $request->user()->id)->where('payment_status', 'paid')->get());
    }

    public function show($id)
    {
        return response()->json(Order::findOrFail($id));
    }

    public function store(Request $request) { return response()->json([]); }
    public function update(Request $request, $id) { return response()->json([]); }
    public function destroy($id) { return response()->json([]); }
}
