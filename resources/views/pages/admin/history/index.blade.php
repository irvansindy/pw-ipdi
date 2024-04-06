@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'History Management'])
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="d-flex align-items-center">
                    <h6>Sejarah</h6>
                    <div class="button-action-for-history ms-auto">
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="content_history">
                
            </div>
        </div>
    </div>
    @include('pages.admin.history.create_history')
    @include('pages.admin.history.detail_history')
@endsection
@push('js')
    {{-- @include('pages.admin.visi_misi.visi_misi_js') --}}
    @include('pages.admin.history.history_js')
@endpush

