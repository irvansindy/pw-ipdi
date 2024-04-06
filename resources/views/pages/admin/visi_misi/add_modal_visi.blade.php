{{-- modal --}}
<div class="modal fade" id="add_modal_visi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="add_modal_visiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_add_visi">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="add_modal_visiLabel">Add Visi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="form-group">
                            {{-- <input type="hidden" name="add_id" id="add_id"> --}}
                            <label for="add_visi">Visi</label>
                            <textarea class="form-control" name="add_visi" id="add_visi" rows="5" value=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="create_visi">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>