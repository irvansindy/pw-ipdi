@extends('layouts.users.app', ['title' => 'Visi & Misi'])

@section('content')
    @include('layouts.users.navbar')
    <section>
        <div class="container-fluid py-4">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="d-flex align-items-center">
                        <h3>Visi</h3>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" id="content_visi">

                </div>
            </div>
            <div class="card">
                <div class="card-header pb-2">
                    <div class="d-flex align-items-center">
                        <h3>Misi</h3>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" id="content_misi">
                    <div class="row list_misi">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.visi_misi.visi_misi_js')
@endpush
