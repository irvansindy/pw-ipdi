@extends('layouts.app')

@section('content')
    @include('layouts.users.navbar')
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('img/carousel/1.png') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/carousel/2.png') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/carousel/3.png') }}" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    {{-- new carousel --}}
    {{-- <section>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item">
                    <div class="page-header min-vh-80 m-3 border-radius-xl"
                        style="background-image: url('{{ asset('img/carousel/1.png') }}');">
                        <span class="mask bg-gradient-dark"></span>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 my-auto">
                                    <h4 class="text-white mb-0 fadeIn1 fadeInBottom">Pricing Plans</h4>
                                    <h1 class="text-white fadeIn2 fadeInBottom">Work with the rockets</h1>
                                    <p class="lead text-white opacity-8 fadeIn3 fadeInBottom">Wealth creation is an
                                        evolutionarily recent positive-sum game. Status is an old zero-sum game. Those
                                        attacking
                                        wealth creation are often just seeking status.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="page-header min-vh-80 m-3 border-radius-xl"
                        style="background-image: url('{{ asset('img/carousel/2.png') }}');">
                        <span class="mask bg-gradient-dark"></span>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 my-auto">
                                    <h4 class="text-white mb-0 fadeIn1 fadeInBottom">Our Team</h4>
                                    <h1 class="text-white fadeIn2 fadeInBottom">Work with the best</h1>
                                    <p class="lead text-white opacity-8 fadeIn3 fadeInBottom">Free people make free choices.
                                        Free choices mean you get unequal outcomes. You can have freedom, or you can have
                                        equal
                                        outcomes. You can’t have both.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item active">
                    <div class="page-header min-vh-80 m-3 border-radius-xl"
                        style="background-image: url('{{ asset('img/carousel/3.png') }}');">
                        <span class="mask bg-gradient-dark"></span>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 my-auto">
                                    <h4 class="text-white mb-0 fadeIn1 fadeInBottom">Office Places</h4>
                                    <h1 class="text-white fadeIn2 fadeInBottom">Work from home</h1>
                                    <p class="lead text-white opacity-8 fadeIn3 fadeInBottom">You’re spending time to save
                                        money when you should be spending money to save time.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="min-vh-80 position-absolute w-100 top-0">
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon position-absolute bottom-50" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon position-absolute bottom-50" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>
        </div>
    </section> --}}
    {{-- end new carousel --}}
    {{-- visi misi --}}
    <section>
        <div class="container mt-6">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="text-center">Visi</h1>
                    <ul class="list-unstyled ms-md-5 mt-md-4">
                        <li>Lorem ipsum dolor sit amet</li>
                        <li>Consectetur adipiscing elit</li>
                        <li>Integer molestie lorem at massa</li>
                        <li>Facilisis in pretium nisl aliquet</li>
                        <li>Nulla volutpat aliquam velit
                            <ul>
                                <li>Phasellus iaculis neque</li>
                                <li>Purus sodales ultricies</li>
                                <li>Vestibulum laoreet porttitor sem</li>
                                <li>Ac tristique libero volutpat at</li>
                            </ul>
                        </li>
                        <li>Faucibus porta lacus fringilla vel</li>
                        <li>Aenean sit amet erat nunc</li>
                        <li>Eget porttitor lorem</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h1 class="text-center">Misi</h1>
                    <ul class="list-unstyled ms-md-5 mt-md-4">
                        <li>Lorem ipsum dolor sit amet</li>
                        <li>Consectetur adipiscing elit</li>
                        <li>Integer molestie lorem at massa</li>
                        <li>Facilisis in pretium nisl aliquet</li>
                        <li>Nulla volutpat aliquam velit
                            <ul>
                                <li>Phasellus iaculis neque</li>
                                <li>Purus sodales ultricies</li>
                                <li>Vestibulum laoreet porttitor sem</li>
                                <li>Ac tristique libero volutpat at</li>
                            </ul>
                        </li>
                        <li>Faucibus porta lacus fringilla vel</li>
                        <li>Aenean sit amet erat nunc</li>
                        <li>Eget porttitor lorem</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    {{-- end visi misi --}}
    {{-- event --}}
    <section>
        <div class="container-fluid mt-6">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="ms-md-2 text-primary">
                        Event / Acara
                    </h3>
                </div>
                <div class="col-md-6 text-end">
                    <a href="" class="btn btn-primary">View All</a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-8">
                    <div class="card card-plain card-blog">
                        <div class="card-image border-radius-lg position-relative">
                            <a href="javascript:;">
                                <div class="blur-shadow-image">
                                    <img class="img border-radius-lg move-on-hover img-fluid"
                                        src="{{ asset('img/carousel/3.png') }}"
                                        style="height: 30% !important;">
                                </div>
                            </a>
                        </div>
                        <div class="card-body px-0">
                            <h5>
                                <a href="javascript:;" class="text-dark font-weight-bold">Rover raised $65 million for pet
                                    sitting</a>
                            </h5>
                            <p>
                                Finding temporary housing for your dog should be as easy as
                                renting an Airbnb. That’s the idea behind Rover ...
                            </p>
                            <a href="javascript:;" class="text-info icon-move-right">Read More
                                <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="row">
                        <a href="javascript:;">
                            <div class="card card-background move-on-hover mb-4">
                                <div class="full-background"
                                    style="background-image: url('{{ asset('img/carousel/3.png') }}')">
                                </div>
                                <div class="card-body pt-6">
                                    <h4 class="text-white">Search and Discovery</h4>
                                    <p>Website visitors today demand a frictionless user expericence — especially when using
                                        search. Because of the hight standards.</p>
                                </div>
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card card-background move-on-hover mb-4">
                                <div class="full-background"
                                    style="background-image: url('{{ asset('img/carousel/4.png') }}')">
                                </div>
                                <div class="card-body pt-6">
                                    <h4 class="text-white">Search and Discovery</h4>
                                    <p>Website visitors today demand a frictionless user expericence — especially when using
                                        search. Because of the hight standards.</p>
                                </div>
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card card-background move-on-hover mb-4">
                                <div class="full-background"
                                    style="background-image: url('{{ asset('img/carousel/5.png') }}')">
                                </div>
                                <div class="card-body pt-6">
                                    <h4 class="text-white">Search and Discovery</h4>
                                    <p>Website visitors today demand a frictionless user expericence — especially when using
                                        search. Because of the hight standards.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- end event --}}
    {{-- berita --}}
    <section>
        <div class="container-fluid mt-6">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="ms-md-2">
                        Berita
                    </h3>
                </div>
                <div class="col-md-6 text-end">
                    <a href="" class="btn btn-secondary">View All</a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-8">
                    <div class="card card-plain card-blog">
                        <div class="card-image border-radius-lg position-relative">
                            <a href="javascript:;">
                                <div class="blur-shadow-image">
                                    <img class="img border-radius-lg move-on-hover img-fluid"
                                        src="{{ asset('img/carousel/2.png') }}"
                                        style="height: 30% !important;">
                                </div>
                            </a>
                        </div>
                        <div class="card-body px-0">
                            <h5>
                                <a href="javascript:;" class="text-dark font-weight-bold">Rover raised $65 million for pet
                                    sitting</a>
                            </h5>
                            <p>
                                Finding temporary housing for your dog should be as easy as
                                renting an Airbnb. That’s the idea behind Rover ...
                            </p>
                            <a href="javascript:;" class="text-info icon-move-right">Read More
                                <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="row">
                        <a href="javascript:;">
                            <div class="card card-background move-on-hover mb-4">
                                <div class="full-background"
                                    style="background-image: url('{{ asset('img/carousel/1.png') }}')">
                                </div>
                                <div class="card-body pt-6">
                                    <h4 class="text-white">Search and Discovery</h4>
                                    <p>Website visitors today demand a frictionless user expericence — especially when using
                                        search. Because of the hight standards.</p>
                                </div>
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card card-background move-on-hover mb-4">
                                <div class="full-background"
                                    style="background-image: url('{{ asset('img/carousel/2.png') }}')">
                                </div>
                                <div class="card-body pt-6">
                                    <h4 class="text-white">Search and Discovery</h4>
                                    <p>Website visitors today demand a frictionless user expericence — especially when using
                                        search. Because of the hight standards.</p>
                                </div>
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card card-background move-on-hover mb-4">
                                <div class="full-background"
                                    style="background-image: url('{{ asset('img/carousel/3.png') }}')">
                                </div>
                                <div class="card-body pt-6">
                                    <h4 class="text-white">Search and Discovery</h4>
                                    <p>Website visitors today demand a frictionless user expericence — especially when using
                                        search. Because of the hight standards.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- end berita --}}
    @include('layouts.users.footer')
@endsection