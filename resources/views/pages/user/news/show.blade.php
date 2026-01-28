@extends('layouts.users.app', ['title' => $news->title])

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Berita</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($news->title, 50) }}</li>
                    </ol>
                </nav>

                <!-- News Content -->
                <div class="card border-0 shadow-sm">
                    <!-- Image Carousel -->
                    {{-- @dd($news->images) --}}
                    @if ($news->images->count() > 0)
                        <div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach ($news->images as $index => $image)
                                    <button type="button"
                                            data-bs-target="#newsCarousel"
                                            data-bs-slide-to="{{ $index }}"
                                            class="{{ $index == 0 ? 'active' : '' }}"
                                            aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}">
                                    </button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($news->images as $index => $image)
                                {{-- @dd($image['file_path']) --}}
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset($image['file_path']) }}"
                                             class="d-block w-100"
                                             alt="{{ $image['caption'] ?? $news->title }}"
                                             style="max-height: 500px; object-fit: cover;">
                                        @if ($image['caption'])
                                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded">
                                                <p class="mb-0">{{ $image['caption'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if ($news->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    @elseif ($news->image)
                        <!-- Fallback ke gambar utama jika tidak ada images -->
                        <img src="{{ asset($news->image) }}"
                             class="card-img-top"
                             alt="{{ $news->title }}"
                             style="max-height: 500px; object-fit: cover;">
                    @else
                        <!-- Default image jika tidak ada gambar sama sekali -->
                        <img src="{{ asset('images/default-news.jpg') }}"
                             class="card-img-top"
                             alt="{{ $news->title }}"
                             style="max-height: 500px; object-fit: cover;">
                    @endif

                    <div class="card-body p-4">
                        <!-- Title -->
                        <h1 class="mb-3">{{ $news->title }}</h1>

                        <!-- Meta Information -->
                        <div class="d-flex flex-wrap gap-3 mb-4 pb-3 border-bottom text-muted">
                            <div>
                                <i class="bi bi-person-circle"></i>
                                <strong>Penulis:</strong> {{ $news->author->username ?? 'Admin' }}
                            </div>
                            <div>
                                <i class="bi bi-calendar-event"></i>
                                <strong>Tanggal:</strong>
                                {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d F Y') : '-' }}
                            </div>
                            @if ($news->event)
                                <div>
                                    <i class="bi bi-tag-fill"></i>
                                    <strong>Event:</strong> {{ $news->event->name }}
                                </div>
                            @endif
                            @if ($news->source_url)
                                <div>
                                    <i class="bi bi-link-45deg"></i>
                                    <a href="{{ $news->source_url }}" target="_blank" rel="noopener">Sumber</a>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="news-content">
                            {!! nl2br(e($news->content)) !!}
                        </div>

                        <!-- Back Button -->
                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('news.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Berita
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related News -->
                @if (isset($relatedNews) && $relatedNews->count() > 0)
                    <div class="mt-5">
                        <h3 class="mb-4">Berita Terkait</h3>
                        <div class="row g-4">
                            @foreach ($relatedNews as $related)
                                @php
                                    $relatedImage = $related->images->first()
                                        ? asset($related->images->first()->image_path)
                                        : ($related->image ? asset($related->image) : asset('images/default-news.jpg'));
                                @endphp
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <img src="{{ $relatedImage }}"
                                             class="card-img-top"
                                             alt="{{ $related->title }}"
                                             style="height: 180px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title fw-bold">
                                                {{ Str::limit($related->title, 60) }}
                                            </h6>
                                            <p class="card-text small text-muted">
                                                {{ Str::limit(strip_tags($related->content), 80) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i>
                                                    {{ $related->published_at ? \Carbon\Carbon::parse($related->published_at)->format('d M Y') : '-' }}
                                                </small>
                                                <a href="{{ route('news.show', $related->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    Baca
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .news-content {
                font-size: 1.1rem;
                line-height: 1.8;
                text-align: justify;
            }

            .carousel-indicators button {
                width: 10px;
                height: 10px;
                border-radius: 50%;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 5%;
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                background-color: rgba(0, 0, 0, 0.5);
                border-radius: 50%;
                padding: 20px;
            }

            .card {
                transition: transform 0.2s;
            }

            .card:hover {
                transform: translateY(-5px);
            }

            @media (max-width: 768px) {
                .news-content {
                    font-size: 1rem;
                }
            }
        </style>
    @endpush
@endsection