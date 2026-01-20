<script>
    // Helper functions for loading states
    function setButtonLoading(button, isLoading) {
        if (isLoading) {
            $(button).prop('disabled', true);
            $(button).data('original-html', $(button).html());
            $(button).html(
                `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...`
                );
        } else {
            $(button).prop('disabled', false);
            $(button).html($(button).data('original-html') || 'Submit');
        }
    }

    function showLoader(containerId) {
        $(containerId).html(`
            <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
    }

    $(document).ready(function() {
        // transactions
        $('#table_list_iuran').DataTable({
            processing: true,
            ajax: {
                url: '{{ route('fetch-transaksi-iuran') }}',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.error('Error loading data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat data. Silahkan coba lagi.',
                    });
                }
            },
            columns: [{
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
                    "data": "iuran.status",
                    "defaultContent": "<i>Not set</i>",
                    render: function(data) {
                        const statusClass = data === 'progress' ? 'warning' : 'success';
                        const statusText = data === 'progress' ? 'Menunggu' : 'Terverifikasi';
                        return `<span class="badge bg-${statusClass}">${statusText}</span>`;
                    }
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-iuran_id="' + item.iuran.id +
                            '" class="btn btn-outline-info btn-sm mt-2 detail_iuran_transaction" data-bs-toggle="modal" data-bs-target="#form_detail_iuran">View</button>'
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
        })

        $("#tahun_iuran").keypress(function(e) {
            if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
        });

        $(document).on('change', '.data_iuran_type', function() {
            let data = $('input[name=iuran_type]:checked').val()
            if (data == 'kolektif') {
                $('#form_data_peserta').show()
                $('#list_peserta').empty();
                showLoader('#list_peserta');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('fetch-peserta') }}',
                    type: 'GET',
                    dataType: 'json',
                    async: true,
                    timeout: 10000,
                    success: function(res) {
                        $('#list_peserta').empty();
                        if (res.data && res.data.length > 0) {
                            $('#list_peserta').append(
                                '<option value="">Pilih Peserta</option>');
                            $.each(res.data, function(i, peserta) {
                                $('#list_peserta').append(`
                                    <option value="${peserta.id}">${peserta.username} (${peserta.email})</option>
                                `)
                            })
                        } else {
                            $('#list_peserta').append(
                                '<option value="">Tidak ada data peserta</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#list_peserta').empty();
                        $('#list_peserta').append(
                            '<option value="">Gagal memuat data peserta</option>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal memuat daftar peserta. Silahkan coba lagi.',
                        });
                    }
                })
            } else if (data == 'mandiri') {
                $('#form_data_peserta').hide()
                $('#list_peserta').empty()
            }
        })

        $(document).on('click', '#generate_payment_iuran', function(e) {
            e.preventDefault()

            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route('generate-payment-iuran') }}',
                data: new FormData($('#form_create_data_iuran')[0]),
                processData: false,
                contentType: false,
                cache: false,
                timeout: 10000,
                success: function(res) {
                    $('#grand_total_payment_iuran').empty()
                    let grand_total = res.data != null ? res.data : 0

                    let formattedTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(grand_total);

                    $('#grand_total_payment_iuran').append(
                        `<h5>Total: <strong>${formattedTotal}</strong></h5>`
                    )
                    $('#val_grand_total_payment_iuran').val(grand_total)
                    $('#button_action_payment_iuran').empty()
                    $('#button_action_payment_iuran').append(`
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary" id="create_transaction_iuran" data-iuran_id="">Submit</button>
                    `)

                    setButtonLoading(btn, false);
                },
                error: function(xhr, status, error) {
                    setButtonLoading(btn, false);
                    let errorMsg = 'Gagal menghitung total pembayaran. Silahkan coba lagi.';
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMsg = response.meta?.message || errorMsg;
                    } catch (e) {}

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg,
                    });
                }
            })
        })

        $(document).on('click', '#create_transaction_iuran', function(e) {
            e.preventDefault()

            // Validasi form
            let peserta = $('.nama_peserta_iuran_central_dialisis').length;
            let iuranType = $("input[name=iuran_type]:checked").val();
            let fileUpload = $('input[name="iuran_file_upload"]')[0].files.length;

            if (!iuranType) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan pilih tipe iuran (Mandiri atau Batch/Kolektif)',
                });
                return;
            }

            if (fileUpload === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan upload bukti transfer',
                });
                return;
            }

            if (peserta === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan pilih minimal 1 peserta',
                });
                return;
            }

            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store-iuran-peserta') }}',
                type: 'POST',
                data: new FormData($('#form_create_data_iuran')[0]),
                processData: false,
                contentType: false,
                cache: false,
                timeout: 30000,
                success: function(res) {
                    setButtonLoading(btn, false);
                    $('#form_create_data_iuran')[0].reset();

                    Swal.fire({
                        icon: "success",
                        title: 'Berhasil!',
                        text: res.meta.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('iuran-anggota') }}";
                        }
                    });
                },
                error: function(xhr, status, error) {
                    setButtonLoading(btn, false);

                    let errorMsg = 'Gagal menyimpan data. Silahkan hubungi admin.';
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (response.meta?.message) {
                            errorMsg = response.meta.message;
                        } else if (response.message) {
                            errorMsg = response.message;
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg,
                    });

                    console.error('XHR Error:', xhr);
                }
            })
        })

        $(document).on('click', '.upload_iuran_transaction', function(e) {
            e.preventDefault()
            let iuran_id = $(this).data('iuran_id')
            $('#form_upload_bukti_iuran')[0].reset()
            $('#master_iuran_id').val(iuran_id)
            $('#upload_file_bukti_iuran').attr('data-iuran_id', iuran_id)
        })

        $(document).on('click', '#upload_file_bukti_iuran', function(e) {
            e.preventDefault()
            let data_upload = new FormData($('#form_upload_bukti_iuran')[0])
            $.ajax({
                url: '{{ route('upload-iuran-peserta') }}',
                type: 'POST',
                data: data_upload,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content type
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
            })
        })

        $(document).on('click', '.detail_iuran_transaction', function(e) {
            e.preventDefault()
            let iuran_id = $(this).data('iuran_id')
            let btn = $(this);

            showLoader('#list_detail_iuran_peserta');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('detail-transaksi-iuran') }}',
                type: 'GET',
                data: {
                    iuran_id: iuran_id
                },
                dataType: 'json',
                timeout: 10000,
                success: function(res) {
                    if (res.data && res.data.length > 0) {
                        $('#list_detail_iuran_peserta').empty()
                        $('#source_attchment_iuran').prop('href', res.data[0]
                            .iuran_image_transfer)

                        $('#nomor_iuran').html(res.data[0].iuran_number)
                        $('#tahun_iuran').html(new Date(res.data[0].date).getFullYear())
                        $('#upload_file_iuran').attr('data-iuran_id', res.data[0].id)

                        let no_detail = 1;
                        if (res.data[0].transaction_detail && res.data[0].transaction_detail
                            .length > 0) {
                            $.each(res.data[0].transaction_detail, function(i, data) {
                                $('#list_detail_iuran_peserta').append(`
                                <tr>
                                    <td>${no_detail++}</td>
                                    <td>${data.user?.username || '-'}</td>
                                    <td>Rp ${parseInt(data.biaya_regis_anggota_baru).toLocaleString('id-ID')}</td>
                                    <td>Rp ${parseInt(data.biaya_iuran_tahunan).toLocaleString('id-ID')}</td>
                                    <td>${data.tahun_iuran}</td>
                                </tr>
                                `)
                            })
                        } else {
                            $('#list_detail_iuran_peserta').append(`
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data peserta</td>
                            </tr>
                            `)
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Data transaksi tidak ditemukan',
                        })
                    }
                },
                error: function(xhr, status, error) {
                    $('#list_detail_iuran_peserta').empty();

                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'Gagal memuat detail transaksi. Silahkan coba lagi.',
                    })
                }
            })
        })

        $(document).on('click', '#upload_file_iuran', function(e) {
            e.preventDefault()

            // Validasi file
            let uploadFile = $('input[name="upload_iuran"]')[0].files;
            if (uploadFile.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan pilih file terlebih dahulu',
                });
                return;
            }

            let form_data = new FormData();
            let id = $(this).data('iuran_id')
            let upload_iuran = uploadFile[0];

            form_data.append('master_iuran_id', id);
            form_data.append('file_iuran_data', upload_iuran);

            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('upload-iuran-peserta') }}',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 30000,
                enctype: 'multipart/form-data',
                success: function(res) {
                    setButtonLoading(btn, false);
                    $('#table_list_iuran').DataTable().ajax.reload();

                    Swal.fire({
                        icon: "success",
                        title: 'Berhasil!',
                        text: res.meta.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Close modal if exists
                            let modal = bootstrap.Modal.getInstance(document
                                .getElementById('form_detail_iuran'));
                            if (modal) modal.hide();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    setButtonLoading(btn, false);

                    let errorMsg = 'Gagal upload file. Silahkan hubungi admin.';
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (response.meta?.message) {
                            errorMsg = response.meta.message;
                        } else if (response.message) {
                            errorMsg = response.message;
                        }
                    } catch (e) {}

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg,
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
