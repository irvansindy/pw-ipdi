@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'Organization Structure'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Organization Structure'])
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="d-flex align-items-center">
                    <h6>Struktur Organisasi</h6>
                    <div class="button-action-for-organization ms-auto">
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2 align-self-center" id="content_organization">
                
            </div>
        </div>
    </div>
    @include('pages.admin.organization.create')
@endsection
@push('js')
    @include('pages.admin.organization.organization_js')
@endpush

