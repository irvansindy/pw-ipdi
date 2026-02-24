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
                    <form role="form" id="form_create_data_iuran" method="POST" action={{ route('profile.update') }}
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="align-items-center">
                                <div class="row">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <input type="number" min="0" class="form-control" name="jumlah_peserta_iuran"
                                        id="jumlah_peserta_iuran" placeholder="Jumlah Peserta Iuran">
                                </div>
                            </div>
                            <div class="array-list-peserta-iuran">
                                <p class="justify-content-center">Belum ada data</p>

                            </div>
                            <div class="d-flex ms-auto for-grand-total-payment-iuran">

                            </div>
                            <div class="align-items-center mt-4">
                                <div class="d-flex ms-auto" id="button_action_payment_iuran"></div>
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
