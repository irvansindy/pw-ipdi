@extends('layouts.users.app', ['title' => 'Iuran Peserta'])

@section('content')
    @include('layouts.users.navbar')
    <section>
        <div class="container-fluid py-4">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="d-flex align-items-center">
                        <h3>Iuran</h3>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" id="content_visi">
                    <div class="nav-wrapper position-relative end-0">
                        {{-- <ul class="nav nav-pills nav-fill p-1" role="tablist"> --}}
                        <ul class="nav nav-tabs nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#list_iuran"
                                    role="tab" aria-controls="preview" aria-selected="true">
                                    <i class="ni ni-single-copy-04 text-sm me-2"></i> List Iuran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#iuran_transaksi"
                                    role="tab" aria-controls="code" aria-selected="true">
                                    <i class="ni ni-money-coins text-sm me-2"></i> Transaksi
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2">
                            <div role="tabpanel" class="tab-pane fade active show" id="list_iuran">
                                <div class="row" id="list_fetch_data">
                                    
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="iuran_transaksi">
                                <table class="table table-hover" id="table_list_iuran" style="width: 100% !important">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Iuran</th>
                                            <th>Tahun</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('pages.user.iuran.checkout')
    @include('pages.user.iuran.detail')
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.iuran.iuran_js')
@endpush
