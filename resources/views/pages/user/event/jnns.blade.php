@extends('layouts.app')

@section('content')
    @include('layouts.users.navbar')
    <section>
        <div class="container mt-5">
            <div class="form-group mb-5">
                <div class="row g-3 d-flex justify-content-end">
                    <div class="col-auto">
                        <input type="text" class="form-control" name="search" id="search" placeholder="Search...." value="">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-info" name="btn_search" id="btn_search">Search</button>
                    </div>
                </div>
            </div>
            <div class="row" id="list_jnns">
            </div>
            <div class="load-more justify-content-center"></div>
            {{-- <div id="pagination" class="mt-5">
                
            </div> --}}
        </div>
    </section>
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.event.jnns_js')
@endpush
