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
                                <input class="form-control" type="text" name="username"
                                    value="{{ old('username', auth()->user()->username) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Email</label>
                                <input class="form-control" type="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">NIK</label>
                                <input class="form-control" type="text" name="nik"
                                    value="{{ old('nik', auth()->user()->nik) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">NIRA PPNI</label>
                                <input class="form-control" type="text" name="nira_ppni"
                                    value="{{ old('nira_ppni', auth()->user()->nira_ppni) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">NIRA IPDI</label>
                                <input class="form-control" type="text" name="nira_ipdi"
                                    value="{{ old('nira_ipdi', auth()->user()->nira_ipdi) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">PW IPDI Provinsi</label>
                                <input type="hidden" name="pw_ipdi_provinsi_current" id="pw_ipdi_provinsi_current" value="{{ auth()->user()->pw_ipdi_provinsi }}">
                                <select class="form-control" name="pw_ipdi_provinsi" id="pw_ipdi_provinsi">
                                    {{-- @forelse ($masterProvinsi as $provinsi)
                                        <option value="{{ $provinsi->id }}" {{ $provinsi->id == auth()->user()->pw_ipdi_provinsi ? 'selected' : '' }}>{{ $provinsi->name }}</option>
                                    @empty
                                        <option value="hidden">Select One</option>
                                    @endforelse --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Nama Perusahaan</label>
                                <input class="form-control" type="text" name="nama_perusahaan"
                                    value="{{ old('nama_perusahaan', auth()->user()->nama_perusahaan) }}">
                            </div>
                        </div>
                        <div class="col-md-6" id="select_kabupaten">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">PW IPDI Kota</label>
                                <select class="form-control" name="pw_ipdi_kabupaten" id="pw_ipdi_kabupaten">
                                    {{-- @if (auth()->user()->pw_ipdi_kabupaten == null)
                                        <option value="hidden">Select One</option>
                                        
                                    @endif
                                    @forelse ($masterKabupaten as $kabupaten)
                                        <option value="{{ $kabupaten->id }}" {{ $kabupaten->id == auth()->user()->pw_ipdi_kabupaten ? 'selected' : '' }}>{{ $kabupaten->name }}</option>
                                    @empty
                                        <option value="hidden">Select One</option>
                                    @endforelse --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Center Dialisis</label>
                                <input class="form-control" type="text" name="center_dialisis"
                                    value="{{ old('center_dialisis', auth()->user()->center_dialisis) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Alamat Center
                                    Dialisis/Perusahaan</label>
                                <input class="form-control" type="text"
                                    name="alamat_center_dialisis_perusahaan"
                                    value="{{ old('alamat_center_dialisis_perusahaan', auth()->user()->alamat_center_dialisis_perusahaan) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">No. Hp</label>
                                <input class="form-control" type="text" name="number_phone"
                                    value="{{ old('number_phone', auth()->user()->number_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Pendidikan
                                    Terakhir</label>
                                <input class="form-control" type="text" name="last_edication"
                                    value="{{ old('last_edication', auth()->user()->last_edication) }}">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer" style="display: inherit !important;">
                    <div class="row">
                        <div class="col-sm">
                            {{-- <h5>
                                Total : <span id="total_harga_event"></span>
                            </h5> --}}
                        </div>
                        <div class="col-sm">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn bg-gradient-primary" id="create_transaction_event" data-event_id="" data-event_harga="">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>