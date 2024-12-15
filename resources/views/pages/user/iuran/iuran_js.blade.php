<script>
    $(document).ready(function() {
        // fetchIuran()
        // function fetchIuran() {
        //     $('#list_fetch_data').empty()
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: '{{ route("fetch-iuran-anggota") }}',
        //         type: 'GET',
        //         dataType: 'json',
        //         async: true,
        //         success: function(res) {
        //             $.each(res.data, function(i, iuran) {
        //                 $('#list_fetch_data').append(`
        //                 <div class="col-md-3">
        //                     <div class="card mx-2 list_data_iuran" data-bs-toggle="modal" data-bs-target="#form_create_iuran" data-id="${iuran.id}" style="cursor:pointer !important;">
        //                         <div class="card-body p-3">
        //                             <div class="row">
        //                                 <div class="col-8">
        //                                     <div class="numbers">
        //                                         <p class="text-sm mb-0 text-uppercase font-weight-bold">
        //                                             ${iuran.name}
        //                                         </p>
        //                                         <h5 class="font-weight-bolder">
        //                                             Rp.${iuran.price}
        //                                         </h5>
        //                                         <p class="mb-0">
        //                                             <span class="text-success text-sm font-weight-bolder">
        //                                                 ${iuran.description}
        //                                             </span>
        //                                         </p>
        //                                     </div>
        //                                 </div>
        //                                 <div class="col-4 text-end">
        //                                     <div
        //                                         class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
        //                                         <i class="ni ni-money-coins text-lg opacity-10"
        //                                             aria-hidden="true"></i>
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //                 `)
        //             })
        //         }
        //     })
        // }

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
                    "data": "iuran.iuran_number",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "iuran.date",
                    "defaultContent": "<i>Not set</i>",
                    render: DataTable.render.datetime('YYYY')
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        if (item.iuran.status == 'pending') {
                            return '<button type="button" data-iuran_id="'+item.iuran.id+'" class="btn btn-outline-success btn-sm mt-2 upload_iuran_transaction" data-bs-toggle="modal" data-bs-target="#uploadIuranTransaction">Upload</button> <button type="button" data-iuran_id="'+item.iuran.id+'" class="btn btn-outline-info btn-sm mt-2 detail_iuran_transaction" data-bs-toggle="modal" data-bs-target="#form_detail_iuran">View</button>'
                        } else {
                                return '<button type="button" data-iuran_id="'+item.iuran.id+'" class="btn btn-outline-info btn-sm mt-2 detail_iuran_transaction" data-bs-toggle="modal" data-bs-target="#form_detail_iuran">View</button>'
                        }
                    }
                },
            ]
        })

        $(document).on('click', '.list_data_iuran', function(e) {
            e.preventDefault()
            let id = $(this).data('id')
            alert(id)
            $('#form_data_peserta').hide()
            $('#form_create_data_iuran')[0].reset()
            $('#create_iuran_id').val(id)
            $('#create_transaction_iuran').attr('data-iuran_id', id);
        })

        $(document).on('click', '#create_iuran', function() {
            window.location.href = "{{ route('create-iuran-anggota') }}";
            // $('#form_create_data_iuran')[0].reset()
            // $('#form_data_peserta').hide()
            // $('#grand_total_payment_iuran').empty()
            // $.ajax({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     url: '{{ route("fetch-peserta") }}',
            //     type: 'GET',
            //     dataType: 'json',
            //     async: true,
            //     success: function(res) {
            //         $('#grand_total_payment_iuran').empty()
            //         $('#button_action_payment_iuran').empty()
            //         $('#button_action_payment_iuran').append(`
            //             <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
            //             <button type="button" class="btn bg-gradient-primary" id="generate_payment_iuran" data-iuran_id="">Payment</button>
            //         `)
            //     }
            // })
        })

        $("#tahun_iuran").keypress(function (e) {
            if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
        });

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

        $(document).on('click', '#generate_payment_iuran', function(e) {
            e.preventDefault()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("generate-payment-iuran") }}',
                data: new FormData($('#form_create_data_iuran')[0]),
                processData: false,   // Prevent jQuery from processing the data
                contentType: false,   // Prevent jQuery from setting content type
                cache: false,
                success: function(res) {
                    // $('#form_create_event').modal('hide')
                    $('#grand_total_payment_iuran').empty()
                    let grand_total = res.data != null ? res.data : 0
                    $('#grand_total_payment_iuran').append(
                        `<p>Total: Rp.${grand_total}</p>`
                    )
                    $('#val_grand_total_payment_iuran').val(grand_total)
                    $('#button_action_payment_iuran').empty()
                    $('#button_action_payment_iuran').append(`
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary" id="create_transaction_iuran" data-iuran_id="">Submit</button>
                    `)
                    // Swal.fire({
                    //     icon: "success",
                    //     title: 'Success!',
                    //     text: res.meta.message,
                    //     footer: '<a href="{{ route('histori-transaksi') }}" class="text-primary">Riwayat Transaksi</a>'
                    // })
                },
            })
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
                cache: false,
                // dataType: 'json',
                // async: true,
                success: function(res) {
                    $('#form_create_event').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                        footer: '<a href="{{ route('histori-transaksi') }}" class="text-primary">Riwayat Transaksi</a>'
                    })
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            })
        })

        $(document).on('click', '.upload_iuran_transaction', function(e) {
            e.preventDefault()
            let iuran_id = $(this).data('iuran_id')
            $('#form_upload_bukti_iuran')[0].reset()
            $('#master_iuran_id').val(iuran_id)
            $('#upload_file_bukti_iuran').attr('data-iuran_id',iuran_id)
        })

        $(document).on('click', '#upload_file_bukti_iuran', function(e) {
            e.preventDefault()
            let data_upload = new FormData($('#form_upload_bukti_iuran')[0])
            $.ajax({
                url: '{{ route("upload-iuran-peserta") }}',
                type: 'POST',
                data: data_upload,
                processData: false,   // Prevent jQuery from processing the data
                contentType: false,   // Prevent jQuery from setting content type
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
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

        $(document).on('click', '#button_append_iuran', function() {
            alert('button_append_iuran')
            $('.array-list-peserta-iuran').append(`
                <div class="row cls_data_array_iuran">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Nama</label>
                            <input class="form-control" type="text" name="nama_peserta_iuran[]" placeholder="Nama">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">Central Dialisis</label>
                            <select class="form-control central_dialisis_iuran" name="central_dialisis_iuran" id="" placeholder="Central Dialisis"></select>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group">
                            <label for="">Uang Iuran 20K</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Uang Iuran 20K
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group">
                            <label for="">Uang Iuran 50K</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Uang Iuran 50K
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger mt-4 button_pop_iuran" name="button_pop_iuran"
                                id=""><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                </div>
            `)
        })

        $(document).on('click', '.button_pop_iuran', function() {
            $(this).closest('.cls_data_array_iuran').remove()
        })
    })
</script>