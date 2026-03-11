@extends('layouts.app')
@section('content') 

    <div class="container-fluid border-top border-2" >
    <p class="text-center text-danger fs-6 mt-5 fs-1 fw-bold">My Purchase<br>

        <div class="container-fluid h-100">
            <ul class="nav nav-pills mb-3 justify-content-center mt-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active rounded-pill" id="pills-pending-tab" data-bs-toggle="pill" data-bs-target="#pills-pending" type="button" role="tab" aria-controls="pills-pending" aria-selected="true"><span class="h3 fw-bold">Pending</span></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-pill" id="pills-delivery-tab" data-bs-toggle="pill" data-bs-target="#pills-delivery" type="button" role="tab" aria-controls="pills-delivery" aria-selected="false"><span class="h3 fw-bold">Delivery</span></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-pill" id="pills-done-tab" data-bs-toggle="pill" data-bs-target="#pills-done" type="button" role="tab" aria-controls="pills-done" aria-selected="false"><span class="h3 fw-bold">Done</span></a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active " id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab" tabindex="0">
                    <p class="text-center text-danger fs-6 mt-2 fs-2 fw-bold">Pending Postage</p>
                    <div class="container w-50 overflow-auto mb-5" style="height:70vh">
                        @if(count($pendings)>0)
                            @foreach($pendings as $pending)
                                <ul class="list-group list-group mt-2">
                                    {!!Form::open(['action' => ['\App\Http\Controllers\OrderController@destroy', $pending->cart_id], 'method' => 'POST'])!!}
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="d-flex justify-content-start">
                                            <img src="/images/perfumes/{{$pending->perfume_img}}" style="height:4rem">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold fs-5"> {{$pending->perfume_name}} (x{{$pending->quantity}})
                                                    {{Form::hidden('_method','PUT')}}
                                                    <button type="submit" class="btn btn-primary fs-6 fw-bold ms-2">Order Received</button>
                                                </div>
                                                <span class="text-muted fs-6">Size: {{$pending->perfume_size}} ml</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <div class="ms-2 me-auto">
                                                <div><span class="badge bg-secondary rounded-pill fs-6 status-width">Pending Postage</span></div>
                                            </div>
                                        </div>
                                    </li>
                                    {!!Form::close()!!}
                                </ul>
                            @endforeach
                        @else
                            <div class="col w-100 mt-5">
                                <p class="text-center">No record found</p>
                            </div>
                        @endif {{-- Kalau rajin tambahla card else--}}
                        
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-delivery" role="tabpanel" aria-labelledby="pills-delivery-tab" tabindex="0">
                    <p class="text-center text-danger fs-6 mt-2 fs-2 fw-bold">On Delivery
                    <div class="container w-50">
                        @if(count($deliveries)>0)
                            @foreach($deliveries as $delivery)
                                <ul class="list-group list-group mt-2">
                                    {!!Form::open(['action' => ['\App\Http\Controllers\OrderController@destroy', $delivery->cart_id], 'method' => 'POST'])!!}
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="d-flex justify-content-start">
                                                    <img src="/images/perfumes/{{$delivery->perfume_img}}" style="height:4rem">
                                                    <div class="ms-2 me-auto">
                                                        <div class="fw-bold fs-5"> {{$delivery->perfume_name}} (x{{$delivery->quantity}})
                                                            {{Form::hidden('_method','PUT')}}
                                                            <button type="submit" class="btn btn-primary fs-6 fw-bold ms-2">Order Received</button>
                                                        </div>
                                                        <span class="text-muted fs-6">Size: {{$delivery->perfume_size}} ml</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end mt-2">
                                                    <div class="ms-2 me-auto">
                                                        <div>
                                                            <span class="badge bg-warning rounded-pill fs-6 status-width">On Delivery</span> 
                                                        </div>
                                                        <div><span class="badge bg-none text-muted rounded-pill fs-6 status-width">JQX2022222</span></div>
                                                    </div>
                                                </div>
                                            </li>
                                    {!!Form::close()!!}
                                </ul>
                            @endforeach
                        @else
                            <div class="col w-100 mt-5">
                                <p class="text-center">No record found</p>
                            </div>
                        @endif
                    </div>
                        
                </div>
                <div class="tab-pane fade" id="pills-done" role="tabpanel" aria-labelledby="pills-done-tab" tabindex="0">
                    <p class="text-center text-danger fs-6 mt-2 fs-2 fw-bold">Completed</p>
                    <div class="container w-50">
                        @if(count($completes)>0)
                            @foreach($completes as $complete)
                                <ul class="list-group list-group mt-2">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="d-flex justify-content-start">
                                            <img src="/images/perfumes/{{$complete->perfume_img}}" style="height:4rem">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold fs-5">{{$complete->perfume_name}} (x{{$complete->quantity}})</div>
                                                <span class="text-muted fs-6">Size: 60 ml</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <div class="ms-2 me-auto">
                                                <div><span class="badge bg-success rounded-pill fs-6 status-width">Received</span></div>
                                                <div><span class="badge bg-none text-muted rounded-pill fs-6 status-width">12/2/2022</span></div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                        @else
                            <div class="col w-100 mt-5">
                                <p class="text-center">No record found</p>
                            </div>
                        @endif
                    </div>

                </div>
        </div>
    </div>

@endsection