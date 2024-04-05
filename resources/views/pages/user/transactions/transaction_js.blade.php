<script>
    $(document).ready(function() {
        $('#transaction_list').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("transaction-history") }}',
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
                    "data": "number",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "event.name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "status",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-transaction_id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button> <button type="button" data-transaction_id="'+item.id+'" class="btn btn-outline-success btn-sm mt-2 upload_transaction" data-bs-toggle="modal" data-bs-target="#uploadTransaction">Upload</button>'
                    }
                },
            ]
        })

        $(document).on('click', '#refresh_transaction', function(e) {
            e.preventDefault()
            $('#transaction_list').DataTable().ajax.reload();
        })

        $(document).on('click', '.upload_transaction', function(e) {
            e.preventDefault()
            $('#form_upload_file')[0].reset()
            let transaction_id = $(this).data('transaction_id')
            $('#file_transaction_id').val(transaction_id)
            $('#submit_upload_transaction').attr('data-transaction_id', transaction_id)
            // alert(transaction_id)
        })

        $(document).on('click', '#submit_upload_transaction', function() {
            // e.preventDefault()
            let form_data = new FormData();
            let trans_id = $(this).data('transaction_id')
            let file_upload = $('input[name="file_upload_transaction"]')[0].files[0];
            form_data.append('id', trans_id);
            form_data.append('file_upload_transaction', file_upload);

            $.ajax({
                url: '{{ route("upload-transaction") }}',
                // type: 'POST',
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                // dataType: 'json',
                // async: true,
                enctype: 'multipart/form-data',
                success: function(res) {
                    $('#transaction_list').DataTable().ajax.reload();
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                },
                error: function(xhr) {
                    // console.log(xhr.responseText);
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'Gagal upload, silahkan hubungi admin.',
                    })
                }
            })
        })
    });
</script>