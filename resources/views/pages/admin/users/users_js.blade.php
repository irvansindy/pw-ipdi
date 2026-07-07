<script>
    $(document).ready(function() {
        $('#user_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route('fetch-users') }}',
                type: 'GET',
            },
            columns: [{
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
                        return '<a href="#" class="text-info font-weight-bold text-xs mx-2 edit_user" data-user_id="' +
                            item.id + '" data-username="' + item.username +
                            '" data-bs-toggle="modal" data-bs-target="#form_edit_user" data-toggle="tooltip" data-original-title="Edit user">Edit</a>'
                    }
                },
            ],
        })

        $('#admin_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route('fetch-admin') }}',
                type: 'GET',
            },
            columns: [{
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
                        return '<a href="#" class="text-info font-weight-bold text-xs mx-2 edit_user" data-user_id="' +
                            item.id + '" data-username="' + item.username +
                            '" data-bs-toggle="modal" data-bs-target="#form_edit_user" data-toggle="tooltip" data-original-title="Edit user">Edit</a> <a href="#" class="text-black font-weight-bold text-xs mx-2 role_admin" data-user_id="' +
                            item.id + '" data-username="' + item.username +
                            '" data-bs-toggle="modal" data-bs-target="#form_role_admin" data-toggle="tooltip" data-original-title="Role Admin">Role</a>'
                    }
                },
            ],
        })

        $(document).on('click', '.edit_user', function() {
            var userId = $(this).data('user_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-user-by-id') }}',
                type: 'GET',
                data: {
                    id: userId
                },
                dataType: 'json',
                success: function(res) {
                    let user = res.data;
                    $('#form_edit_user_data')[0].reset();
                    $('#form_edit_user_data').find('#user_id').val(user.id);
                    $('#form_edit_user_data').find('#username').val(user.username);
                    $('#form_edit_user_data').find('#email').val(user.email);
                    $('#form_edit_user_data').find('#nik').val(user.nik);
                    $('#form_edit_user_data').find('#nira_ppni').val(user.nira_ppni);
                    $('#form_edit_user_data').find('#nira_ipdi').val(user.nira_ipdi);
                    $('#form_edit_user_data').find('#pw_ipdi_provinsi').val(user
                        .pw_ipdi_provinsi);
                    $('#form_edit_user_data').find('#nama_perusahaan').val(user
                        .nama_perusahaan);
                    $('#form_edit_user_data').find('#pw_ipdi_provinsi_current').val(user
                        .pw_ipdi_provinsi);
                    $('#form_edit_user_data').find('#center_dialisis').val(user
                        .center_dialisis);
                    $('#form_edit_user_data').find('#number_phone').val(user.number_phone);
                    $('#form_edit_user_data').find('#alamat_center_dialisis_perusahaan')
                        .val(user.alamat_center_dialisis_perusahaan);
                    $('#form_edit_user_data').find('#last_edication').val(user
                        .last_edication);

                    // Tampilkan modal setelah data terisi
                    $('#form_edit_user').modal('show');
                },
                error: function(xhr) {
                    // Tampilkan error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error fetching user data',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            })
        });

        $(document).on('click', '#update_user', function(e) {
            e.preventDefault();

            // Tampilkan loading animation
            let buttonText = $('#update_user').html();
            $('#update_user').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...'
            );

            let form_data = new FormData($('#form_edit_user_data')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('update-user') }}',
                type: 'POST',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(res) {
                    // Sembunyikan loading animation
                    $('#update_user').prop('disabled', false).html(buttonText);

                    // Tutup modal
                    $('#form_edit_user').modal('hide');

                    // Tampilkan success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'User berhasil diupdate',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });

                    // Refresh datatable
                    $('#user_management').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    // Sembunyikan loading animation
                    $('#update_user').prop('disabled', false).html(buttonText);

                    // Handle error response
                    let errorMessage = 'Terjadi kesalahan saat update user';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        if (typeof xhr.responseJSON.message === 'object') {
                            // Jika error adalah object (validation errors)
                            let errors = xhr.responseJSON.message;
                            let errorList = '<ul style="text-align: left;">';
                            for (let key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorList += '<li>' + errors[key].join(', ') + '</li>';
                                }
                            }
                            errorList += '</ul>';
                            errorMessage = errorList;
                        } else {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }

                    // Tampilkan error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
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

        $(document).on('click', '#submit_admin_cms', function() {
            let user_id = $('#user_for_admin').val();
            let roles = $('#roles_admin_cms').val(); // ini array karena multiple

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('create-admin-cms') }}',
                type: 'POST',
                data: {
                    user_id: user_id,
                    roles: roles
                },
                success: function(res) {
                    alert('Admin CMS created successfully');
                    $('#modalAdminCMS').modal('hide');
                    $('#admin_management').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let err = xhr.responseJSON?.message ?? 'Error creating Admin CMS';
                    alert(err);
                }
            });
        });

        $(document).on('click', '.role_admin', function() {
            var userId = $(this).data('user_id');
            var username = $(this).data('username');
            $('#form_role_admin').find('#user_id').val(userId);
            $('#form_role_admin').find('#username').val(username);
            alert('Role Admin for ' + username);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-admin-by-id') }}',
                type: 'GET',
                data: {
                    id: userId
                },
                dataType: 'json',
                success: function(res) {
                    $('#form_role_admin_data')[0].reset()
                    let user = res.data;
                    $('#form_role_admin_data').find('#user_id').val(user.id);
                    $('#form_role_admin_data').find('#username').val(user.username);
                    $('#form_role_admin_data').find('#email').val(user.email);
                    $('#form_role_admin_data').find('#roles').val(user.roles);
                },
                error: function(xhr) {
                    $('#form_role_admin_data')[0].reset()
                    alert('Error fetching user data.');
                }
            })
        });

    })
</script>
