<!-- Modal -->
<div class="modal fade" id="formMenus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="formMenus_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formMenusLabel">Create Menu</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="data_form_menu">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_name">Name</label>
                        <input type="hidden" class="form-control" name="menu_id" id="menu_id" placeholder="Name">
                        <input type="text" class="form-control" name="menu_name" id="menu_name" placeholder="Name">
                        <span class="text-danger" id="message_menu_name"></span>
                    </div>
                    <div class="form-group">
                        <label for="menu_url">Url</label>
                        <input type="text" class="form-control" name="menu_url" id="menu_url" placeholder="Url">
                        <span class="text-danger" id="message_menu_url"></span>
                    </div>
                    <div class="form-group">
                        <label for="menu_icon">Icon</label>
                        <input type="text" class="form-control" name="menu_icon" id="menu_icon" placeholder="Icon">
                        <span class="text-danger" id="message_menu_icon"></span>
                    </div>
                    {{-- <div class="form-group">
                        <label for="menu_type">Menu Type</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menu_type" id="menu_type_parent"
                                value="parent">
                            <label class="form-check-label" for="menu_type_parent">Parent</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="menu_type" id="menu_type_children"
                                value="children">
                            <label class="form-check-label" for="menu_type_children">Children</label>
                        </div>
                    </div>
                    <div class="form-group" id="parent_menu">
                        
                    </div> --}}
                    <div class="form-group" id="roles_menu">
                        <label for="roles">Roles</label>
                        <select class="form-control" name="roles[]" id="roles" multiple style="width: 100%">
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
                <div class="modal-footer" id="btn-action-menu">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit_create_menu">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
