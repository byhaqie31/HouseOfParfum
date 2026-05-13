<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Imagick;
use File;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pendings = DB::table('cart')
                    ->join('order', 'cart.id', '=', 'order.cart_id')
                    ->where('cart.order_status', '=', '0')
                    ->where('cart.payment_status', '=', '1')
                    ->get();
        $deliveries = DB::table('cart')
                    ->join('order', 'cart.id', '=', 'order.cart_id')
                    ->where('cart.order_status', '=', '1')
                    ->where('cart.payment_status', '=', '1')
                    ->get();
        $completes = DB::table('cart')
                    ->join('order', 'cart.id', '=', 'order.cart_id')
                    ->where('cart.order_status', '=', '2')
                    ->where('cart.payment_status', '=', '1')
                    ->get();
        return view('order.order-list')->with(['pendings'=>$pendings, 'deliveries'=>$deliveries, 'completes'=>$completes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $order = new Order();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validator
        $this->validate($request,[
            
            'name'=>'required',
            'email'=> 'required',
            'payment'=> 'required',
            'phone'=> 'required',
            'receipt' => 'required',
        ]);
        //Receipt Store
        
        $imageName = uniqid().'.'.$request->receipt->extension();  
        $request->receipt->move(public_path('images/receipts'), $imageName);

        $carts = Cart::where('cust_id', Auth::id())->where('payment_status', 0)->get();
        $transaction = 'Cs'.str_pad(Auth::id(), 5, "0", STR_PAD_LEFT).'_'.uniqid();

        foreach($carts as $cart){
            $order = new Order();
            $order->cust_id = Auth::id();
            $order->cart_id = $cart->id;
            $order->perfume_id = $cart->perfume_id;
            $order->perfume_name = $cart->perfume_name;
            $order->perfume_size = $cart->perfume_size;
            $order->quantity = $cart->quantity;
            $order->payment_receipt = $imageName;
            $order->transaction_id = $transaction;
            $order->total_price = $cart->total_price;
            $order->payment_mode = $request->input('payment');  
            $order->payment_status = 1;
            $order->save();

            $cart->payment_status = 1; //paid
            $cart->save(); 
        }
        return redirect('/purchase')->with('success', 'Order has been placed.');

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
        // Pass value id cust dan update semua where ...
        if(Session::get('role') === '1'){
            $orders = Order::where('cust_id', $id)->where('order_status', 0)->get();
            foreach ($orders as $order){
                $order->tracking_no = $request->input('tracking_no');
                $order->order_status = '1';
                
                $cart = Cart::find($order->cart_id);
                $cart->order_status = '1'; //da post

                $cart->save();
                $order->save();
            }
            return redirect('/order')->with('success', 'Order status has been updated.');
        }

        // Pass value id cart dan update one by one...
        else if(Session::get('role') === '0'){
            $order = Order::where('cart_id', $id)->first(); //x sure first atau get()
            $order->order_status = '2';

            $cart = Cart::where('id', $order->cart_id)->first(); 
            $cart->order_status = '2';

            $customer = Customer::where('user_id', $order->cust_id)->first();
            $customer->loyalty_point += (round((($order->total_price)/10), 0));

            if($customer->loyalty_point < 500)
                $customer->loyalty_rank = '0';

            else if(($customer->loyalty_point >= 500) && ($customer->loyalty_point < 1000))
            $customer->loyalty_rank = '1';

            else
            $customer->loyalty_rank = '2';

            $customer->save();
            $order->save();
            $cart->save();

            return redirect('/purchase')->with('success', 'Order status has been updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
