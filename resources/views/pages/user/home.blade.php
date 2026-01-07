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
                    <ul class="list-unstyled ms-md-5 mt-md-4" id="list_visi" style="text-align: justify !important;">

                    </ul>
                </div>
                <div class="col-md-12">
                    <h1 class="text-center">Misi</h1>
                    <ol type="1" id="list_misi" style="text-align: justify !important;">

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
                    <a href="{{ route('events') }}" class="btn btn-primary">View All</a>
                </div>
            </div>
            <div id="data-event-headline">
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
                    <a href="{{ route('news.index') }}" class="btn btn-secondary">View All</a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-8">
                    <div class="card card-plain card-blog">
                        <div class="card-image border-radius-lg position-relative">
                            <a href="javascript:;">
                                <div class="blur-shadow-image">
                                    <img class="img border-radius-lg move-on-hover img-fluid"
                                        src="{{ asset('img/carousel/2.png') }}" style="height: 30% !important;"
                                        loading="lazy">
                                </div>
                            </a>
                        </div>
                        <div class="card-body px-0">
                            <h5>
                                <a href="javascript:;" class="text-dark font-weight-bold">Pelantikan PW IPDI DKI Jakarta
                                    Periode 2023-2028</a>
                            </h5>
                            <p align="justify">
                                Pada tanggal 03 September 2023 acara Pembacaan dan Penandatanganan Naskah Pelantikan PW IPDI
                                DKI Jakarta Periode 2023-2028 di Hotel Ciputra Jakarta, dan dihadiri oleh Ketua DPW PPNI DKI
                                Jakarta (Jajang Rahmat Solihin), Ketua IPDI (Syamsul Bakhri), Wakil Ketua Organisasi dan
                                Kaderisasi (Yudi Hadiansyah), Ketua PW IPDI DKI (Agus Suwoko).
                            </p>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="row">
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid"
                                    src="{{ asset('img/carousel/4.png') }}" style="height: 10% !important;" loading="lazy">
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid"
                                    src="{{ asset('img/carousel/5.png') }}" style="height: 10% !important;" loading="lazy">
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
