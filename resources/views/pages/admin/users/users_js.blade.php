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
                        return '<a href="#" class="text-info font-weight-bold text-xs mx-2 edit_user" data-user_id="'+item.id+'" data-username="'+item.username+'" data-bs-toggle="modal" data-bs-target="#form_edit_user" data-toggle="tooltip" data-original-title="Edit user">Edit</a>'
                    }
                },
            ],
        })

        $(document).on('click', '.edit_user', function(e) {
            e.preventDefault()
            let user_id = $(this).data('user_id')
            $('#form_edit_user_data')[0].reset()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delete-user") }}',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    
                }
            })
        })
        
        $(document).on('click', '.delete_user', function(e) {
            e.preventDefault()
            let user_id = $(this).data('user_id')
            let username = $(this).data('username')
            $('#delete_username').empty()
            $('#delete_user_id').empty()
            $('#delete_user_id').val(user_id)
            $('#delete_username').html('Yakin ingin menghapus user '+username)
        })
        
        $(document).on('click', '#confirm_delete_user', function(e) {
            e.preventDefault()
            let user_id = $('#delete_user_id').val()
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delete-user") }}',
                type: 'POST',
                data: {
                    user_id: user_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#form_delete_user').modal('hide')
                    $('#user_management').DataTable().ajax.reload();
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    console.log(response_error)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'gagal menghapus data, silahkan hubungi super admin.',
                    })
                }
            })
        })
    })
</script>