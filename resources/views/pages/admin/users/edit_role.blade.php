<div class="modal fade" id="form_role_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="form_role_user_label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-dialog-scrollable">
            <form action="" method="" id="form_role_user_data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="form_role_user_label">Edit Role User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="role_user_id" id="role_user_id">
                    <div class="form-group">
                        <label class="form-control-label">Nama</label>
                        <input class="form-control" type="text" id="role_username" disabled>
                    </div>
                    <div class="form-group">
                        <label for="user_roles">Role</label>
                        <select class="form-control" name="roles[]" id="user_roles" multiple style="width: 100%">
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
                            <button type="button" class="btn bg-gradient-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn bg-gradient-primary" id="submit_user_role">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
