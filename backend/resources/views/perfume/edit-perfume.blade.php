
@extends('layouts.app')
@section('content')

<div class="container-fluid border-top border-2" >
    <p class="text-center text-danger fs-6 mt-5 fs-1 fw-bold">Edit Perfume Details<br>
        <div class="container bg-white shadow-lg p-3 mb-5 bg-body rounded" >
            <div class="row text-start align-items-center h-100 mx-2">
              <div class="col-7 ">
                {!!Form::open(['action' => ['\App\Http\Controllers\PerfumeController@update', $perfume->id], 'method' => 'POST', 'enctype' => 'multipart/form-data'])!!}
                <p class="text-center text-danger mt-2 fs-1 fw-bold mb-0">{{$perfume->name}}</p>
                <p class="text-center text-muted fs-6 mt-0">Fill all the details to add new perfume</span></p>
                
                {{-- id accordionPanelsStayOpeExample to stay open --}}
                <hr class="my-4">
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
                                <div class="col-md-4">
                                    <label for="rrp" class="form-label">RRP</label>
                                    <input type="text" class="form-control" name="rrp" placeholder="Enter product RRP (AUD)">
                                </div>
                                <div class="col-md-4">
                                    <label for="rrp_myr" class="form-label">RRP MYR</label>
                                    <input type="text" class="form-control" name="rrp_myr" placeholder="Enter product RRP (RM)">
                                </div>
                                <div class="col-md-4">
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
                                    <select id="size" name = "size" class="form-select">
                                        <option value = "0" disabled selected>Select size</option>
                                        <option value="30" >30 ml</option>
                                        <option value="60">60 ml</option>
                                        <option value="90">90 ml</option>
                                        <option value="120" >120 ml</option>
                                    </select>
                                </div>   
                                <div class="col-md-4">
                                    <label for="year" class="form-label">Manufacturing Year</label>
                                    <input type="text" class="form-control" name="year" placeholder="Enter product price (RM)">
                                </div>    
                                <div class="col-md-4">
                                    <label for="quality" class="form-label">Quality</label>
                                    <select id="quality" name = "quality" class="form-select">
                                        <option value = "0" disabled selected>Select quality</option>
                                        <option value="edp" >Eau De Parfum (EDP)</option>
                                        <option value="edt">Eau De Toilette (EDT)</option>
                                        <option value="edc">Eau De Cologne (EDC)</option>
                                    </select>
                                </div>    
                            </div>
                        </div>
                        </div>
                    </div>

                    {{-- Accordion 3: Main Accord  --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                            3. Accords
                        </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="row g-3">
                            <div class="col-sm-4">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" value="citrus" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Citrus 
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="floral" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Floral
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="oriental" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Oriental
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" value="woody" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Woody
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="amber" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Amber
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="chypre" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Chypre
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" value="fougère" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Fougère
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="spicy" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Spicy
                                    </label>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="musky" id="flexCheckDefault" name="accord[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Musky
                                    </label>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    {{-- Accordion 4: Suits  --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseThree">
                            4. Suits
                        </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
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

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseThree">
                            5. Notes
                        </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label for="topnotes" class="form-label">Top Notes</label>
                                    <textarea class="form-control" id="topnotes" name="topnotes" rows="2"></textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label for="middlenotes" class="form-label">Middle Notes</label>
                                    <textarea class="form-control" id="middlenotes" name="middlenotes" rows="2"></textarea>
                                </div>
                                <div class="col-sm-4">
                                    <label for="basenotes" class="form-label">Base Notes</label>
                                    <textarea class="form-control" id="basenotes" name="basenotes" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-sm-12">
                                    <label for="history" class="form-label">History</label>
                                    <textarea class="form-control" id="history"  name="history" maxlength="1200" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button type="submit" class="btn btn-danger btn-lg fw-bold" type="submit">Update</button>
                    </div>
                {!!Form::close()!!}
              </div>
              <div class="col-5">
                <p class="text-center text-muted fs-4 mt-5 fw-bold"> <img src="/images/perfumes/{{$perfume->image}}" class="img-fluid w-75">
                    <br>{{$perfume->name}} ({{$perfume->brand}}) 
                
                </p>
              </div>
            </div>
          </div>
</div>

@endsection