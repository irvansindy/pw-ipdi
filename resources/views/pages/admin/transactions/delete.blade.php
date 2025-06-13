{{-- modal --}}
<div class="modal fade" id="form_delete_transaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="form_delete_transaction_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-dialog-scrollable" >
            <form action="" method="POST" id="form_delete_transaction_data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Data Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 id="delete_transaction_number"></h3>
                </div>
                <div class="modal-footer" style="display: inherit !important;">
                    <div class="row">
                        <div class="col-sm">
                            <input type="hidden" name="delete_transaction_id" id="delete_transaction_id">
                        </div>
                        <div class="col-sm ms-end">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn bg-gradient-primary" id="confirm_delete_transaction" data-transaction_id="" data-transaction_number="">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>