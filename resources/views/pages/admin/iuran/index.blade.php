@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'Iuran Managements'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Iuran Managements'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>Iuran Management</h6>
                            <div class="d-flex align-items-center gap-2 ms-auto flex-wrap">
                                {{-- Filter Tahun --}}
                                <select class="form-select form-select-sm" id="filter_tahun_export" style="width: auto;">
                                    <option value="">Tahun</option>
                                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>

                                {{-- Filter Status --}}
                                <select class="form-select form-select-sm" id="filter_status_export" style="width: auto;">
                                    <option value="">Status</option>
                                    <option value="progress">Menunggu</option>
                                    <option value="verified">Terverifikasi</option>
                                    <option value="success">Disetujui</option>
                                </select>

                                {{-- Tombol Export --}}
                                <button type="button" class="btn bg-gradient-success btn-sm" id="btn_export_iuran" style="margin-bottom: 0px !important;">
                                    <i class="fas fa-file-excel me-1"></i> Export Excel
                                </button>

                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0 px-md-2">
                            <table class="table align-items-center mb-0 data_tables" id="iuran_management" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Nama
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Biaya
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Tahun
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
    @include('pages.admin.iuran.modal_aproval_iuran')
    @include('pages.admin.iuran.modal_edit_iuran')
@endsection
@push('js')
    @include('pages.admin.iuran.iuran_js')
@endpush
