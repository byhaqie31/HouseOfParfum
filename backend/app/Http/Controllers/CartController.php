<?php

namespace App\Http\Controllers;
use App\Models\Perfume;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carts = Cart::where('cust_id', Auth::id())->where('payment_status', 0)->get();
        $customer = Customer::where('user_id', Auth::id())->first();
        $total = Cart::where('cust_id', Auth::id())->where('payment_status', 0)->sum('total_price');
        return view('cart.item-cart')->With(['carts'=>$carts, 'total'=>$total, 'customer'=>$customer]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $perfume = Perfume::find($id);

        $cart = new Cart();
        $cart->perfume_id = $perfume->id; 
        $cart->perfume_name = $perfume->name;
        $cart->perfume_img = $perfume->image;
        $cart->cust_id = Auth::id();
        $cart->price_per_unit = $perfume->price;
        $cart->perfume_size =  $perfume->size;
        $cart->quantity = $request->input("qty");
        $cart->total_price = ($cart->quantity)*($cart->price_per_unit); 

        $cart->save();
        return redirect('/perfume');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();
        return redirect('/cart');
    }

    public static function countCart($id){
        $Carts = Cart::where('cust_id', $id)->where('payment_status', 0)->get();

        return $Carts;
    }
}
