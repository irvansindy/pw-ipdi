@extends('layouts.users.app', ['title' => 'Struktur Organisasi'])

@section('content')
    @include('layouts.users.navbar')
    <section>
        <div class="container-fluid py-4">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="d-flex align-items-center">
                        <h3>Struktur Organisasi</h3>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" id="content_visi">
                    {{-- <div class="embed-responsive embed-responsive-16by9">  --}}
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/UqU2ACpvDe4" allowfullscreen> 
                        </iframe> 
                    </div>
                </div>
            </div>

        </div>
    </section>
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.organization.organisasi_js')
@endpush
