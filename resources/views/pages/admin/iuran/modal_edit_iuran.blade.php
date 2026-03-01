{{-- Modal Edit Detail Iuran (Admin) --}}
<div class="modal fade" id="modal_edit_detail_iuran" tabindex="-1" role="dialog" aria-labelledby="modalAdminEditDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdminEditDetailLabel">Edit Detail Iuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_edit_detail_iuran">
                @csrf
                <input type="hidden" id="edit_detail_id" name="detail_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-control-label">Peserta</label>
                        <input type="text" class="form-control" id="edit_detail_nama_peserta" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label">Tahun Iuran <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_detail_tahun_iuran" name="tahun_iuran"
                            placeholder="Contoh: 2024" min="2000" max="2099">
                        <span class="field-error-text text-danger small mt-1 d-none" id="err_tahun_iuran"></span>
                    </div>

                    {{-- Biaya Registrasi Anggota Baru (20K) --}}
                    <div class="form-group mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-control-label mb-0">
                                Biaya Registrasi Anggota Baru
                                <span class="text-danger">*</span>
                            </label>
                            <div class="form-check form-switch mb-0 ms-3">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="check_biaya_20k" style="cursor: pointer;">
                                <label class="form-check-label small text-muted" for="check_biaya_20k">
                                    Isi otomatis <strong>20K</strong>
                                </label>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="edit_detail_biaya_20k"
                                name="biaya_regis_anggota_baru" placeholder="0" min="0">
                        </div>
                        <span class="field-error-text text-danger small mt-1 d-none" id="err_biaya_20k"></span>
                    </div>

                    {{-- Biaya Iuran Tahunan (50K) --}}
                    <div class="form-group mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-control-label mb-0">
                                Biaya Iuran Tahunan
                                <span class="text-danger">*</span>
                            </label>
                            <div class="form-check form-switch mb-0 ms-3">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="check_biaya_50k" style="cursor: pointer;">
                                <label class="form-check-label small text-muted" for="check_biaya_50k">
                                    Isi otomatis <strong>50K</strong>
                                </label>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="edit_detail_biaya_50k"
                                name="biaya_iuran_tahunan" placeholder="0" min="0">
                        </div>
                        <span class="field-error-text text-danger small mt-1 d-none" id="err_biaya_50k"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn bg-gradient-primary" id="btn_save_edit_detail_iuran">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>