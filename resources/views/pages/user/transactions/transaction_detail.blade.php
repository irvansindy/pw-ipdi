{{-- modal --}}
<div class="modal fade" id="detailTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="detailTransaction_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form_upload_file">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="detail_transaction_id" id="detail_transaction_id">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Nomor Transaksi</span>
                            <p id="detail_id"></p>
                        </div>
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Pendaftar</span>
                            <p id="detail_username"></p>
                        </div>
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Event</span>
                            <p id="detail_event"></p>
                        </div>
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Tanggal Acara</span>
                            <p id="detail_date"></p>
                        </div>
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Status</span>
                            <p id="detail_status"></p>
                        </div>
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Total Harga</span>
                            <p id="detail_price"></p>
                        </div>
                        <div class="is_with_hotel"></div>
                        <div class="is_payment"></div>
                    </div>
                    <div class="table">
                        <table class="table table-striped">
                            <thead>
                                <th>No</th>
                                <th>Nama Peserta</th>
                            </thead>
                            <tbody id="list_data_pererta">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn bg-gradient-primary" id="submit_upload_transaction">Upload</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>
