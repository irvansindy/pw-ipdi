{{-- modal --}}
<div class="modal fade" id="formCreateIuran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="formCreateIuranLabel" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_create_iuran">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="IuranLabel">Create New Iuran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="create_iuran_name">Nama</label>
                            <input type="text" class="form-control" name="create_iuran_name" id="create_iuran_name" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="create_iuran_desc">Deskripsi</label>
                            <input type="text" class="form-control" name="create_iuran_desc" id="create_iuran_desc" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="create_iuran_price">Biaya</label>
                            <input type="number" class="form-control" name="create_iuran_price" id="create_iuran_price" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="create_iuran_year">Tahun</label>
                            <input type="number" class="form-control" name="create_iuran_year" id="create_iuran_year" placeholder="">
                        </div>
                        {{-- <div class="form-group">
                            <label for="create_permission_role">Permission</label>
                            <select class="form-control" name="create_permission_role" id="create_permission_role" disabled="disabled"></select>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="create_data_iuran">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>