@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'News Management'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'News Management'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>News Management</h6>
                            <button type="button" class="btn bg-gradient-primary ms-auto" id="for_create_news" data-bs-toggle="modal" data-bs-target="#formNews">+ News</button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table align-items-center mb-0 data_tables" id="news_management" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Title 
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Published Date
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.news.news_modal')
@endsection
@push('css')
    <style>
        .note-toolbar, .card-header {
            border-radius: 12px 12px 0 0;;
        };

        .note-resizebar {
            border-radius: 0 0 12px 12px !important;
        };
    </style>
@endpush
@push('js')
    @include('pages.admin.news.news_js')
@endpush