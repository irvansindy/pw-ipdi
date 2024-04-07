@extends('layouts.users.app', ['title' => 'Sejarah'])

@section('content')
    @include('layouts.users.navbar')
    <div class="container-fluid py-4">
        <div class="card mb-4">
            <div class="card-header pb-2">
                <div class="d-flex align-items-center">
                    <h3>Sejarah</h3>
                    <div class="button-action-for-history ms-auto">
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" id="content_history">
                
            </div>
        </div>
    </div>
    @include('layouts.users.footer')
@endsection
@push('js')
@include('pages.user.history.history_js')
@endpush
