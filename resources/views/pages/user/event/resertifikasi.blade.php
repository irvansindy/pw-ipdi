@extends('layouts.app')

@section('content')
    @include('layouts.users.navbar')
    <section>
        <div class="container mt-5">
            <div class="form-group mb-5">
                <div class="row g-3 d-flex justify-content-end">
                    <div class="col-auto">
                        <input type="text" class="form-control" name="search_resertifikasi" id="search_resertifikasi" placeholder="Search...." value="">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-info" name="btn_search_resertifikasi" id="btn_search_resertifikasi">Search</button>
                    </div>
                </div>
            </div>
            <div class="row" id="list_resertifikasi">
            </div>
            <div class="load-more justify-content-center"></div>
        </div>
    </section>
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.event.resertifikasi_js')
@endpush
