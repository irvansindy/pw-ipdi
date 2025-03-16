@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'Transactions'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Transaction Management'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>Transaction Management</h6>
                        </div>
                    </div>
                    
                    <div class="card-body px-0 pt-0 pb-2">
                        <ul class="nav nav-tabs px-md-2 px-sm-2" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="jnns-tab" data-bs-toggle="tab" data-bs-target="#jnns" type="button" role="tab">JNNS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="resertifikasi-tab" data-bs-toggle="tab" data-bs-target="#resertifikasi" type="button" role="tab">Re-Sertifikasi</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seminar-tab" data-bs-toggle="tab" data-bs-target="#seminar" type="button" role="tab">Seminar</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="wkd-tab" data-bs-toggle="tab" data-bs-target="#wkd" type="button" role="tab">WKD</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kongres_wilayah-tab" data-bs-toggle="tab" data-bs-target="#kongres_wilayah" type="button" role="tab">Kongres Wilayah</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="myTabContent">
                            @foreach(['jnns', 'resertifikasi', 'seminar', 'wkd', 'kongres_wilayah'] as $tab)
                            <div class="tab-pane fade p-0 px-md-2 px-sm-2 @if($loop->first) show active @endif" id="{{ $tab }}" role="tabpanel">
                                <div class="d-flex justify-content-end align-items-center gap-2 mb-3 px-3 flex-wrap">
                                    <select class="form-select year-filter w-auto" id="year_filter_{{ $tab }}">
                                        @for($year = now()->year; $year >= now()->year - 10; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                    <button type="button" class="btn bg-gradient-danger mt-3 mt-sm-3 mt-md-3 refresh-data" data-table="{{ $tab }}">
                                        <i class="fas fa-sync"></i> Refresh
                                    </button>
                                    <button type="button" class="btn bg-gradient-success mt-sm-3 mt-md-3 export-excel" data-table="{{ $tab }}">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </div>
                                <div class="table-responsive p-0 px-md-2 px-sm-2">
                                    <table class="table align-items-center mb-0 data_tables" id="transaction_{{ $tab }}_management" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Transaction Number</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Event</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.admin.transactions.update')
    @include('pages.admin.transactions.delete')
    @include('pages.admin.transactions.detail_attendance')
@endsection
@push('css')
    {{-- <style>
        @media (max-width: 768px) {
            .d-flex.align-items-center.gap-2 {
                flex-wrap: wrap;
            }

            .year-filter {
                width: 100%;
            }
        }

    </style> --}}
@endpush
@push('js')
    @include('pages.admin.transactions.transaction_js')
@endpush
