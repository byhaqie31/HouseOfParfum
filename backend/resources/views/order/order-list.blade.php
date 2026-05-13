@extends('layouts.app')
@section('content') 

    <div class="container-fluid border-top border-2" >
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

            {{-- Pane Tabs --}}
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab" tabindex="0">
                    <p class="text-center text-danger fs-6 mt-2 fs-2 fw-bold">Pending Postage</p>
                    <div class="container w-50">
                        @if(count($pendings)>0)
                            @foreach($pendings as $order)
                                <ul class="list-group list-group mt-2">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="d-flex justify-content-start">
                                            <img src="https://www.zheolab.com/wp-content/uploads/2021/02/30ml-Spray-Gold-Screw-Cap-Square.png" style="height:4rem">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold fs-5">{{$order->perfume_name}} (x{{$order->quantity}})</div>
                                                <span class="text-muted fs-6">{{$order->perfume_size}} ml </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <div class="ms-2 me-auto">
                                                <div><span class="badge bg-secondary rounded-pill fs-6 status-width">Pending Postage</span></div>
                                                <div><button type="button" data-bs-target="#approveModal{{$order->id}}" data-bs-toggle="modal" class="btn btn-link text-muted rounded-pill fs-6 status-width">Update</button></div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                 <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{$order->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Update?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {!!Form::open(['action' => ['\App\Http\Controllers\OrderController@destroy', $order->cust_id], 'method' => 'POST'])!!}
                                            <div class="mb-3">
                                                <label for="tracking_no" class="form-label">Tracking Number</label>
                                                <input type="tracking_no" class="form-control" id="tracking_no" name="tracking_no" placeholder="Enter tracking no...">
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            {{Form::hidden('_method','PUT')}}
                                            {{Form::submit('Confirm', ['class'=>'btn btn-danger fw-bold'])}}
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><b>Close</b></button>
                                        </div>
                                        {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col w-100 mt-5">
                                <p class="text-center">No record found</p>
                            </div>
                        @endif
                    </div>
                        
                </div>
                <div class="tab-pane fade" id="pills-delivery" role="tabpanel" aria-labelledby="pills-delivery-tab" tabindex="0">
                    <p class="text-center text-danger fs-6 mt-2 fs-2 fw-bold">On Delivery
                    <div class="container w-50">
                        @if(count($deliveries)>0)
                            @foreach($deliveries as $order)
                                <ul class="list-group list-group mt-2">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="d-flex justify-content-start">
                                            <img src="https://www.zheolab.com/wp-content/uploads/2021/02/30ml-Spray-Gold-Screw-Cap-Square.png" style="height:4rem">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold fs-5">{{$order->perfume_name}} (x{{$order->quantity}})</div>
                                                <span class="text-muted fs-6">{{$order->perfume_size}} ml</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <div class="ms-2 me-auto">
                                                <div><span class="badge bg-warning rounded-pill fs-6 status-width">On Delivery</span></div>
                                                <div><span class="badge bg-none text-muted rounded-pill fs-6 status-width">{{$order->tracking_no}}</span></div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                 <!-- Approve Modal -->
                                 <div class="modal fade" id="approveModal{{$order->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Update?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {!!Form::open(['action' => ['\App\Http\Controllers\OrderController@destroy', $order->cust_id], 'method' => 'POST'])!!}
                                            <div class="mb-3">
                                                <label for="tracking_no" class="form-label">Tracking Number</label>
                                                <input type="tracking_no" class="form-control" id="tracking_no" name="tracking_no" placeholder="Enter tracking no...">
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            {{Form::hidden('_method','PUT')}}
                                            {{Form::submit('Confirm', ['class'=>'btn btn-danger fw-bold'])}}
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><b>Close</b></button>
                                        </div>
                                        {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
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
                            @foreach($completes as $order)
                                <ul class="list-group list-group mt-2">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="d-flex justify-content-start">
                                            <img src="https://www.zheolab.com/wp-content/uploads/2021/02/30ml-Spray-Gold-Screw-Cap-Square.png" style="height:4rem">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold fs-5">{{$order->perfume_name}} (x{{$order->quantity}})</div>
                                                <span class="text-muted fs-6">{{$order->perfume_size}} ml</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <div class="ms-2 me-auto">
                                                <div><span class="badge bg-success rounded-pill fs-6 status-width">Completed</span></div>
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