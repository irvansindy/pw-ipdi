@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Berita</a></li>
                        <li class="breadcrumb-item active">{{ $news->title }}</li>
                    </ol>
                </nav>

                <!-- News Content -->
                <div class="card border-0 shadow-sm">
                    <img src="{{ asset($news->image) }}" class="card-img-top" alt="{{ $news->title }}"
                        style="max-height: 500px; object-fit: cover;">

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
                                    <a href="{{ $news->source_url }}" target="_blank">Sumber</a>
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
        </style>
    @endpush
@endsection
