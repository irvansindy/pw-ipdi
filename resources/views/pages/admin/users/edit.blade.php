{{-- modal --}}
<div class="modal fade" id="form_edit_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="form_edit_user_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-dialog-scrollable" >
            <form action="" method="" id="form_edit_user_data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="event_id" id="event_id">
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input data_event_type" type="radio" name="event_type"
                            id="event_mandiri" value="mandiri">
                        <label class="form-check-label" for="event_mandiri">Mandiri</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input data_event_type" type="radio" name="event_type"
                            id="event_kolektif" value="kolektif">
                        <label class="form-check-label" for="event_kolektif">Batch/Kolektif</label>
                    </div>
                    <div class="form-group" id="form_data_peserta">
                        <label for="list_peserta">Peserta</label>
                        <select name="list_peserta[]" id="list_peserta" style="width:100%;">
                            
                        </select>
                    </div>
                    <div class="row data_event">
                        <div class="col-md-auto">
                            <p>Nama : <span id="name_event"></span></p>
                            <p>Judul : <span id="title_event"></span></p>
                            <p class="price_event">Harga : <span id="price_event" data-price_data=""></span></p>
                            <div class="list_type_jnns">

                            </div>
                            <div class="event_hotel">

                            </div>
                            <p>Kuota : <span id="quota_event"></span></p>
                            <p>Tanggal : <span id="date_event"></span></p>
                            <p>Terakhir Registrasi : <span id="last_register"></span></p>
                            {{-- <p>Description : <span id="desc_event"></span></p> --}}
                            <p>Rekening : <span id="bank_account_number"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: inherit !important;">
                    <div class="row">
                        <div class="col-sm">
                            {{-- <h5>
                                Total : <span id="total_harga_event"></span>
                            </h5> --}}
                        </div>
                        <div class="col-sm">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn bg-gradient-primary" id="create_transaction_event" data-event_id="" data-event_harga="">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>