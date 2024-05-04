{{-- modal --}}
<div class="modal fade" id="form_detail_iuran" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="form_detail_iuran_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_update_data_iuran">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Iuran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="nomor_iuran"></p>
                    <p id="tahun_iuran"></p>
                    <table class="table table-hover table-bordered" style="width: 100% !important">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peserta</th>
                            </tr>
                        </thead>
                        <tbody id="list_detail_iuran_peserta">
                            
                        </tbody>
                    </table>
                    <div class="form-group">
                        <input class="form-control" type="file" name="upload_iuran" id="upload_iuran">
                        <p class="text-danger" style="font-size: 12px">file harus berjenis: jpeg/jpg/png, max size: 128kb</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-gradient-primary" id="upload_file_iuran" data-iuran_id="">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
