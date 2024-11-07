{{-- modal --}}
<div class="modal fade" id="form_create_iuran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="form_create_iuran_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_create_data_iuran">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Daftar Iuran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="create_iuran_id" id="create_iuran_id">
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
                    <div class="form-group" id="">
                        <label for="tahun_iuran">Tahun Iuran</label>
                        <input type="text" class="form-control" name="tahun_iuran" id="tahun_iuran" placeholder="" value="">
                    </div>
                    <div class="form-group" id="form_data_peserta">
                        <label for="list_peserta">Peserta</label>
                        <select name="list_peserta[]" id="list_peserta" style="width:100%;">
                            
                        </select>
                        <input type="hidden" name="val_grand_total_payment_iuran" id="val_grand_total_payment_iuran">
                    </div>
                    <div class="input-group" id="grand_total_payment_iuran">
                        <p></p>
                    </div>
                </div>
                <div class="modal-footer" id="button_action_payment_iuran">
                    {{-- <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-gradient-primary" id="generate_payment_iuran" data-iuran_id="">Payment</button>
                    <button type="button" class="btn bg-gradient-primary" id="create_transaction_iuran" data-iuran_id="">Submit</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>
