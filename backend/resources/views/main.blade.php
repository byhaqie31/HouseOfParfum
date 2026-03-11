@extends('layouts.app')
@section('content') 

    <div class="container-fluid bg-main" style="height: 90vh;">
        <div class="container-fluid h-100">
    
            <div class="row h-100 align-items-center">
               
                <div class="col text-start ms-5">
                    
                    <p class="display-1 fw-bolder text-dark mb-0"> Explore What</p>
                    <p class="display-1 fw-bolder text-dark mb-0"> The Best Smell For You</p>
                    <p class="fs-4 ms-3 text-muted"> "A perfume is like a piece of clothing, a message, a way of presenting <br>
                        oneself a costume that differs according to the woman who wears it.""
                    </p>
                    <p class="blockquote-footer fs-5 ms-3 "> Paloma Picasso
                    </p>

                    <span>
                        <button class="btn btn-danger rounded-pill ms-3 shadow-lg p-3 mb-5 rounded fw-bold fs-5 status-width">Log In</button>
                        <button class="btn btn-primary-outline rounded-pill ms-3 shadow-lg p-3 mb-5 rounded fw-bold fs-5 text-danger"><i class="fa-regular fa-circle-play fs-5 "></i> Watch Video</button>
                        
                    </span>
                    
                    
                </div>
                <div class="col-sm-5">
                    <img src="{{ URL::asset('perfume.png'); }}" alt ="x"style="width: 80%; " />
                </div>   
            </div>
            {{--  --}}
        </div>
    </div>
@endsection