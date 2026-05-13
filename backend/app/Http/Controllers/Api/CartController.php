<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Cart::where('cust_id', $request->user()->id)->get());
    }

    public function store(Request $request)
    {
        $cart = Cart::create(array_merge($request->all(), ['cust_id' => $request->user()->id]));
        return response()->json($cart, 201);
    }

    public function show($id)
    {
        return response()->json(Cart::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->update($request->all());
        return response()->json($cart);
    }

    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();
        return response()->json(['message' => 'Cart item deleted']);
    }
}
