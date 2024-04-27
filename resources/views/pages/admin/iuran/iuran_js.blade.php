<script>
    $(document).ready(function() {
        $('#iuran_management').DataTable({
            processing: true,
            // serverside: false,
            ajax: {
                url: '{{ route("fetch-iuran") }}',
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
                {
                    "data": "price",
                    // "render": function(data, item) {
                    //     return 'Rp.' + item.id
                    // },
                    render: function (data, type, row) {
                        return 'Rp.'+row.price;
                    },
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "year",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-iuran_id="' +
    item.id +
    '" class="btn btn-outline-info btn-sm mt-2 detail_iuran_data" data-bs-toggle="modal" data-bs-target="#formDetailIuran">View</button>'
                    }
                },
            ]
        })

        $(document).on('click', '#for_create_iuran_data', function(e) {
            e.preventDefault()
            $('#form_create_iuran')[0].reset()
        })

        $(document).on('click', '#create_data_iuran', function(e) {
            e.preventDefault()
            let data = {
                'name': $('#create_iuran_name').val(),
                'description': $('#create_iuran_desc').val(),
                'price': $('#create_iuran_price').val(),
                'year': $('#create_iuran_year').val()
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-iuran") }}',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $('#formCreateIuran').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: errorMessage.meta.message,
                        text: errorMessage.data.message,
                        // text: "User role cannot empty"
                    })
                }
            })
        })

        $(document).on('click', '.detail_iuran_data', function(e) {
            e.preventDefault()
            let iuran_id = $(this).data('iuran_id')
            // alert(iuran_id)
            $('#form_detail_iuran')[0].reset()
            $.ajax({
                url: '{{ route("detail-iuran") }}',
                type: 'GET',
                data: {
                    id: iuran_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_iuran_name').val(res.data.name)
                    $('#detail_iuran_desc').val(res.data.description)
                    $('#detail_iuran_price').val(res.data.price)
                    $('#detail_iuran_year').val(res.data.year)
                    $('#update_data_iuran').attr('data-id', res.data.id)
                },
                error: function(xhr, status, error) {
                    $('#formDetailIuran').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                    })
                }
            })
        })

        $(document).on('click', '#update_data_iuran', function(e) {
            e.preventDefault()
            let id = $(this).data('id')
            let detail_iuran_name = $('#detail_iuran_name').val()
            let detail_iuran_desc = $('#detail_iuran_desc').val()
            let detail_iuran_price = $('#detail_iuran_price').val()
            let detail_iuran_year = $('#detail_iuran_year').val()

            let data_detail = {
                'id': id,
                'name': $('#detail_iuran_name').val(),
                'description': $('#detail_iuran_desc').val(),
                'price': $('#detail_iuran_price').val(),
                'year': $('#detail_iuran_year').val()
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("update-iuran") }}',
                type: 'post',
                data: data_detail,
                dataType: 'json',
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $('#formDetailIuran').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: errorMessage.meta.message,
                        text: errorMessage.data.message,
                        // text: "User role cannot empty"
                    })
                }
            })
        })
    })
</script>