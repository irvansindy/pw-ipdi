{{-- modal --}}
<div class="modal fade" id="form_edit_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="form_edit_user_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-dialog-scrollable" >
            <form action="" method="" id="form_edit_user_data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="event_id" id="event_id">
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Nama</label>
                                <input class="form-control" type="text" name="user_id" id="user_id" value="">
                                <input class="form-control" type="text" name="username" id="username" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">NIK</label>
                                <input class="form-control" type="text" name="nik" id="nik" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">NIRA PPNI</label>
                                <input class="form-control" type="text" name="nira_ppni" id="nira_ppni" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">NIRA IPDI</label>
                                <input class="form-control" type="text" name="nira_ipdi" id="nira_ipdi" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">PW IPDI Provinsi</label>
                                <select class="form-control" name="pw_ipdi_provinsi" id="pw_ipdi_provinsi">
                                    @php
                                        DB::table('master_provinsi')->orderBy('name', 'asc')->get()->each(function($item) {
                                            echo "<option value='{$item->id}'>{$item->name}</option>";
                                        });
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Nama Perusahaan</label>
                                <input class="form-control" type="text" name="nama_perusahaan" id="nama_perusahaan" value="">
                            </div>
                        </div>
                        <div class="col-md-6" id="select_kabupaten">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">PW IPDI Kota</label>
                                <select class="form-control" name="pw_ipdi_kabupaten" id="pw_ipdi_kabupaten">
                                    @php
                                        DB::table('master_kabupaten')->orderBy('name', 'asc')->get()->each(function($item) {
                                            echo "<option value='{$item->id}'>{$item->name}</option>";
                                        });
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Center Dialisis</label>
                                <select class="form-control" name="center_dialisis" id="center_dialisis">
                                    @php
                                        DB::table('master_dialises')->orderBy('name', 'asc')->get()->each(function($item) {
                                            echo "<option value='{$item->id}'>{$item->name}</option>";
                                        });
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Alamat Center
                                    Dialisis/Perusahaan</label>
                                <input class="form-control" type="text" name="alamat_center_dialisis_perusahaan" id="alamat_center_dialisis_perusahaan" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">No. Hp</label>
                                <input class="form-control" type="text" name="number_phone" id="number_phone" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Pendidikan
                                    Terakhir</label>
                                <input class="form-control" type="text" name="last_edication" id="last_edication" value="">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer" style="display: inherit !important;">
                    <div class="row">
                        <div class="col-sm">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn bg-gradient-primary" id="update_user" data-event_id="" data-event_harga="">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>