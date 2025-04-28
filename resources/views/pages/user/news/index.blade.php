@extends('layouts.users.app', ['title' => 'Berita'])

@section('content')
    @include('layouts.users.navbar')
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="d-flex align-items-center">
                    <h3>Berita</h3>
                    <div class="button-action-for-history ms-auto">
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="content_history">
                <div class="container py-4">
                    <div class="row g-4">
                        <!-- Berita utama -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-3">
                                <img src="{{ asset('img/carousel/WKD Maret 2024.png') }}" class="card-img-top rounded"
                                    alt="Berita Utama">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap mb-2 text-muted meta-info">
                                        <div class="me-3"><i class="bi bi-clock"></i>Author |</div>
                                        <div class="me-3"><i class="bi bi-eye"></i>Tanggal</div>
                                        {{-- <div class="me-3"><i class="bi bi-chat-dots"></i>5 Comment</div>
                                        <div><i class="bi bi-share"></i>1.5k Share</div> --}}
                                    </div>
                                    <h5 class="card-title">Title</h5>
                                    <p class="card-text">Description</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar berita kecil -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
                                <!-- Berita Utama -->
                                <img src="{{ asset('img/carousel/WKD Maret 2024.png') }}" class="img-fluid rounded mb-3"
                                    alt="Thumbnail">
                                <h6 class="fw-bold">Get the best speak market, news.</h6>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-clock me-1"></i> 06 minute read<br>
                                    <i class="bi bi-eye me-1"></i> 3.5k Views
                                </p>

                                <!-- List Berita Lainnya -->
                                <div class="mt-3">
                                    <div class="d-flex mb-3">
                                        <img src="{{ asset('img/carousel/WKD Maret 2024.png') }}" class="rounded me-2"
                                            style="width: 70px; height: 70px; object-fit: cover;" alt="...">
                                        <div>
                                            <p class="mb-1 fw-semibold small">Get the best speak market, news.</p>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-clock me-1"></i> 06 minute read<br>
                                                <i class="bi bi-eye me-1"></i> 3.5k Views
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Ulangi blok di atas untuk item berita berikutnya -->
                                    <div class="d-flex mb-3">
                                        <img src="{{ asset('img/carousel/WKD Maret 2024.png') }}" class="rounded me-2"
                                            style="width: 70px; height: 70px; object-fit: cover;" alt="...">
                                        <div>
                                            <p class="mb-1 fw-semibold small">Get the best speak market, news.</p>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-clock me-1"></i> 06 minute read<br>
                                                <i class="bi bi-eye me-1"></i> 3.5k Views
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Tambahkan item lainnya di sini -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Latest News Start -->
            <div class="container-fluid latest-news py-5">
                <div class="container py-5">
                    <h2 class="mb-4">Berita terkini</h2>
                    <div class="latest-news-carousel owl-carousel">
                        
                    </div>
                </div>
            </div>
            <!-- Latest News End -->
        </div>
    </div>
    @include('layouts.users.footer')
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/owlcarousel/assets/owl.carousel.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/owlcarousel/assets/owl.theme.default.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/animate/animate.css') }}">
    <style>
        .meta-info {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .meta-info i {
            margin-right: 5px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .news-thumbnail img {
            height: 60px;
            object-fit: cover;
        }

        /*** Latest News Start ***/
        .latest-news .latest-news-carousel.owl-carousel {
            position: relative;
        }

        .latest-news .latest-news-carousel.owl-carousel .owl-nav .owl-prev {
            position: absolute;
            top: -65px;
            right: 70px;
            font-size: 20px;
            padding: 2px 20px;
            transition: 0.5s;
            margin-right: 10px;
            border: 1px solid var(--bs-primary);
            /* color: var(--bs-dark); */
            border-radius: 20px;
        }

        .latest-news .latest-news-carousel.owl-carousel .owl-nav .owl-next {
            position: absolute;
            top: -65px;
            right: 0;
            font-size: 20px;
            margin-left: 10px;
            padding: 2px 20px;
            transition: 0.5s;
            border: 1px solid var(--bs-primary);
            /* color: var(--bs-dark); */
            border-radius: 20px;
        }

        .latest-news .latest-news-carousel.owl-carousel .owl-nav .owl-prev:hover,
        .latest-news .latest-news-carousel.owl-carousel .owl-nav .owl-next:hover {
            /* background: var(--bs-primary); */
            color: var(--bs-white);
            border-radius: 10px;
        }

        /*** Latest News End ***/
    </style>
    
@endpush
@push('js')
    <script src="{{ asset('assets/js/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/css/owlcarousel/owl.carousel.min.js') }}"></script>
    @include('pages.user.news.news_js')
@endpush
