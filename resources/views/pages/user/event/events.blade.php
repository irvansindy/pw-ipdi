@extends('layouts.app')

@section('content')
    @include('layouts.users.navbar')
    <section>
        <div class="container mt-5">
            <div class="form-group mb-5">
                <div class="row g-3 d-flex justify-content-end">
                    <div class="col-auto">
                        <input type="text" class="form-control" name="search_events" id="search_events" placeholder="Search...." value="">
                    </div>
                    <div class="col-auto">
                        {{-- <input type="text" class="form-control" name="search_events" id="search_events" placeholder="Search...." value=""> --}}
                        <select class="form-control" name="filter_events" id="filter_events">
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-info" name="btn_search_events" id="btn_search_events">Search</button>
                    </div>
                </div>
            </div>
            <div class="row" id="list_events">
            </div>
            <div class="load-more justify-content-center"></div>
            {{-- <div id="pagination" class="mt-5">
                
            </div> --}}
        </div>
    </section>
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.event.events_js')
@endpush
