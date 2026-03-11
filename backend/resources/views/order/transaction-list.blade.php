@extends('layouts.app')
@section('content') 
    <div class="container w-50">
        <p class="text-center text-danger fs-6 mt-5 fs-1 fw-bold">Transactions List <br>
            
            <div class="input-group mb-3 bg-light">
                <input type="text" class="form-control" placeholder="Search a perfume..." aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-primary" type="button" id="button-addon2"><i class="fa-solid fa-search"></i> Search</button>
                <button class="btn btn-secondary" type="button" id="button-addon2"><i class="fa-solid fa-filter"></i></button>
              </div>
        @if(count($orders)>0)
            @for ($i = 0; $i < (count($transactions)); $i++)
            <ul class="list-group list-group mt-4">
                <span><b>Order ID: </b>{{$transactions[$i]}}
                    <button type="button" data-bs-target="#myModal{{$receipts[$i]}}" data-bs-toggle="modal" class="badge bg-primary mb-2"> View Receipts</button>

                </span>
                @foreach($orders as $order)
                    @if($order->transaction_id === $transactions[$i])
                        {{-- Check dulu sama ka dak id, kalau sama loop list --}}
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="d-flex justify-content-start">
                                <img src="/images/perfumes/{{$order->perfume_img}}" style="height:4rem">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold fs-5">{{$order->perfume_name}} </div>
                                    <span class="text-muted fs-6">{{$order->perfume_size}} ml</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <div class="ms-2 me-auto text-end">
                                    <div><span class="text-muted">(x{{$order->quantity}}) pcs</span></div>
                                    <div><span class="fw-bold fs6">Total RM {{$order->total_price}} </span></div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
             <!-- Open Image Modal -->
            <div class="modal fade" id="myModal{{$receipts[$i]}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete Perfume <b>{{$receipts[$i]}}</b>?
                        
                        
                    </div>
                    </div>
                </div>
            </div>
            <?php $i = $i+1;?>
            @endfor
        @else
            <div class="col w-100 mt-5">
                <p class="text-center">No record found</p>
            </div>
        @endif
    </div>
@endsection