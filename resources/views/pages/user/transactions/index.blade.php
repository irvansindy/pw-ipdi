@extends('layouts.users.app')

@section('content')
    @include('layouts.users.navbar')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>Riwayat Transaksi</h6>
                            <button type="button" class="btn bg-gradient-danger ms-auto" id="refresh_transaction">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 data_tables" id="transaction_list">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Transaction Number
                                        </th>
                                        <th
                                            class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Event
                                        </th>
                                        <th
                                            class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status
                                        </th>
                                        <th class="text-primary opacity-7"></th>
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
    @include('pages.user.transactions.upload')
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.transactions.transaction_js')
@endpush
