@extends('layouts.cart')
@section('content') 

<div class="container">
    <main class="mx-4">
      <div class="py-5 text-center">
        <a class="text-decoration-none text-dark justify-content-center w-25 text-center"><span class="h2 fw-bold">House of Parfum<span class="text-danger">.</span></span></a>
        <p class="lead">Check out your item cart</p>
    </div>
  
      <div class="row g-5">
        <div class="col-md-5 col-lg-5 order-md-last">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-danger">Your cart</span>
            
            {{-- Calculate count item --}}
            <span class="badge bg-danger rounded-pill">{{count($carts)}}</span>
          </h4>
          <ul class="list-group mb-3 ">
            {{-- Loop sini --}}
            @if (count($carts)>0)
                @foreach($carts as $cart)
                    <li class="list-group-item  lh-s">
                        <div class="d-flex justify-content-start">
                            <img src="/images/perfumes/{{$cart->perfume_img}}" style="height:4rem">
                            <div class="d-grid w-100">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mt-2">{{$cart->perfume_name}} <br>({{$cart->quantity}}x)</h6>
                                    <span class="text-muted mt-3 ">RM {{number_format((float)$cart->total_price, 2, '.', '')}}</span>
                                </div>
                                {!!Form::open(['action' => ['\App\Http\Controllers\CartController@destroy', $cart->id], 'method' => 'POST', 'class' => 'pull-right text-end'])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    <button type="submit" class="text-end text-white badge bg-danger text-decoration-none shadow-none">Remove</button>
                                {!!Form::close()!!}
                            </div>
                            
                        </div>
                    </li>
                @endforeach
            @endif
            {{-- <li class="list-group-item d-flex justify-content-between bg-light">
              <div class="text-success">
                <h6 class="my-0">Promo code</h6>
                <small>EXAMPLECODE</small>
              </div>
              <span class="text-success">−RM 5</span>
            </li> --}}
          </ul>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between">
                <span class="fs-5">Total (MYR)</span>
                <strong>RM {{number_format((float)$total, 2, '.', '')}}</strong>
              </li>
          </ul>
  
          
        </div>
        @if(count($carts)>0) 
        <div class="col-md-7 col-lg-7 mb-4">
          {!!Form::open(['action' => ['\App\Http\Controllers\OrderController@store'], 'method' => 'POST', 'enctype' => 'multipart/form-data','class' => 'pull-right', 'novalidate'])!!}
            <div class="row g-3">
              <div class="col-sm-12">
                <label for="name" class="form-label">Full Name </label>
                <input type="text" class="form-control" id="name" name="name" value="{{$customer->name}}" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>

              <div class="col-8">
                <label for="email" class="form-label">Email <span class="text-muted"></span></label>
                <input type="email" class="form-control" id="email" name="email"  value="{{$customer->email}}">
                <div class="invalid-feedback">
                  Please enter a valid email address for shipping updates.
                </div>
              </div>

              <div class="col-sm-4">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="" value="{{$customer->phone}}" required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>
              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address"  name="address" placeholder="1234 Main St" value="{{$customer->address}}" required>
                <div class="invalid-feedback">
                  Please enter your shipping address.
                </div>
              </div>
            </div>
  
            <hr class="my-4">

            <h4 class="mb-3">Payment</h4>
            
            <div class="row gy-3">
              <div class="col-md-6">
                <label for="payment" class="form-label">Payment method</label>
                <select id="payment" name = "payment" class="form-select"  onchange="check(this)">
                    <option value="online">Online Transfer</option>
                    @if($customer->loyalty_point >= $total)
                    <option value="point">Points Deduction</option>
                    @else
                    <option value="point" disabled>Points Deduction (Insufficient)</option>
                    @endif
                    {{-- <option value="cod" disabled>Cash On Delivery</option> --}}
                  </select>
                <small class="text-muted">Choose a correct payment method</small>
                <div class="invalid-feedback">
                  Please select method of payment
                </div>
              </div>

              <div class="col-md-6" id="show">
                <label for="receipt" class="form-label">Receipt</label>
                <input type="file" class="form-control" id="receipt" name="receipt">
                <div class="invalid-feedback">
                  Receipt is required.
                </div>
              </div>

            </div>
  
            <hr class="my-4">
  
            <button type="submit" class="w-100 btn btn-danger btn-lg" type="submit">Continue to checkout</button>
          {!!Form::close()!!}
        </div>
        
        @else
        <div class="col-md-7 col-lg-7 mb-4">
          <form class="needs-validation" novalidate>
            <div class="row g-3">
              <div class="col-sm-12">
                <label for="name" class="form-label">Full Name </label>
                <input type="text" class="form-control" id="name" name="name" value="{{$customer->name}}" disabled>
                <div class="invalid-feedback">
                  Valid name is required.
                </div>
              </div>

              <div class="col-8">
                <label for="email" class="form-label">Email <span class="text-muted"></span></label>
                <input type="email" class="form-control" id="email" name="email"  value="{{$customer->email}}" disabled>
                <div class="invalid-feedback">
                  Please enter a valid email address for shipping updates.
                </div>
              </div>

              <div class="col-sm-4">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="" value="{{$customer->phone}}" disabled>
                <div class="invalid-feedback">
                  Valid phone number is required.
                </div>
              </div>
              <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" value="{{$customer->address}}" disabled>
                <div class="invalid-feedback">
                  Please enter your shipping address.
                </div>
              </div>
            </div>
  
            <hr class="my-4">
  
            <h4 class="mb-3">Payment</h4>
            
            <div class="row gy-3">
              <div class="col-md-6">
                <label for="cc-name" class="form-label">Payment method</label>
                <select id="project" name = "project" class="form-select" disabled>
                    <option value="online">Please Choose</option>
                  </select>
                <small class="text-muted">Choose a correct payment method</small>
              </div>
  
              <div class="col-md-6" style="display:none;">
                <label for="cc-expiration" class="form-label">Receipt</label>
                <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                <div class="invalid-feedback">
                  Expiration date required
                </div>
              </div>
              <div class="col-md-6">
                <label for="cc-expiration" class="form-label">Receipt</label>
                <input type="file" class="form-control" id="cc-expiration" disabled>
                <div class="invalid-feedback">
                  Expiration date required
                </div>
              </div>

            </div>
  
            <hr class="my-4">
  
            <button class="w-100 btn btn-danger btn-lg disabled" type="submit">Continue to checkout</button>
          </form>
        </div> 
        @endif
     
      </div>
    </main>
  </div>
  <script>
    function check(that) {
      if (that.value == "online") {
        document.getElementById("show").style.display = "block";
      } 
      else 
        document.getElementById("show").style.display = "none";
    }
  </script>

@endsection