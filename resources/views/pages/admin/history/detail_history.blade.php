{{-- modal --}}
<div class="modal fade" id="detail_modal_history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="detail_modal_historyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_detail_history">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="detail_modal_historyLabel">Detail History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <input type="hidden" name="detail_id" id="detail_id">
                            <label for="detail_history">History/Sejarah</label>
                            <textarea name="detail_history" id="detail_history"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="update_history">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>