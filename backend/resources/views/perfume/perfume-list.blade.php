@extends('layouts.app')
@section('content') 
    <div class="container w-50">
        <p class="text-center text-danger fs-6 mt-5 fs-1 fw-bold">Perfumes List<br>
            <div class="input-group mb-3 bg-light">
                <input type="text" class="form-control" placeholder="Search a perfume..." aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-primary" type="button" id="button-addon2"><i class="fa-solid fa-search"></i> Search</button>
                <button class="btn btn-secondary" type="button" id="button-addon2"><i class="fa-solid fa-filter"></i></button>
              </div>
        @if(count($perfumes)>0)
            @foreach($perfumes as $perfume)
                <ul class="list-group list-group mt-2">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="d-flex justify-content-start">
                            <img src="images/perfumes/{{$perfume->image}}" style="height:4rem">
                            <div class="ms-4 me-auto">
                                <div class="fw-bold fs-5">{{$perfume->name}} <a href="perfume/{{$perfume->id}}" class="ms-2 badge link-white bg-danger text-decoration-none"> View More</a></div>
                                <span class="text-muted fs-6">{{$perfume->size}} ml</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2 ">
                        
                            <button class="rounded-pill badge bg-warning rounded-pill fs-6 "><a href= "perfume/{{$perfume->id}}/edit" class=" text-dark text-decoration-none">Edit</a></button>
                            
                            {!!Form::open(['action' => ['\App\Http\Controllers\PerfumeController@destroy', $perfume->id], 'method' => 'POST', 'enctype' => 'multipart/form-data'])!!}
                                {{Form::hidden('_method', 'DELETE')}}   
                                <button type="submit" class="badge bg-danger rounded-pill fs-6 ms-1 text-decoration-none">Delete</button>
                            {!!Form::close()!!}
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
    <a class="btn btn-danger add-perfume rounded-pill" href ="/perfume/create">
        <i class="fa-solid fa-plus"></i>
        <span>New Perfume</span>
      </a>
    @endif

</div>
@endsection