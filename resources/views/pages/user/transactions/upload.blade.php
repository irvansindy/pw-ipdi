{{-- modal --}}
<div class="modal fade" id="uploadTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="uploadTransaction_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form_upload_file">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload bukti transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="file_transaction_id" id="file_transaction_id">
                        <label for="">Bukti Transaksi</label>
                        <input type="file" name="file_upload_transaction" id="file_upload_transaction">
                        <p class="text-danger" style="font-size: 12px">file harus berjenis: jpeg/jpg/jpg, max size: 128kb</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-gradient-primary" id="submit_upload_transaction">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>