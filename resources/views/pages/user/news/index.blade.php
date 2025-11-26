@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="d-flex align-items-center">
                    <h3>Berita</h3>
                    <div class="button-action-for-history ms-auto"></div>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="container py-4">
                    <div class="row g-4">
                        <!-- Berita Utama -->
                        <div class="col-lg-8">
                            @if ($mainNews)
                                <div class="card border-0 shadow-sm mb-3">
                                    <img src="{{ asset($mainNews->image) }}" class="card-img-top rounded"
                                        alt="{{ $mainNews->title }}" style="height: 400px; object-fit: cover;">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap mb-2 text-muted meta-info">
                                            <div class="me-3">
                                                <i class="bi bi-person"></i>
                                                {{ $mainNews->author->username ?? 'Admin' }}
                                            </div>
                                            <div class="me-3">
                                                <i class="bi bi-calendar"></i>
                                                {{ $mainNews->published_at ? \Carbon\Carbon::parse($mainNews->published_at)->format('d M Y') : '-' }}

                                            </div>
                                            @if ($mainNews->event)
                                                <div class="me-3">
                                                    <i class="bi bi-tag"></i>
                                                    {{ $mainNews->event->name }}
                                                </div>
                                            @endif
                                        </div>
                                        <h5 class="card-title fw-bold">{{ $mainNews->title }}</h5>
                                        <p class="card-text">{{ Str::limit($mainNews->content, 200) }}</p>
                                        <a href="{{ route('news.show', $mainNews->id) }}" class="btn btn-primary btn-sm">
                                            Baca Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">Belum ada berita tersedia</div>
                            @endif
                        </div>

                        <!-- Sidebar Berita Kecil -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
                                @if ($sidebarNews->count() > 0)
                                    @foreach ($sidebarNews as $index => $news)
                                        @if ($index === 0)
                                            <!-- Berita Pertama (Besar) -->
                                            <a href="{{ route('news.show', $news->id) }}"
                                                class="text-decoration-none text-dark">
                                                <img src="{{ asset($news->image) }}" class="img-fluid rounded mb-3"
                                                    alt="{{ $news->title }}"
                                                    style="height: 200px; width: 100%; object-fit: cover;">
                                                <h6 class="fw-bold">{{ $news->title }}</h6>
                                                <p class="text-muted small mb-2">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d M Y') : '-' }}<br>
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $news->author->username ?? 'Admin' }}
                                                </p>
                                            </a>

                                            <!-- List Berita Lainnya -->
                                            <div class="mt-3">
                                            @else
                                                <a href="{{ route('news.show', $news->id) }}"
                                                    class="text-decoration-none text-dark">
                                                    <div class="d-flex mb-3">
                                                        <img src="{{ asset($news->image) }}" class="rounded me-2"
                                                            style="width: 70px; height: 70px; object-fit: cover;"
                                                            alt="{{ $news->title }}">
                                                        <div>
                                                            <p class="mb-1 fw-semibold small">
                                                                {{ Str::limit($news->title, 50) }}</p>
                                                            <p class="text-muted small mb-0">
                                                                <i class="bi bi-calendar me-1"></i>
                                                                {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d M Y') : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                        @endif
                                    @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada berita lainnya</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest News Start -->
        <div class="container-fluid latest-news py-5">
            <div class="container py-5">
                <h2 class="mb-4">Berita Terkini</h2>
                @if ($latestNews->count() > 0)
                    <div class="row g-4">
                        @foreach ($latestNews as $news)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset($news->image) }}" class="card-img-top" alt="{{ $news->title }}"
                                        style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold">{{ $news->title }}</h6>
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($news->content, 100) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i>
                                                {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d M Y') : '-' }}
                                            </small>
                                            <a href="{{ route('news.show', $news->id) }}" class="btn btn-sm btn-primary">
                                                Baca
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Belum ada berita terkini</div>
                @endif
            </div>
        </div>
        <!-- Latest News End -->
    </div>
    </div>

    @push('styles')
        <style>
            .card {
                transition: transform 0.2s;
            }

            .card:hover {
                transform: translateY(-5px);
            }
        </style>
    @endpush
@endsection
