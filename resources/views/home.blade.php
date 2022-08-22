@extends('layouts.app-full-width')

@section('title', 'Courses')

@section('content')


        {{-- Dummy spacing
        <div class="row p-5 bg-primary no-gutters mb-7"></div> --}}

        {{-- Title with CTA  --}}
        <div class="container-fluid p-0 bg-primary pt-7 pb-4">
            <div class="row bg-primary pl-6 pt-6 pb-4 no-gutters">
                    <div class="col-sm-6 text-white">
                        <h1 class="display-3 d-sm-inline">Learning platform</h1>
                        <p class="my-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-secondary bg-grad my-6 py-2 px-5">
                            <h3 class="display-5 pt-2 text-primary"><b>Get started</b></h3>
                        </button>
                    </div>
            </div>
            <div class="row bg-primary mb-7"></div>
        </div>

        {{-- Second part of the landing page  --}}
        <div class="container-fluid pl-0 bg-white">
            <div class="row no-gutters align-items-center my-7">
                <div class="col-sm-4">
                    {{--
                    <img src="https://image.shutterstock.com/image-photo/smart-home-automation-control-system-600w-1281529354.jpg">
                    --}}
                </div>
                <div class="col-sm-7 text-black text-right">
                    <h3 class="display-4 d-sm-inline">Catchy phrase.</h3>
                    <br>
                    <h3 class="display-4 d-sm-inline">And a comment.</h3>
                    <p class="text-right mt-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </div>


        {{-- Features list  --}}
        <div class="container-fluid p-0 bg-dark pt-6 pb-4">
            <div class="row mb-7 no-gutters">
                <h1 class="text-white mx-auto">Features</h1>
            </div>
            <div class="row pt-3 pb-4 px-5 no-gutters">
                <div class="col-md-4">
                    <div class="card bg-dark text-white w-75 ml-auto border-0">
                        <img class="card-img-top mx-auto" style="width:50%;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Symbol_thumbs_up_white.svg/1200px-Symbol_thumbs_up_white.svg.png" alt="Card image cap">
                        <div class="card-body">
                            <h3 class="card-title text-center">Card title</h3>
                            <p class="card-text text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark text-white w-75 mx-auto border-0">
                        <img class="card-img-top mx-auto" style="width:50%;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Symbol_thumbs_up_white.svg/1200px-Symbol_thumbs_up_white.svg.png" alt="Card image cap">
                        <div class="card-body">
                            <h3 class="card-title text-center">Card title</h3>
                            <p class="card-text text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark text-white w-75 mr-auto border-0">
                        <img class="card-img-top mx-auto" style="width:50%;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Symbol_thumbs_up_white.svg/1200px-Symbol_thumbs_up_white.svg.png" alt="Card image cap">
                        <div class="card-body">
                            <h3 class="card-title text-center">Card title</h3>
                            <p class="card-text text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="row bg-primary"></div>
        </div>

        {{-- second CTA --}}
        <div class="container-fluid bg-primary p-0 bg-primary pb-4">
            <div class="row pl-6 pt-6 pb-4 no-gutters align-items-center">
                <div class="col-sm-6 text-white">
                    <h1 class="display-5 d-sm-inline">Would you like to learn something new?</h1>
                </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-secondary bg-grad py-1 px-3">
                        <h5 class="display-5 pt-2 text-primary"><b>Take me to the courses!</b></h5>
                    </button>
                </div>
            </div>
            <div class="row bg-primary mb-3"></div>
        </div>

        {{-- Pricing --}}
        @include('billing.partials.pricing', ['activePlan' => null, 'redirectTo' => route('register')])

@endsection
