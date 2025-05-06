<script>
    $(document).ready(function() {
        // Global variable 
		var getCanvas;
        $('#transaction_list').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("histori-transaksi") }}',
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
                    "data": "transactions.number",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "transactions.event.name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "transactions.status",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    data: null,
                    title: 'Action',
                    wrap: true,
                    render: function(item) {
                        if (item.transactions != null) {
                            const transId = item.transactions.id;
                            const transNumber = item.transactions.token_register;
                            // const encodedTransNumber = encodeURIComponent(transNumber);

                            if (item.transactions.status === 'pending') {
                                return `
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button> 
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-success btn-sm mt-2 upload_transaction" data-bs-toggle="modal" data-bs-target="#uploadTransaction">Upload</button>
                                `;
                            } else if (item.transactions.status === 'progress') {
                                return `
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button>
                                `;
                            } else if (item.transactions.status === 'success') {
                                return `
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button> 
                                    <a href="/generate-ticket-barcode?transnumber=${transNumber}" class="btn btn-outline-danger btn-sm mt-2">Ticket</a>
                                    <a href="/generate-ticket-qrcode?transnumber=${transNumber}" class="btn btn-outline-danger btn-sm mt-2">Qr Code</a>
                                `;
                            }
                        } else {
                            return '';
                        }
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

        $(document).on('click', '.detail_transaction', function(e) {
            e.preventDefault()
            let transaction_id = $(this).data('transaction_id')
            // alert(transaction_id)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("detail-transaksi") }}',
                type: 'GET',
                data: {
                    transaction_id: transaction_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_id').html(res.data.number)
                    $('#detail_username').html(res.data.user.username)
                    $('#detail_event').html(res.data.event.name)
                    $('#detail_date').html(moment(res.data.event.date).format('lll'))
                    $('#detail_status').html(res.data.status)
                    $('#detail_price').html(res.data.price)
                    if (res.data.with_hotel == true) {
                        $('.is_with_hotel').empty()
                        $('.is_with_hotel').append(`
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Harga Hotel</span>
                            <p id="">${res.data.harga_hotel}</p>
                        </div>
                        
                        `)
                        $('.is_with_hotel').append(`
                        <div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Jumlah Kamar Hotel</span>
                            <p id="">${res.data.total_room + ` Room`}</p>
                        </div>
                        `)
                    } else {
                        $('.is_with_hotel').empty()
                    }
                    $('.is_payment').empty()
                    if (res.data.proof_of_payment != null) {
                        let link_proof_of_payment = '/uploads/bukti_transaksi/' + res.data.proof_of_payment
                        $('.is_payment').append(`<div class="col-sm-6 col-sm-6 col-6">
                            <span style="font-weight: 700 !important;">Link bukti pembayaran</span>
                            <br/>
                            <a href="${link_proof_of_payment}" target="_blank" style="font-size: 12px;">klik disini</a>
                        </div>`)
                    }
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
                }
            })
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
                        // text: res.meta.message,
                        text: 'sukses mengupload bukti pembayaran.',
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

        

        var element = $("#ticket");

        document.getElementById("preview_qrcode").addEventListener("click", function() {
            html2canvas(document.getElementById("data_ticket"), {
                // allowTaint: true,
                allowTaint: false,
                useCORS: true,
                onrendered: function(canvas) {
                    var anchorTag = document.createElement("a");
                    anchorTag.download = "qrcode.jpg";
                    anchorTag.href = canvas.toDataURL();
                    anchorTag.target = '_blank';
                    anchorTag.click();
                }
            })
            
            // .then(function (canvas) {
            // });
        });

        $('#myInput').val("").focus();
        
        $(document).on('keyup', '#myInput', function(e){
            if(tex !=="" && e.keyCode===13){
                $('#myInput').val(tex).focus();
            }
        })
        $('#myInput').keyup(function(e){
            var tex = $(this).val();
            e.preventDefault();
        });
    });
</script>