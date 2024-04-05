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
                        return '<button type="button" data-transaction_id="'+item.id+'" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button>'
                    }
                },
            ]
        })

        $(document).on('click', '.detail_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
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
                    $('#detailTransactionLabel').html('Detail Transaction ' + res.data.number)
                    $('#update_transaction_event').html(res.data.event.name)
                    $('#update_transaction_user').html(res.data.user.username)
                    $('#update_transaction_date').html(res.data.date)
                    $('#update_transaction_status').html(res.data.status)
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
    })
</script>