{{-- @extends('layouts.app') --}}
@extends('layouts.users.app', ['title' => 'PW IPDI DKI JAKARTA'])

@section('content')
    @include('layouts.users.navbar')
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('img/carousel/1.png') }}" class="d-block w-100" loading="lazy">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/carousel/2.png') }}" class="d-block w-100" loading="lazy">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/carousel/3.png') }}" class="d-block w-100" loading="lazy">
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
    {{-- end new carousel --}}
    {{-- visi misi --}}
    <section>
        <div class="container mt-6">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">Visi</h1>
                    <ul class="list-unstyled ms-md-5 mt-md-4" id="list_visi">
                        
                    </ul>
                </div>
                <div class="col-md-12">
                    <h1 class="text-center">Misi</h1>
                    <ol type="1" id="list_misi">
                        
                    </ol>
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
                                        style="height: 30% !important;" loading="lazy">
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
                                        style="height: 30% !important;" loading="lazy">
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
@push('js')
    @include('pages.user.home_js')
@endpush