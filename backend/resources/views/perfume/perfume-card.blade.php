@extends('layouts.app')
@section('content') 

    <div class="container-fluid border-top border-2" >
        <div class="container bg-white shadow-lg p-3 mb-0 bg-body rounded mt-5">
            <h2 class="text-center text-uppercase mt-3"><strong>{{$perfume->brand}} {{$perfume->name}}</strong></h2>
            @if(Session::get('role') === '1')
                <div class="text-center">
                    <a href="{{$perfume->id}}/edit" class="btn btn-warning rounded-pill me-1 fw-bold"><i class="fa-solid fa-gear"></i> Edit</a>
                    <button type="button" data-bs-target="#myModal" data-bs-toggle="modal" class="btn btn-danger rounded-pill ms-1 fw-bold"><i class="fa-solid fa-trash-can"></i> Delete</button>
                </div>
            @endif
            <div class="row" style="height:auto">
            <div class="col-6">
                <div class="row mt-3 mb-5">
                    <div class="col ">
                        <img src="/images/perfumes/{{$perfume->image}}" class="w-75 rounded mx-auto d-block mt-5" alt="...">
                        {{-- <img  src="" class="align-self-center">                       --}}
                    </div>
                    <div class="col">
                        <div class="container mt-5 fs-5">
                            <p><strong>Perfume: </strong>Dior Sauvage</p>
                            <p><strong>Type: </strong>
                                @if ($perfume->quality === 'EDP')
                                Eau De Parfum (EDP)

                                @elseif ($perfume->quality === 'EDT')
                                Eau De Toilette (EDT)

                                @else
                                Eau De Cologne (EDP)
                                @endif
                            </p>
                            <p><strong>Gender: </strong>
                                @if ($perfume->suit === 'men')
                                Men's Perfume

                                @elseif ($perfume->quality === 'women')
                                Women's Perfume

                                @elseif ($perfume->suit === 'giftbox')
                                Gift Box
                                
                                @else
                                Unisex Perfume 

                                @endif
                            </p>
                            <p><strong>Size: </strong>{{$perfume->size}} ml </p>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 "> 
                    <div class="container">
                        <div class="mx-5">
                            <span class="h4 fw-bold">Season</span>
                            <br>
                            <span class="h6 text-start mt-2">Winter </span>
                            <div class="progress" style="height:15px; width:100%">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: {{$perfume->percent_winter}}%; font-size: 1.0em" aria-valuenow="{{$perfume->percent_winter}}" aria-valuemin="0" aria-valuemax="100">{{$perfume->percent_winter}}%</div>
                            </div><br>
                            <span class="h6 text-start ">Spring</span>
                            <div class="progress" style="height:15px; width:100%">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: {{$perfume->percent_spring}}%; font-size: 1.0em" aria-valuenow="{{$perfume->percent_spring}}" aria-valuemin="0" aria-valuemax="100">{{$perfume->percent_spring}}%</div>
                            </div><br>
                            <span class="h6 text-start ">Summer</span>
                            <div class="progress" style="height:15px; width:100%">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: {{$perfume->percent_summer}}%; font-size: 1.0em" aria-valuenow="{{$perfume->percent_summer}}" aria-valuemin="0" aria-valuemax="100">{{$perfume->percent_summer}}%</div>
                            </div><br>
                            <span class="h6 text-start ">Autumn</span>
                            <div class="progress" style="height:15px; width:100%">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: {{$perfume->percent_autumn}}%; font-size: 1.0em;" aria-valuenow="{{$perfume->percent_autumn}}" aria-valuemin="0" aria-valuemax="100">{{$perfume->percent_autumn}}%</div>
                            </div><br><br>
                            <span class="h4 fw-bold">Time</span>
                            <br>
                            <span class="h6 text-start ">Day</span>
                            <div class="progress" style="height:15px; width:100%">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: {{$perfume->percent_day}}%; font-size: 1.0em;" aria-valuenow="{{$perfume->percent_day}}" aria-valuemin="0" aria-valuemax="100">{{$perfume->percent_day}}%</div>
                            </div><br>
                            <span class="h6 text-start ">Night</span>
                            <div class="progress" style="height:15px; width:100%">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label" style="width: {{$perfume->percent_night}}%; font-size: 1.0em;" aria-valuenow="{{$perfume->percent_night}}" aria-valuemin="0" aria-valuemax="100">{{$perfume->percent_night}}%</div>
                            </div><br>
                 
                        </div>
                    </div>
                </div>

              </div>
              <div class="col-6">
                <div class="container mt-5">
                    <h2 class="mx-4 fw-bold">About {{$perfume->brand}} {{$perfume->name}}<hr class="border-3 border-top border-primary mt-1 w-25"></h2>
                <p class="mx-4 fs-5">
                   {{$perfume->history}}
                </p>
                </div>
              </div>
            </div>
            
          </div>
          
            <div class="container rounded mt-5 ">  
            <div class="card-group text-center ">
                <div class="card bg-white shadow-lg p-3  mx-2">
                  <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <i class="fa-regular fa-gem fa-5x"></i>
                  </div>
                </div>
                <div class="card bg-white shadow-lg p-3  mx-2">
                  <div class="card-body ">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                  </div>
                </div>
                <div class="card body bg-white shadow-lg p-3  mx-2">
                  <div class="card-">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                  </div>
                </div>
              </div>
            </div>

         <!-- Delete Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete Perfume <b>{{$perfume->brand}} {{$perfume->name}}</b>?
                    
                    
                </div>
                <div class="modal-footer">
                    {!!Form::open(['action' => ['\App\Http\Controllers\PerfumeController@destroy', $perfume->id], 'method' => 'POST', 'enctype' => 'multipart/form-data'])!!}
                                {{Form::hidden('_method', 'DELETE')}}   
                                <button type="submit" class="btn btn-danger fw-bold fs-6 ms-1 text-decoration-none">Delete</button>
                    {!!Form::close()!!}
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal"><b>Close</b></button>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection