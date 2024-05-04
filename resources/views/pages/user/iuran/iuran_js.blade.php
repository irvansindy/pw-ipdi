<script>
    $(document).ready(function() {
        fetchIuran()
        function fetchIuran() {
            $('#list_fetch_data').empty()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-iuran-anggota") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $.each(res.data, function(i, iuran) {
                        $('#list_fetch_data').append(`
                        <div class="col-md-3">
                            <div class="card mx-2 list_data_iuran" data-bs-toggle="modal" data-bs-target="#form_create_iuran" data-id="${iuran.id}" style="cursor:pointer !important;">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                                    ${iuran.name}
                                                </p>
                                                <h5 class="font-weight-bolder">
                                                    Rp.${iuran.price}
                                                </h5>
                                                <p class="mb-0">
                                                    <span class="text-success text-sm font-weight-bolder">
                                                        ${iuran.description}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div
                                                class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                                <i class="ni ni-money-coins text-lg opacity-10"
                                                    aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `)
                    })
                }
            })
        }

        // transactions
        $('#table_list_iuran').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("fetch-transaksi-iuran") }}',
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
                    "data": "transactions.iuran_number",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "transactions.date",
                    "defaultContent": "<i>Not set</i>",
                    render: DataTable.render.datetime('YYYY')
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-iuran_id="'+item.transactions.id+'" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#form_detail_iuran">View</button>'
                    }
                },
            ]
        })

        $(document).on('click', '.list_data_iuran', function(e) {
            e.preventDefault()
            let id = $(this).data('id')
            $('#form_data_peserta').hide()
            $('#form_create_data_iuran')[0].reset()
            $('#create_iuran_id').val(id)
            // $('#create_transaction_iuran').removeAttr('data-iuran_id')
            // $('#create_transaction_iuran').attr('data-iuran_id', id)
            $('#create_transaction_iuran').data('iuran_id', id);
        })

        $(document).on('change', '.data_iuran_type', function() {
            let data = $('input[name=iuran_type]:checked').val()
            if (data == 'kolektif') {
                $('#form_data_peserta').show()
                $('#list_peserta').empty()
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("fetch-peserta") }}',
                    type: 'GET',
                    dataType: 'json',
                    async: true,
                    success: function(res) {
                        $.each(res.data, function(i, peserta) {
                            $('#list_peserta').append(`
                                <option value="${peserta.id}">${peserta.username}</option>
                            `)
                        })
                    }
                })
            } else if(data == 'mandiri') {
                $('#form_data_peserta').hide()
                $('#list_peserta').empty()
            }
        })

        $(document).on('click', '#create_transaction_iuran', function(e) {
            e.preventDefault()
            let iuran_id = $(this).data('iuran_id')
            let iuran_type = $("input[name=iuran_type]").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-iuran-peserta") }}',
                type: 'POST',
                // data: {
                //     id: iuran_id,
                //     iuran_type: iuran_type
                // },
                data: new FormData($('#form_create_data_iuran')[0]),
                processData: false,
                contentType: false,
                cache: false
                // dataType: 'json',
                // async: true,
            })
        })

        $(document).on('click', '.detail_transaction', function(e) {
            e.preventDefault()
            let iuran_id = $(this).data('iuran_id')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("detail-transaksi-iuran") }}',
                type: 'GET',
                data: {
                    iuran_id: iuran_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#list_detail_iuran_peserta').empty()
                    $('#nomor_iuran').html(res.data[0].iuran_number)
                    $('#tahun_iuran').html(res.data[0].date)
                    $('#upload_file_iuran').attr('data-iuran_id',res.data[0].id)
                    
                    let no_detail = 1;
                    console.log(res.data[0].transaction_detail);
                    $.each(res.data[0].transaction_detail, function(i, data) {
                        $('#list_detail_iuran_peserta').append(`
                        <tr>
                            <td>${no_detail++}</td>
                            <td>${data.user.username}</td>
                        </tr>
                        `)
                    })
                },
                error: function(xhr) {
                    // console.log(xhr.responseText);
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'gagal memuat data, silahkan hubungi admin.',
                    })
                }
            })
        })

        $(document).on('click', '#upload_file_iuran', function(e) {
            e.preventDefault()

            let form_data = new FormData();
            let id = $(this).data('iuran_id')
            let upload_iuran = $('input[name="upload_iuran"]')[0].files[0];
            form_data.append('id', id);
            form_data.append('upload_iuran', upload_iuran);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("upload-iuran-peserta") }}',
                type: 'POST',
                // data: new FormData($('#form_update_data_iuran')[0]),
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(res) {
                    $('#table_list_iuran').DataTable().ajax.reload();
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

        $("#list_peserta").select2({
            dropdownParent: $('#form_create_iuran'),
            multiple: true,
        });
    })
</script>