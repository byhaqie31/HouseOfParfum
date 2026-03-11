@extends('layouts.app')
@section('content') 
    <div class="container w-50">
        <p class="text-center text-danger fs-6 mt-5 fs-1 fw-bold">Brands List<br>
            <div class="input-group mb-3 bg-light">
                <input type="text" class="form-control" placeholder="Search a perfume..." aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-primary" type="button" id="button-addon2"><i class="fa-solid fa-search"></i> Search</button>
                <button class="btn btn-secondary" type="button" id="button-addon2"><i class="fa-solid fa-filter"></i></button>
              </div>
        @if(count($brands)>0)
            @foreach($brands as $brand)
                <ul class="list-group list-group mt-2">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="d-flex justify-content-start">
                            <img src="images/brands/{{$brand->image}}" style="height:4rem">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold fs-5">{{$brand->name}}</div>
                                <span class="text-muted fs-6">{{$brand->code}}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <div class="ms-2 me-auto">
                                <div>
                                    {!!Form::open(['action' => ['\App\Http\Controllers\BrandController@destroy', $brand->id], 'method' => 'POST', 'enctype' => 'multipart/form-data'])!!}
                                        {{Form::hidden('_method', 'DELETE')}}   
                                        <button type="submit" class="badge bg-danger rounded-pill fs-6">Delete</button>
                                    {!!Form::close()!!}
                                </div>
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
    @if(Session::get('role') === '1')
    <button type="button" class="btn btn-danger add-perfume rounded-pill"  data-bs-target="#myModal" data-bs-toggle="modal" >
      <i class="fa-solid fa-plus"></i>
      <span>New Brand</span>
    </button>
    @endif

    <!-- Approve Modal -->
 <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Confirm Update?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {!!Form::open(['action' => ['\App\Http\Controllers\BrandController@store'], 'method' => 'POST', 'enctype' => 'multipart/form-data'])!!}
            <div class="row g-3">
                <div class="col-md-8">
                    <label for="name" class="form-label">Brand Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter brand name">
                </div>
                <div class="col-md-4">
                    <label for="code" class="form-label">Brand Code</label>
                    <input type="text" class="form-control" name="code" placeholder="e.g. YSL">
                </div>  
                <div class="col-md-12">
                    <label for="image" class="form-label">Brand Image</label>
                    <input type="file" class="form-control" name="image" >
                </div>        
            </div>
            
            
        </div>
        <div class="modal-footer">
            {{-- {{Form::hidden('_method','PUT')}} --}}
            {{Form::submit('Confirm', ['class'=>'btn btn-danger fw-bold'])}}
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><b>Close</b></button>
        </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection