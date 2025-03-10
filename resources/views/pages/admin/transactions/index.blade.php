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
                            <div class="ms-auto mx-0">
                                <button type="button" class="btn bg-gradient-danger ms-auto" id="refresh_data">
                                    <i class="fas fa-sync"></i>
                                </button>
                                <a href="{{ route('transaction-export-excel') }}" class="btn bg-gradient-success ms-auto"
                                    id="transaction_export_excel">
                                    <i class="fas fa-file-excel"></i>
                                </a>
                            </div>
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
                            <div class="tab-pane fade p-0 px-md-2 px-sm-2 show active" id="jnns" role="tabpanel">
                                <div class="table-responsive p-0 px-md-2 px-sm-2">
                                    <table class="table align-items-center mb-0 data_tables" id="transaction_jnns_management" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    No
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Transaction Number
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Event
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    User
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Status
                                                </th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade p-0 px-md-2 px-sm-2" id="resertifikasi" role="tabpanel">
                                <div class="table-responsive p-0 px-md-2 px-sm-2">
                                    <table class="table align-items-center mb-0 data_tables" id="transaction_resertifikasi_management" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    No
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Transaction Number
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Event
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    User
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Status
                                                </th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade p-0 px-md-2 px-sm-2" id="seminar" role="tabpanel">
                                <div class="table-responsive p-0 px-md-2 px-sm-2">
                                    <table class="table align-items-center mb-0 data_tables" id="transaction_seminar_management" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    No
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Transaction Number
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Event
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    User
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Status
                                                </th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade p-0 px-md-2 px-sm-2" id="wkd" role="tabpanel">
                                <div class="table-responsive p-0 px-md-2 px-sm-2">
                                    <table class="table align-items-center mb-0 data_tables" id="transaction_wkd_management" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    No
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Transaction Number
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Event
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    User
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Status
                                                </th>
                                                <th class="text-secondary opacity-7"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade p-0 px-md-2 px-sm-2" id="kongres_wilayah" role="tabpanel">
                                <div class="table-responsive p-0 px-md-2 px-sm-2">
                                    <table class="table align-items-center mb-0 data_tables" id="transaction_kongres_wilayah_management" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    No
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Transaction Number
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Event
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    User
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                    Status
                                                </th>
                                                <th class="text-secondary opacity-7"></th>
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
        </div>
    </div>
    @include('pages.admin.transactions.update')
    @include('pages.admin.transactions.delete')
    @include('pages.admin.transactions.detail_attendance')
@endsection
@push('js')
    @include('pages.admin.transactions.transaction_js')
@endpush
