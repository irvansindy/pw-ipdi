{{-- modal --}}
<div class="modal fade" id="uploadIuranTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="uploadIuranTransaction_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_upload_bukti_iuran">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadIuranTransaction_label">Upload Bukti Pembayaran Iuran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="master_iuran_id" id="master_iuran_id">
                    <div class="form-group" id="form_data_peserta">
                        <label for="file_iuran_data">Upload bukti pembayaran</label>
                        <input type="file" name="file_iuran_data" id="file_iuran_data" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-gradient-primary" id="upload_file_bukti_iuran" data-iuran_id="">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
