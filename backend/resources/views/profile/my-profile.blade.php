
@extends('layouts.app')
@section('content')

<div class="container-fluid border-top border-2" >
    <p class="text-center text-danger fs-6 mt-5 fs-1 fw-bold">My Profile<br>
        <div class="container bg-white shadow-lg p-3 mb-5 bg-body rounded" style="height: 70vh" >
            <div class="row text-start align-items-center h-100 mx-2">
              <div class="col-7 ">
                {!!Form::open(['action' => ['\App\Http\Controllers\ProfileController@store'], 'method' => 'POST', 'class' => 'pull-right', 'novalidate'])!!}
                    <div class="row g-3">
                    <div class="col-sm-12">
                        <label for="name" class="form-label">Full Name </label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$profile->name}}" required>
                        <div class="invalid-feedback">
                        Valid first name is required.
                        </div>
                    </div>

                    <div class="col-8">
                        <label for="email" class="form-label">Email <span class="text-muted"></span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{$profile->email}}"  >
                        <div class="invalid-feedback">
                        Please enter a valid email address for shipping updates.
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{$profile->phone}}"  placeholder="" required>
                        <div class="invalid-feedback">
                        Phone number is required
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{$profile->address}}" required>
                        <div class="invalid-feedback">
                        Please enter your shipping address.
                        </div>
                    </div>
                    </div>
        
                    <hr class="my-4">

                    <h4 class="mb-3">Loyalty</h4>
                    
                    <div class="row gy-3">

                        {{-- 3 tier loyalty program () 
                            0 - tier 1 (Member)
                            1 - tier 2 (Silver)
                            2 - tier 3 (Gold)
                            --}}
                        
                        @if ($profile->loyalty_rank === '0')
                        {{-- Tier 1 --}}
                        <div class="col-md-6">
                            <div class="card bg-success text-white bg-gradient">
                                <div class="card-body">
                                  <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                      <i class="fa-solid fa-crown fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                      <h3><b>Member Rank</b></h3>
                                      <p class="mb-0">{{$profile->loyalty_point}} pts</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                        </div>

                        @elseif ($profile->loyalty_rank === '1')
                        {{-- Tier 2 --}}
                        <div class="col-md-6" id="show">
                            <div class="card bg-secondary text-white bg-gradient">
                                <div class="card-body">
                                  <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                      <i class="fa-solid fa-crown fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h3><b>Silver Rank</b></h3>
                                      <p class="mb-0">{{$profile->loyalty_point}} pts</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                        </div>

                        @else
                        {{-- Tier 3 --}}
                        <div class="col-md-6" id="show">
                            <div class="card bg-warning text-white bg-gradient">
                                <div class="card-body">
                                  <div class="d-flex justify-content-between px-md-1">
                                    <div class="align-self-center">
                                      <i class="fa-solid fa-crown fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                      <h3><b>Gold Rank</b></h3>
                                      <p class="mb-0">{{$profile->loyalty_point}} pts</p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                        </div>
                        @endif

                    </div>
        
                    <hr class="my-4">
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn btn-danger btn-lg fw-bold" type="submit">Update</button>
                    </div>
                {!!Form::close()!!}
              </div>
              <div class="col-5">
                <p class="text-center text-muted fs-6 mt-5 fs-1 fw-bold">Hello, {{$profile->name}} !<br>
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp" class="img-fluid">
              </div>
            </div>
          </div>
</div>

@endsection