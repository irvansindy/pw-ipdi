<script>
    $(document).ready(function() {
        $('#user_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("fetch-users") }}',
                type: 'GET',
            },
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "username",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "email",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "email",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<a href="#" class="text-info font-weight-bold text-xs mx-2 edit_user" data-user_id="'+item.id+'" data-username="'+item.username+'" data-bs-toggle="modal" data-bs-target="#form_edit_user" data-toggle="tooltip" data-original-title="Edit user">Edit</a>'
                    }
                },
            ],
        })
        
        $('#admin_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("fetch-admin") }}',
                type: 'GET',
            },
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "username",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "email",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "email",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<a href="#" class="text-info font-weight-bold text-xs mx-2 edit_user" data-user_id="'+item.id+'" data-username="'+item.username+'" data-bs-toggle="modal" data-bs-target="#form_edit_user" data-toggle="tooltip" data-original-title="Edit user">Edit</a> <a href="#" class="text-black font-weight-bold text-xs mx-2 role_admin" data-user_id="'+item.id+'" data-username="'+item.username+'" data-bs-toggle="modal" data-bs-target="#form_role_admin" data-toggle="tooltip" data-original-title="Role Admin">Role</a>'
                    }
                },
            ],
        })

        $(document).on('click', '.edit_user', function() {
            var userId = $(this).data('user_id');
            var username = $(this).data('username');
            $('#form_edit_user').find('#user_id').val(userId);
            $('#form_edit_user').find('#username').val(username);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-user-by-id") }}', 
                type: 'GET',
                data: {
                    id: userId
                },
                dataType: 'json',
                success: function(res) {
                    $('#form_edit_user_data')[0].reset()
                    let user = res.data;
                    $('#form_edit_user_data').find('#user_id').val(user.id);
                    $('#form_edit_user_data').find('#username').val(user.username);
                    $('#form_edit_user_data').find('#email').val(user.email);
                    $('#form_edit_user_data').find('#nik').val(user.nik);
                    $('#form_edit_user_data').find('#nira_ppni').val(user.nira_ppni);
                    $('#form_edit_user_data').find('#nira_ipdi').val(user.nira_ipdi);
                    $('#form_edit_user_data').find('#pw_ipdi_provinsi').val(user.pw_ipdi_provinsi);
                    $('#form_edit_user_data').find('#nama_perusahaan').val(user.nama_perusahaan);
                    $('#form_edit_user_data').find('#pw_ipdi_provinsi_current').val(user.pw_ipdi_provinsi);
                    // Set value pada select
                    $('#form_edit_user_data').find('#center_dialisis').val(user.center_dialisis);
                    $('#form_edit_user_data').find('#number_phone').val(user.number_phone);
                    $('#form_edit_user_data').find('#alamat_center_dialisis_perusahaan').val(user.alamat_center_dialisis_perusahaan);
                    $('#form_edit_user_data').find('#last_edication').val(user.last_edication);
                },
                error: function(xhr) {
                    $('#form_edit_user_data')[0].reset()
                    alert('Error fetching user data.');
                }
            })
        });

        $(document).on('click', '#update_user', function(e) {
            e.preventDefault();
            let form_data = new FormData($('#form_edit_user_data')[0])
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-user-by-id") }}', 
                type: 'POST',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(res) {
                    
                }
            })
        })

        $(document).on('click', '#create_admin_user', function() {
            $('#user_for_admin').select2({
                width: '100%',
                dropdownParent: $('#modalAdminCMS')
            })
            $('#roles_admin_cms').select2({
                width: '100%',
                dropdownParent: $('#modalAdminCMS')
            })
        });

        $(document).on('click', '#submit_admin_cms', function () {
            let user_id = $('#user_for_admin').val();
            let roles = $('#roles_admin_cms').val(); // ini array karena multiple

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("create-admin-cms") }}',
                type: 'POST',
                data: {
                    user_id: user_id,
                    roles: roles
                },
                success: function (res) {
                    alert('Admin CMS created successfully');
                    $('#modalAdminCMS').modal('hide');
                    $('#admin_management').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    let err = xhr.responseJSON?.message ?? 'Error creating Admin CMS';
                    alert(err);
                }
            });
        });

    })
</script>