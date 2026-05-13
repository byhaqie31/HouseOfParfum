@extends('layouts.app')
@section('content') 
 
    <div class="container-fluid border-top border-2 mb-5" >
        <div class="container-fluid h-100">
            <ul class="nav nav-pills mb-3 justify-content-center mt-3" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active rounded-pill" id="pills-parfum-tab" data-bs-toggle="pill" data-bs-target="#pills-parfum" type="button" role="tab" aria-controls="pills-parfum" aria-selected="true"><span class="h3 fw-bold">PARFUM</span></a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link rounded-pill" id="pills-edp-tab" data-bs-toggle="pill" data-bs-target="#pills-edp" type="button" role="tab" aria-controls="pills-edp" aria-selected="true"><span class="h3 fw-bold">EDP</span></a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link rounded-pill" id="pills-edt-tab" data-bs-toggle="pill" data-bs-target="#pills-edt" type="button" role="tab" aria-controls="pills-edt" aria-selected="false"><span class="h3 fw-bold">EDT</span></a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link rounded-pill" id="pills-edc-tab" data-bs-toggle="pill" data-bs-target="#pills-edc" type="button" role="tab" aria-controls="pills-edc" aria-selected="false"><span class="h3 fw-bold">EDC</span></a>
              </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="pills-parfum" role="tabpanel" aria-labelledby="pills-parfum-tab" tabindex="0">
                <p class="text-center text-muted fs-6 mt-2">EXCLUSIVELY<br>
                  <span class="text-center text-danger fs-1 fw-bold">PARFUM</span></p>
      
                  <div class="container">
                      <div class="row row-cols-1 row-cols-md-3 g-4">
                        {{-- Loop all perfume from DB --}}
                        @if(count($parfum)>0)
                          @foreach($parfum as $perfume)
                          <div class="col">
                            <div class="card text-center">
                              <h5 class="card-header  fs-5 p-3 fw-bold">{{$perfume->name}}</h5>
                              <img src="images/perfumes/{{$perfume->image}}" style="max-height:100%">
                              <div class="card-body">
                              @guest
                              <div class="d-flex justify-content-center">
                                <h5 class="card-title fs-3 fw-bold">{{$perfume->name}}</h5>
                                  <div class="d-flex gap-2 justify-content-end">
                                  </div>
                              </div>
                              
                              
                              @endguest
                                <a href="perfume/{{$perfume->id}}" class="text-decoration-none fw-bold">More details >>></a>
                                <p class="card-text fs-5 text-danger fw-bold">RM {{number_format((float)$perfume->price, 2, '.', '')}}</p>
                                @if ((Route::has('login')))
                                <div class="d-flex justify-content-center">
                                  @if(Session::get('role') === '0')
                                  {!! Form::open(['action' => ['\App\Http\Controllers\CartController@update', $perfume->id], 'method'=>'POST']) !!}
                                  <div class="d-flex gap-2 justify-content-end">
                                      <div class="number">
                                        <span class="minus badge bg-secondary">-</span>
                                        <input class="qty" id="qty" name="qty" type="text" value="1"/>
                                        <span class="plus badge bg-secondary">+</span>
                                      </div>
                                      {{Form::hidden('_method','PUT')}}
                                      <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-cart-plus"></i> Add to cart</button>
                                    </div>
                                  {!! Form::close() !!}
                                @endif
                                @endif
                                </div>  
                              </div>
                            </div>
                          </div>
                        @endforeach

                          @else
                          <div class="col w-100 mt-5">
                           <p class="text-center">No record found</p>
                          </div>
                          @endif
                          {{-- end loop --}}
                        </div>
                  </div>
              </div>
              <div class="tab-pane fade show" id="pills-edp" role="tabpanel" aria-labelledby="pills-edp-tab" tabindex="0">
                <p class="text-center text-muted fs-6 mt-2">EXCLUSIVELY<br>
                  <span class="text-center text-danger fs-1 fw-bold">EDP PERFUME</span></p>
      
                  <div class="container">
                      <div class="row row-cols-1 row-cols-md-3 g-4">
                        {{-- Loop all perfume from DB --}}
                        @if(count($edp)>0)
                          @foreach($edp as $perfume)
                          <div class="col">
                            <div class="card text-center">
                              <h5 class="card-header  fs-5 p-3 fw-bold">{{$perfume->name}}</h5>
                              <img src="images/perfumes/{{$perfume->image}}" style="max-height:100%">
                              <div class="card-body">
                              @guest
                              <div class="d-flex justify-content-center">
                                <h5 class="card-title fs-3 fw-bold">{{$perfume->name}}</h5>
                                  <div class="d-flex gap-2 justify-content-end">
                                  </div>
                              </div>
                              
                              
                              @endguest
                                <a href="perfume/{{$perfume->id}}" class="text-decoration-none fw-bold">More details >>></a>
                                <p class="card-text fs-5 text-danger fw-bold">RM {{number_format((float)$perfume->price, 2, '.', '')}}</p>
                                @if ((Route::has('login')))
                                <div class="d-flex justify-content-center">
                                  @if(Session::get('role') === '0')
                                  {!! Form::open(['action' => ['\App\Http\Controllers\CartController@update', $perfume->id], 'method'=>'POST']) !!}
                                  <div class="d-flex gap-2 justify-content-end">
                                      <div class="number">
                                        <span class="minus badge bg-secondary">-</span>
                                        <input class="qty" id="qty" name="qty" type="text" value="1"/>
                                        <span class="plus badge bg-secondary">+</span>
                                      </div>
                                      {{Form::hidden('_method','PUT')}}
                                      <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-cart-plus"></i> Add to cart</button>
                                    </div>
                                  {!! Form::close() !!}
                                @endif
                                @endif
                                </div>  
                              </div>
                            </div>
                          </div>
                        @endforeach

                          @else
                          <div class="col w-100 mt-5">
                           <p class="text-center">No record found</p>
                          </div>
                          @endif
                          {{-- end loop --}}
                        </div>
                  </div>
              </div>
              <div class="tab-pane fade" id="pills-edt" role="tabpanel" aria-labelledby="pills-edt-tab" tabindex="0">
                <p class="text-center text-muted fs-6 mt-2">EXCLUSIVELY<br>
                  <span class="text-center text-danger fs-1 fw-bold">EDT PERFUME</span></p>
                  <div class="container">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                      {{-- Loop all perfume from DB --}}
                      @if(count($edt)>0)
                          @foreach($edt as $perfume)
                          <div class="col">
                            <div class="card text-center">
                              <h5 class="card-header  fs-5 p-3 fw-bold">{{$perfume->name}}</h5>
                              <img src="images/perfumes/{{$perfume->image}}" style="max-height:100%">
                              <div class="card-body">
                              @guest
                              <div class="d-flex justify-content-center">
                                <h5 class="card-title fs-3 fw-bold">{{$perfume->name}}</h5>
                                  <div class="d-flex gap-2 justify-content-end">
                                  </div>
                              </div>
                              
                              
                              @endguest
                                <a href="perfume/{{$perfume->id}}" class="text-decoration-none fw-bold">More details >>></a>
                                <p class="card-text fs-5 text-danger fw-bold">RM {{number_format((float)$perfume->price, 2, '.', '')}}</p>
                                @if ((Route::has('login')))
                                <div class="d-flex justify-content-center">
                                  @if(Session::get('role') === '0')
                                  {!! Form::open(['action' => ['\App\Http\Controllers\CartController@update', $perfume->id], 'method'=>'POST']) !!}
                                  <div class="d-flex gap-2 justify-content-end">
                                      <div class="number">
                                        <span class="minus badge bg-secondary">-</span>
                                        <input class="qty" id="qty" name="qty" type="text" value="1"/>
                                        <span class="plus badge bg-secondary">+</span>
                                      </div>
                                      {{Form::hidden('_method','PUT')}}
                                      <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-cart-plus"></i> Add to cart</button>
                                    </div>
                                  {!! Form::close() !!}
                                @endif
                                @endif
                                </div>  
                              </div>
                            </div>
                          </div>
                        @endforeach

                        @else
                        <div class="col w-100 mt-5">
                         <p class="text-center">No record found</p>
                        </div>
                        @endif
                        {{-- end loop --}}
                      </div>
                </div>
              </div>
              <div class="tab-pane fade" id="pills-edc" role="tabpanel" aria-labelledby="pills-edc-tab" tabindex="0">
                <p class="text-center text-muted fs-6 mt-2">EXCLUSIVELY<br>
                  <span class="text-center text-danger fs-1 fw-bold">EDC PERFUME</span></p>

                  <div class="container">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                      {{-- Loop all perfume from DB --}}
                      @if(count($edc)>0)
                          @foreach($edc as $perfume)
                          <div class="col">
                            <div class="card text-center">
                              <h5 class="card-header  fs-5 p-3 fw-bold">{{$perfume->name}}</h5>
                              <img src="images/perfumes/{{$perfume->image}}" style="max-height:100%">
                              <div class="card-body">
                              @guest
                              <div class="d-flex justify-content-center">
                                <h5 class="card-title fs-3 fw-bold">{{$perfume->name}}</h5>
                                  <div class="d-flex gap-2 justify-content-end">
                                  </div>
                              </div>
                              
                              
                              @endguest
                                <a href="perfume/{{$perfume->id}}" class="text-decoration-none fw-bold">More details >>></a>
                                <p class="card-text fs-5 text-danger fw-bold">RM {{number_format((float)$perfume->price, 2, '.', '')}}</p>
                                @if ((Route::has('login')))
                                <div class="d-flex justify-content-center">
                                  @if(Session::get('role') === '0')
                                  {!! Form::open(['action' => ['\App\Http\Controllers\CartController@update', $perfume->id], 'method'=>'POST']) !!}
                                  <div class="d-flex gap-2 justify-content-end">
                                      <div class="number">
                                        <span class="minus badge bg-secondary">-</span>
                                        <input class="qty" id="qty" name="qty" type="text" value="1"/>
                                        <span class="plus badge bg-secondary">+</span>
                                      </div>
                                      {{Form::hidden('_method','PUT')}}
                                      <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-cart-plus"></i> Add to cart</button>
                                    </div>
                                  {!! Form::close() !!}
                                @endif
                                @endif
                                </div>  
                              </div>
                            </div>
                          </div>
                        @endforeach      
                      @else
                        <div class="col w-100 mt-5">
                         <p class="text-center">No record found</p>
                        </div>
                        @endif
                        {{-- end loop --}}
                      </div>
                </div>

              </div>
            </div>
        </div>
    </div>
    @if(Session::get('role') === '1')
    <a class="btn btn-danger add-perfume rounded-pill" href ="/perfume/create">
      <i class="fa-solid fa-plus"></i>
      <span>New Perfume</span>
    </a>
    @endif
    
    <script>
      var minus = document.querySelector(".minus")
      var add = document.querySelector(".plus");
      var quantityNumber = document.querySelector(".qty");
      var currentValue = parseInt(document.getElementById("qty").value);


      minus.addEventListener("click", function(){
        if(currentValue > 0)
          currentValue -= 1;
        else
          currentValue = 0;

          quantityNumber.value = currentValue;
          console.log(currentValue)
      });

      add.addEventListener("click", function() {
          currentValue += 1;
          quantityNumber.value = currentValue;
          console.log(currentValue);
      });
    </script>
@endsection