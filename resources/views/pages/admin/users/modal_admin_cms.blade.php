{{-- modal --}}
<div class="modal fade" id="modalAdminCMS" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="modalAdminCMS_label" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-dialog-scrollable" >
            <form action="" method="" id="form_user_for_admin">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdminCMS_label">Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" id="user_id">
                        <label for="example-text-input" class="form-control-label">Nama</label>
                        <Select class="form-control" name="user_for_admin" id="user_for_admin">
                            @php
                                $users = \App\Models\User::whereHas('roles', function ($query) {
                                    $query->where('name', 'user');
                                })->get();
                                foreach ($users as $user) {
                                    echo "<option value='{$user->id}'>{$user->username} - {$user->email}</option>";
                                }
                            @endphp
                        </Select>
                    </div>
                    <div class="form-group">
                        <label for="roles_admin_cms">Roles</label>
                        <select class="form-control" name="roles_admin_cms[]" id="roles_admin_cms" multiple style="width: 100%">
                            <option value="">Select One</option>
                            @php
                                $roles = \Spatie\Permission\Models\Role::all();
                            @endphp
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display: inherit !important;">
                    <div class="row">
                        <div class="col-sm">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn bg-gradient-primary" id="submit_admin_cms" >Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>