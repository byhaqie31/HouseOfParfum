@extends('layouts.app')
@section('content')


<div class="container  overflow-auto">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card ">
                <h2 class="text-center mt-4 text-danger fw-bold">ADD A NEW PERFUME</h2>
                <span class="text-center text-muted mb-3">Fill all the details to add new perfume</span>
                <div class="card-body mx-3 scroll">
                    {!! Form::open(['class' => "row g-3", 'action' => '\App\Http\Controllers\PerfumeController@store', 'method'=>'POST','enctype' => 'multipart/form-data']) !!}
                        
                    <div class="row g-3">
                        <div class="col-md-4">
                            <span>
                                <label for="brand" class="form-label">Brand</label> 
                                <button type="button" data-bs-target="#myModal" data-bs-toggle="modal" class="badge bg-primary text-white rounded-pill">+ New Brand</button>
                            </span>
                            <select id="brand" name = "brand" class="form-select">
                                <option value ="0" disabled selected>Choose brand...</option>
                                @if (count($brands)>0)
                                    @foreach ($brands as $brand)
                                        <option value="{{$brand->code}}">{{$brand->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter product name">
                        </div>
                        <div class="col-md-4">
                            <label for="image" class="form-label">Item Image</label>
                            <input type="file" class="form-control" name="image" accept=".pdf, .jpeg, .jpg, .png">
                        </div>
                    </div>
                        
      
                        {{-- id accordionPanelsStayOpeExample to stay open --}}
                        <div class="accordion accordion" id="accordionFlushExample">
                        
                        {{-- Accordion 1: Price  --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                1. Price
                            </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="row g-3">
                                        <input type="hidden" class="form-control" name="rrp" value ="0" placeholder="Enter product RRP (AUD)">
                                    <div class="col-md-6">
                                        <label for="rrp_myr" class="form-label">RRP MYR</label>
                                        <input type="text" class="form-control" name="rrp_myr" placeholder="Enter product RRP (RM)">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="price" class="form-label">Selling Price</label>
                                        <input type="text" class="form-control" name="price" placeholder="Enter product price (RM)">
                                    </div>        
                                </div>
                            </div>
                            </div>
                        </div>

                        {{-- Accordion 2: Quality  --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                2. Quality
                            </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="size" class="form-label">Perfume size</label>
                                        <input type="number" class="form-control" name="size" placeholder="Enter perfume size" max="500" min="0">

                                    </div>   
                                    <div class="col-md-4">
                                        <label for="year" class="form-label">Manufacturing Year</label>
                                        <input type="number" class="form-control" name="price" placeholder="Enter year" min="0">
                                    </div>    
                                    <div class="col-md-4">
                                        <label for="quality" class="form-label">Quality</label>
                                        <select id="quality" name = "quality" class="form-select">
                                            <option value = "0" disabled selected>Select quality</option>
                                            <option value="parfum" >Parfum</option>
                                            <option value="edp" >Eau De Parfum (EDP)</option>
                                            <option value="edt">Eau De Toilette (EDT)</option>
                                            <option value="edc">Eau De Cologne (EDC)</option>
                                        </select>
                                    </div>    
                                </div>
                            </div>
                            </div>
                        </div>

                        {{-- Accordion 3: Suits  --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                3. Suits
                            </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                               <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select id="gender" name = "gender" class="form-select">
                                            <option value = "0" disabled selected>Select suit</option>
                                            <option value="men" >Men</option>
                                            <option value="women">Women</option>
                                            <option value="unisex">Unisex</option>
                                            <option value="giftbox" >Giftbox</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="season" class="form-label">Season</label>
                                        <select id="season" name = "season" class="form-select">
                                            <option value = "0" disabled selected>Select season</option>
                                            <option value="autumn" >Autumn</option>
                                            <option value="winter">Winter</option>
                                            <option value="spring">Spring</option>
                                            <option value="summer" >Summer</option>
                                        </select>
                                    </div> 
                                    <div class="col-md-4">
                                        <label for="time" class="form-label">Time</label>
                                        <select id="time" name = "time" class="form-select">
                                            <option value = "0" disabled selected>Select time</option>
                                            <option value="day" >Day</option>
                                            <option value="night" >Night</option>
                                        </select>
                                    </div> 
                                </div>
                              </div>
                            </div>
                        </div>

                        {{-- Accordion 4: Percentage  --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseThree">
                                4. Percentage
                            </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">

                                    <span class="fs-5 fw-bold mb-0">Season</span>
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <label for="percent_winter" class="form-label">Percentage Winter</label>
                                            <input type="number" class="form-control" name="percent_winter" placeholder="Enter percentage" max="100" min="0">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="percent_spring" class="form-label">Percentage Spring</label>
                                            <input type="number" class="form-control" name="percent_spring" placeholder="Enter percentage" max="100" min="0">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="percent_summer" class="form-label">Percentage Summer</label>
                                            <input type="number" class="form-control" name="percent_summer" placeholder="Enter percentage" max="100" min="0">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="percent_autumn" class="form-label">Percentage Autumn</label>
                                            <input type="number" class="form-control" name="percent_autumn" placeholder="Enter percentage" max="100" min="0">
                                        </div>
                                    </div>

                                    <span class="fs-5 fw-bold mb-0 mt-2">Time</span>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label for="percent_day" class="form-label">Percentage Day</label>
                                            <input type="number" class="form-control" name="percent_day" placeholder="Enter percentage" max="100" min="0">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="percent_night" class="form-label">Percentage Night</label>
                                            <input type="number" class="form-control" name="percent_night" placeholder="Enter percentage" max="100" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseThree">
                                5. Notes
                            </button>
                            </h2>
                            <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-sm-3">
                                        <label for="accord" class="form-label">Main Accords</label>
                                        <textarea class="form-control" id="accord" name="accord" rows="2"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="topnotes" class="form-label">Top Notes</label>
                                        <textarea class="form-control" id="topnotes" name="topnotes" rows="2"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="middlenotes" class="form-label">Middle Notes</label>
                                        <textarea class="form-control" id="middlenotes" name="middlenotes" rows="2"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="basenotes" class="form-label">Base Notes</label>
                                        <textarea class="form-control" id="basenotes" name="basenotes" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-sm-12">
                                        <label for="history" class="form-label">History</label>
                                        <textarea class="form-control" id="history"  maspringength="1200" name="history" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                        <div class="d-grid gap-2 col-4 mx-auto">
                            {{Form::submit('SUBMIT', ['class'=>'btn btn-primary fw-bold'])}}
                        </div>
                
                        
                        
                        {!! Form::close() !!} 
                       
                       
                </div>
            </div>
        </div>
    </div>
</div>
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

<script>
    var expanded = false;

    function showCheckboxes() {
    var checkboxes = document.getElementById("season");
    if (!expanded) {
        checkboxes.style.display = "block";
        expanded = true;
    } else {
        checkboxes.style.display = "none";
        expanded = false;
    }
    }
</script>

@endsection