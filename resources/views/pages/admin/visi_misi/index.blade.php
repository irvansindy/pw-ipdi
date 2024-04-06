@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Vision Management'])
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="d-flex align-items-center">
                    <h6>Visi</h6>
                    {{-- <button type="button" class="btn bg-gradient-primary ms-auto" data-bs-toggle="modal" data-bs-target="#formCreateRole">+ Role</button> --}}
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="content_visi">
                
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-2">
                <div class="d-flex align-items-center">
                    <h6>Misi</h6>
                    <button type="button" class="btn bg-gradient-primary ms-auto" id="add_visi" data-bs-toggle="modal" data-bs-target="#add_modal_misi">+ Misi</button>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="content_misi">
                <div class="list_misi">

                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.visi_misi.add_modal_visi')
    @include('pages.admin.visi_misi.add_modal_misi')
    @include('pages.admin.visi_misi.detail_modal_visi')
    @include('pages.admin.visi_misi.detail_modal_misi')
@endsection
@push('js')
    @include('pages.admin.visi_misi.visi_misi_js')
@endpush

