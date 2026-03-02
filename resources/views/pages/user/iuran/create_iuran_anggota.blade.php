@extends('layouts.users.app', ['title' => 'Pembayaran Iuran'])

@section('content')
    @include('layouts.users.navbar')
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form role="form" id="form_create_data_iuran" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="align-items-center">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-2">
                                        <p class="mb-0">Pembayaran Iuran</p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input data_iuran_type" type="radio" name="iuran_type"
                                                id="iuran_mandiri" value="mandiri">
                                            <label class="form-check-label" for="iuran_mandiri">Mandiri</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input data_iuran_type" type="radio" name="iuran_type"
                                                id="iuran_kolektif" value="kolektif">
                                            <label class="form-check-label" for="iuran_kolektif">Batch/Kolektif</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="file" class="form-control" name="iuran_file_upload"
                                            id="iuran_file_upload" placeholder="File bukti transfer">
                                        <span class="text-danger" style="font-size: 12px;">file bukti transfer</span>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" name="tanggal_iuran_transfer"
                                            id="tanggal_iuran_transfer">
                                        <span class="text-danger" style="font-size: 12px;">tanggal iuran transfer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            {{-- Area peserta — tersembunyi sampai tipe dipilih --}}
                            <div id="section_peserta" style="display: none;">

                                {{-- Tombol Tambah — hanya muncul untuk kolektif --}}
                                <div id="wrapper_btn_tambah" class="mb-3" style="display: none;">
                                    <button type="button" class="btn bg-gradient-primary btn-sm" id="btn_tambah_peserta">
                                        <i class="fas fa-plus me-1"></i> Tambah Peserta
                                    </button>
                                </div>

                                <div class="array-list-peserta-iuran"></div>
                            </div>

                            <div class="d-flex ms-auto for-grand-total-payment-iuran mt-3"></div>
                            <div class="align-items-center mt-2">
                                <div class="d-flex ms-auto" id="button_action_payment_iuran"></div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('pages.user.iuran.create_iuran_js')
@endpush