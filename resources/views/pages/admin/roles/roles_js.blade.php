<script>
    new DataTable('#role_management', {
        processing: true,
        ajax: {
            url: '{{ route("fetch-roles") }}',
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
                "data": "name",
                "defaultContent": "<i>Not set</i>"
            },
            // {
            //     'data': null,
            //     title: 'Action',
            //     wrap: true,
            //     "render": function(item) {
            //         return '<button type="button" data-event_id="' +item.id +'" class="btn btn-outline-info btn-sm mt-2 detail_event_data" data-bs-toggle="modal" data-bs-target="#formUpdateEvent">View</button> <a href="/ticket-qrcode?transnumber=' + item.id + '" class="btn btn-outline-danger btn-sm mt-2">Qr Code</a>'
            //     }
            // },
        ]
    });
    $('#for_create_role').click(function() {
        $('#form_create_role')[0].reset();
    });
    $('#create_role_user').click(function(e) {
        e.preventDefault()
        // alert('success')
        let create_role_name = $('#create_role_name').val()
        let create_permission_role = $('#create_permission_role').val()

        if (create_role_name == '' || create_role_name == null) {
            alert('Role tidak boleh kosong')
            return false;
        }

        let data = {
            'name': create_role_name,
        }
        CreateOrUpdateOrDelete('role-store', 'post', data, '#formCreateRole')
        $('#role_management').DataTable().ajax.reload();
    })
</script>