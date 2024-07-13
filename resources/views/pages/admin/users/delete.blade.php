{{-- modal --}}
<div class="modal fade" id="form_delete_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="form_delete_user_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-dialog-scrollable" >
            <form action="" method="POST" id="form_create_data_event">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 id="delete_username"></h3>
                </div>
                <div class="modal-footer" style="display: inherit !important;">
                    <div class="row">
                        <div class="col-sm">
                            <input type="hidden" name="delete_user_id" id="delete_user_id">
                        </div>
                        <div class="col-sm ms-end">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn bg-gradient-primary" id="confirm_delete_user" data-user_id="" data-event_harga="">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>