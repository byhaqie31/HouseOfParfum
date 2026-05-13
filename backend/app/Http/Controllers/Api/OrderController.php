<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Order::where('cust_id', $request->user()->id)->get());
    }

    public function store(Request $request)
    {
        $order = Order::create(array_merge($request->all(), ['cust_id' => $request->user()->id]));
        return response()->json($order, 201);
    }

    public function show($id)
    {
        return response()->json(Order::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());
        return response()->json($order);
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(['message' => 'Order deleted']);
    }
}
