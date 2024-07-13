<script>
    $(document).ready(function() {
        $('#transaction_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("transaction-fetch") }}',
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
                    "data": "user.username",
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
                        return '<button type="button" data-transaction_id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button> <button type="button" data-transaction_id="'+item.id+'" data-transaction_number="'+item.number+'" class="btn btn-outline-danger btn-sm mt-2 delete_transaction" data-bs-toggle="modal" data-bs-target="#form_delete_transaction">Delete</button>'
                    }
                },
            ]
        })

        $(document).on('click', '.detail_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            // alert(transaction_id)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('transaction-detail') }}',
                type: 'get',
                data: {
                    id: transaction_id
                },
                dataType: 'json',
                success: function(res) {
                    $('#form_update_transaction')[0].reset()
                    $('#update_transaction_id').val(res.data.id)
                    $('#detailTransactionLabel').html('Detail Transaction ' + res.data.number)
                    $('#update_transaction_event').html(res.data.event.name)
                    $('#update_transaction_user').html(res.data.user.username)
                    $('#update_transaction_date').html(res.data.date)
                    $('#update_transaction_status').html(res.data.status)
                    $('#update_transaction_proof_of_payment').empty()
                    $('#update_transaction_proof_of_payment').append(`
                        <a href="{{ asset('uploads/bukti_transaksi/${res.data.proof_of_payment}') }}" target="_blank">Bukti Transaksi</a>
                    `)

                    let numbering_user = 1
                    $('#list_data_pererta').empty()
                    $.each(res.data.transaction_detail, function(i, peserta) {
                        $('#list_data_pererta').append(`
                            <tr>
                                <td>${numbering_user++}</td>
                                <td>${peserta.user.username}</td>
                            </tr>
                        `)
                    })
                },
                error: function(xhr, status, error) {
                    $('#detailTransaction').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                    })
                }
            })
        })

        $(document).on('click', '#update_transaction', function(e) {
            e.preventDefault()
            let update_transaction_id = $('#update_transaction_id').val()
            // alert(update_transaction_id)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("transaction-approval") }}',
                type: 'POST',
                // method: 'POST',
                // processData: false,
                // contentType: false,
                // cache: false,
                data: {
                    update_transaction_id: update_transaction_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detailTransaction').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'gagal memuat data, silahkan hubungi admin.',
                    })
                }
            })
        })

        $(document).on('click', '.delete_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            let transaction_number = $(this).data('transaction_number')
            $('#delete_transaction_id').empty()
            $('#delete_transaction_number').empty()
            $('#delete_transaction_id').val(transaction_id)
            $('#delete_transaction_number').html('Yakin ingin menghapus transaksi dengan nomor '+transaction_number)
            $('#confirm_delete_transaction').attr('data-transaction_id',transaction_id)
            $('#confirm_delete_transaction').attr('data-transaction_number',transaction_number)
        })
        
        $(document).on('click', '#confirm_delete_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            let delete_transaction_id = $('#delete_transaction_id').val()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delete-transaction") }}',
                type: 'POST',
                data: {
                    transaction_id: transaction_id,
                    delete_transaction_id: delete_transaction_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#form_delete_transaction').modal('hide')
                    $('#transaction_management').DataTable().ajax.reload();
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
                        text: 'gagal menghapus data, silahkan hubungi admin.',
                    })
                }
            })
        })
    })
</script>