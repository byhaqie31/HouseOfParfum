@extends('layouts.app')
@section('content') 

    <div class="container-fluid border-top border-2" >
        <div class="container-fluid h-100">

            <p class="text-center text-muted fs-5 mt-4">CONTACT<br>
            <span class="text-center text-dark fs-1 fw-bold">Need Help? <span class="text-danger">Contact Us</span></span></p>

            
            {{--  --}}
        </div>
    </div>
    <div class="container overflow-hidden text-start mb-3" style="height:30vh">
        <div class="row gy-5">
            <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31881.78159053671!2d101.74885697584392!3d2.7502672217710837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdc1a44abdc9c7%3A0x1a444dd645c70579!2sBandar%20Baru%20Enstek%2C%20Negeri%20Sembilan!5e0!3m2!1sen!2smy!4v1656938359218!5m2!1sen!2smy" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
    <div class="container overflow-hidden text-start ">
        <div class="row gy-5">
            <div class="col-6">
                <div class="card p-3 border bg-light">
                    <div class="card-body">
                    <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <a class="btn btn-danger me-4 rounded-pill ctc-icon">
                                <i class="fa-regular fa-map fa-2x text-white mt-2"></i>
                            </a>

                        </div>
                        <div>
                            <h4>Our Address</h4>
                            <p class="mb-0 fs-5 text-muted">Bandar Baru Enstek, Sepang, 71800, Negeri Sembilan</p>
                        </div>
                        </div>
                        {{-- <div class="align-self-center">
                        <h2 class="h1 mb-0">18,000</h2>
                        </div> --}}
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card p-3 border bg-light">
                    <div class="card-body">
                    <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <a class="btn btn-danger me-4 rounded-pill ctc-icon">
                                <i class="fa-regular fa-envelope fa-2x text-white mt-2"></i>
                            </a>

                        </div>
                        <div>
                            <h4>Email Us</h4>
                            <p class="mb-0 fs-5 text-muted">ameerzacheryv@gmail.com</p>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card p-3 border bg-light">
                    <div class="card-body">
                    <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <a class="btn btn-danger me-4 rounded-pill ctc-icon">
                                <i class="fa-solid fa-phone fa-2x text-white mt-2"></i>
                            </a>

                        </div>
                        <div>
                            <h4>Call Us</h4>
                            <p class="mb-0 fs-5 text-muted">+6019-2301 787</p>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card p-3 border bg-light">
                    <div class="card-body">
                    <div class="d-flex justify-content-between p-md-1">
                        <div class="d-flex flex-row">
                        <div class="align-self-center">
                            <a class="btn btn-danger me-4 rounded-pill ctc-icon">
                                <i class="fa-regular fa-clock fa-2x text-white mt-2"></i>
                            </a>

                        </div>
                        <div>
                            <h4>Operation Hour</h4>
                            <p class="mb-0 fs-5 text-muted"><strong>Mon-Sat: </strong>10AM - 10PM | <strong>Sunday: </strong>Closed</p>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="float-end me-5 mt-5 add-perfume">
        <button class="btn btn-danger rounded-pill ctc-icon fw-bold"><i class="fa-solid fa-arrow-up "></i></button>
    </div> --}}
@endsection
    