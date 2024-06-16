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
                                <a href="javascript:;" class="text-dark font-weight-bold">
                                    JNNS XVI
                                </a>
                            </h5>
                            <p>
                                Jakarta Nephrology Nursing Symposium (JNNS) XVI 02 – 03 September 2023 merupakan Program PITWIL IPDI DKI Jakarta setiap Tahunnnya, dalam rangka update Ilmu dan Tekhnologi di bidang Dialisis guna meningkatkan Pelayanan Dialisis yang Profesional.
                            </p>
                            {{-- <a href="javascript:;" class="text-info icon-move-right">Read More
                                <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                            </a> --}}
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="row">
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/carousel/4.png') }}" style="height: 10% !important;" loading="lazy">
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/carousel/5.png') }}" style="height: 10% !important;" loading="lazy">
                            </div>
                        </a>
                        
                        {{-- <a href="javascript:;">
                            <div class="card card-background move-on-hover mb-4">
                                <div class="full-background"
                                    style="background-image: url('{{ asset('img/carousel/5.png') }}')">
                                </div>
                                <div class="card-body pt-6">
                                    <h4 class="text-white">JNNS XVI</h4>
                                    <p>Website visitors today demand a frictionless user expericence — especially when using
                                        search. Because of the hight standards.</p>
                                </div>
                            </div>
                        </a> --}}
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-8">
                    <div class="card card-plain card-blog">
                        <div class="card-image border-radius-lg position-relative">
                            <a href="javascript:;">
                                <div class="blur-shadow-image">
                                    <img class="img border-radius-lg move-on-hover img-fluid"
                                        src="{{ asset('img/carousel/WKD Maret 2024.png') }}"
                                        style="height: 30% !important;" loading="lazy">
                                </div>
                            </a>
                        </div>
                        <div class="card-body px-0">
                            <h5>
                                <a href="javascript:;" class="text-dark font-weight-bold">
                                    WKD 2024
                                </a>
                            </h5>
                            <p>
                                Pada setiap tahun, World Kidney Day diperingati untuk meningkatkan kesadaran tentang pentingnya kesehatan ginjal dan mengurangi dampak penyakit ginjal di seluruh dunia. Hari ini berfokus pada mendorong pencegahan, deteksi dini, dan pengobatan penyakit ginjal. Dengan mengangkat tema berbeda setiap tahunnya, World Kidney Day menyoroti isu-isu yang berkaitan dengan kesehatan ginjal, termasuk pentingnya gaya hidup sehat, peran penting ginjal dalam tubuh, dan bagaimana mengurangi faktor risiko seperti hipertensi dan diabetes. Mari bersama-sama menjaga kesehatan ginjal kita demi kualitas hidup yang lebih baik.
                            </p>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="row">
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/carousel/WKD Maret 2024 (2).png') }}" style="height: 10% !important;" loading="lazy">
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/carousel/WKD Maret 2024 (3).png') }}" style="height: 10% !important;" loading="lazy">
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
                                <a href="javascript:;" class="text-dark font-weight-bold">Pelantikan PW IPDI DKI Jakarta Periode 2023-2028</a>
                            </h5>
                            <p>
                                Pada tanggal 03 September 2023 acara Pembacaan dan Penandatanganan Naskah Pelantikan PW IPDI DKI Jakarta Periode 2023-2028 di Hotel Ciputra Jakarta, dan dihadiri oleh Ketua DPW PPNI DKI Jakarta (Jajang Rahmat Solihin), Ketua IPDI (Syamsul Bakhri), Wakil Ketua Organisasi dan Kaderisasi (Yudi Hadiansyah), Ketua PW IPDI DKI (Agus Suwoko).
                            </p>
                            {{-- <a href="javascript:;" class="text-info icon-move-right">Read More
                                <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                            </a> --}}
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="row">
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/carousel/4.png') }}" style="height: 10% !important;" loading="lazy">
                            </div>
                        </a>
                        <a href="javascript:;">
                            <div class="card move-on-hover mb-4">
                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/carousel/5.png') }}" style="height: 10% !important;" loading="lazy">
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