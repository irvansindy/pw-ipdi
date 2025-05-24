<script>
    $(document).ready(function () {
        // Initialize the menu table
        $('#menu_management').DataTable({
            processing: true,
            ajax: '{{ route('fetch-menu-permission') }}',
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "url",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-menu_id="' +item.id +'" class="btn btn-outline-info btn-sm mt-2 detail_menu_permission" data-bs-toggle="modal" data-bs-target="#formMenus">View</button>'
                    }
                },
            ]
        });

        $(document).on('click', '#for_create_role', function () {
            $('#formMenus').modal('show');
            $('#data_form_menu')[0].reset();
            $('#formMenusLabel').text('Create Menu');
            $('.text-danger').text('');
            $('#btn-action-menu').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary" id="submit_create_menu"">Submit</button>');

        });

        $(document).on('click', '#submit_create_menu', function (e) {
            e.preventDefault();
            var formData = new FormData($('#data_form_menu')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store-menu-permission') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    $('#formMenus').modal('hide');
                    $('.text-danger').text('');
                    $('#menu_management').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    // alert('Error creating menu.');
                    let response_error = JSON.parse(xhr.responseText)
                    $.each(response_error.meta.message.errors, function(i, value) {
                            $('#message_' + i.replace('.', '_')).text(value)
                        })
                }
            });
        });

        // Handle the delete button click
        $(document).on('click', '.detail_menu_permission', function (e) {
            e.preventDefault();
            var id = $(this).data('menu_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-menu-permission-by-id') }}',
                method: 'GET',
                data: {
                    id: id
                },
                success: function (res) {
                    $('#formMenus').modal('show');
                    $('#data_form_menu')[0].reset();
                    $('#formMenusLabel').text('Update Menu');
                    $('.text-danger').text('');
                    $('#btn-action-menu').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update_create_menu">Update</button>');
                    const menu = res.data
                    let roles = menu.permissions[0].roles
                    // Populate the form fields with the fetched data
                    $('#menu_id').val(res.data.id);
                    $('#menu_name').val(res.data.name);
                    $('#menu_url').val(res.data.url);
                    $('#menu_icon').val(res.data.icon);
                    
                    // Set the roles
                    // $('#roles').val(res.data.roles.map(role => role.id)).trigger('change');
                    $('[name="roles[]"]').val(roles.map(role => role.id)).trigger('change')
                },
            })
        });

        $(document).on('click', '#update_create_menu', function (e) {
            e.preventDefault();
            var formData = new FormData($('#data_form_menu')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('update-menu-permission') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    $('#formMenus').modal('hide');
                    $('.text-danger').text('');
                    $('#menu_management').DataTable().ajax.reload();
                    alert('Menu updated successfully.');
                },
                error: function (xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    $.each(response_error.meta.message.errors, function(i, value) {
                            $('#message_' + i.replace('.', '_')).text(value)
                        })
                }
            });
        });
    });
    $('#roles').select2({
        dropdownParent: $('#formMenus')
    })
</script>