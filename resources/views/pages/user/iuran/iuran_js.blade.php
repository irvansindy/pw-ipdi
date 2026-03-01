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
        // =====================================================================
        // DATATABLE - Riwayat Iuran
        // =====================================================================
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
                        const isProgress = item.iuran?.status === 'progress';
                        const iuranId = item.iuran?.id;

                        let actions = `
                            <button type="button"
                                data-iuran_id="${iuranId}"
                                class="btn btn-outline-info btn-sm mt-1 detail_iuran_transaction"
                                data-bs-toggle="modal"
                                data-bs-target="#form_detail_iuran">
                                <i class="fas fa-eye"></i> View
                            </button>`;

                        // Tombol Edit hanya tampil jika status masih 'progress'
                        if (isProgress) {
                            actions += `
                            <button type="button"
                                data-detail_id="${item.id}"
                                data-tahun_iuran="${item.tahun_iuran}"
                                data-biaya_20k="${item.biaya_regis_anggota_baru}"
                                data-biaya_50k="${item.biaya_iuran_tahunan}"
                                data-nama_peserta="${item.user?.username || '-'}"
                                class="btn btn-outline-warning btn-sm mt-1 btn_edit_detail_iuran"
                                data-bs-toggle="modal"
                                data-bs-target="#modal_edit_detail_iuran">
                                <i class="fas fa-edit"></i> Edit
                            </button>`;

                            // Tombol Delete hanya tampil jika user adalah pemilik transaksi (user_id === iuran.user_id)
                            // Data is_owner dikirim dari server
                            if (item.iuran?.is_owner) {
                                actions += `
                            <button type="button"
                                data-iuran_id="${iuranId}"
                                class="btn btn-outline-danger btn-sm mt-1 btn_delete_transaksi_iuran">
                                <i class="fas fa-trash"></i> Hapus
                            </button>`;
                            }
                        }

                        return `<div class="d-flex flex-wrap gap-1">${actions}</div>`;
                    }
                },
            ]
        });

        // =====================================================================
        // NAVIGASI: Tombol Buat Iuran Baru
        // =====================================================================
        $(document).on('click', '#create_iuran', function() {
            window.location.href = "{{ route('create-iuran-anggota') }}";
        });

        // =====================================================================
        // FORM CREATE: Helper UI
        // =====================================================================
        $(document).on('click', '.list_data_iuran', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#form_data_peserta').hide();
            $('#form_create_data_iuran')[0].reset();
            $('#create_iuran_id').val(id);
            $('#create_transaction_iuran').attr('data-iuran_id', id);
        });

        $("#tahun_iuran").keypress(function(e) {
            if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
        });

        $(document).on('change', '.data_iuran_type', function() {
            let data = $('input[name=iuran_type]:checked').val();
            if (data == 'kolektif') {
                $('#form_data_peserta').show();
                $('#list_peserta').empty();
                showLoader('#list_peserta');

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: '{{ route('fetch-peserta') }}',
                    type: 'GET',
                    dataType: 'json',
                    async: true,
                    timeout: 10000,
                    success: function(res) {
                        $('#list_peserta').empty();
                        if (res.data && res.data.length > 0) {
                            $('#list_peserta').append('<option value="">Pilih Peserta</option>');
                            $.each(res.data, function(i, peserta) {
                                $('#list_peserta').append(`
                                    <option value="${peserta.id}">${peserta.username} (${peserta.email})</option>
                                `);
                            });
                        } else {
                            $('#list_peserta').append('<option value="">Tidak ada data peserta</option>');
                        }
                    },
                    error: function() {
                        $('#list_peserta').empty();
                        $('#list_peserta').append('<option value="">Gagal memuat data peserta</option>');
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal memuat daftar peserta. Silahkan coba lagi.' });
                    }
                });
            } else if (data == 'mandiri') {
                $('#form_data_peserta').hide();
                $('#list_peserta').empty();
            }
        });

        // =====================================================================
        // GENERATE PAYMENT
        // =====================================================================
        $(document).on('click', '#generate_payment_iuran', function(e) {
            e.preventDefault();
            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                url: '{{ route('generate-payment-iuran') }}',
                data: new FormData($('#form_create_data_iuran')[0]),
                processData: false,
                contentType: false,
                cache: false,
                timeout: 10000,
                success: function(res) {
                    $('#grand_total_payment_iuran').empty();
                    let grand_total = res.data != null ? res.data : 0;
                    let formattedTotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(grand_total);

                    $('#grand_total_payment_iuran').append(`<h5>Total: <strong>${formattedTotal}</strong></h5>`);
                    $('#val_grand_total_payment_iuran').val(grand_total);
                    $('#button_action_payment_iuran').empty();
                    $('#button_action_payment_iuran').append(`
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary" id="create_transaction_iuran" data-iuran_id="">Submit</button>
                    `);
                    setButtonLoading(btn, false);
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    let errorMsg = 'Gagal menghitung total pembayaran. Silahkan coba lagi.';
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMsg = response.meta?.message || errorMsg;
                    } catch (e) {}
                    Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
                }
            });
        });

        // =====================================================================
        // STORE IURAN (Submit Transaksi Baru)
        // =====================================================================
        $(document).on('click', '#create_transaction_iuran', function(e) {
            e.preventDefault();

            let peserta = $('.nama_peserta_iuran_central_dialisis').length;
            let iuranType = $("input[name=iuran_type]:checked").val();
            let fileUpload = $('input[name="iuran_file_upload"]')[0].files.length;

            if (!iuranType) {
                return Swal.fire({ icon: 'warning', title: 'Warning!', text: 'Silahkan pilih tipe iuran (Mandiri atau Batch/Kolektif)' });
            }
            if (fileUpload === 0) {
                return Swal.fire({ icon: 'warning', title: 'Warning!', text: 'Silahkan upload bukti transfer' });
            }
            if (peserta === 0) {
                return Swal.fire({ icon: 'warning', title: 'Warning!', text: 'Silahkan pilih minimal 1 peserta' });
            }

            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.meta.message, confirmButtonText: 'OK' })
                        .then((result) => {
                            if (result.isConfirmed) window.location.href = "{{ route('iuran-anggota') }}";
                        });
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    let errorMsg = 'Gagal menyimpan data. Silahkan hubungi admin.';
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMsg = response.meta?.message || response.message || errorMsg;
                    } catch (e) {}
                    Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
                }
            });
        });

        // =====================================================================
        // UPLOAD BUKTI TRANSFER (dari card riwayat)
        // =====================================================================
        $(document).on('click', '.upload_iuran_transaction', function(e) {
            e.preventDefault();
            let iuran_id = $(this).data('iuran_id');
            $('#form_upload_bukti_iuran')[0].reset();
            $('#master_iuran_id').val(iuran_id);
            $('#upload_file_bukti_iuran').attr('data-iuran_id', iuran_id);
        });

        $(document).on('click', '#upload_file_bukti_iuran', function(e) {
            e.preventDefault();
            let data_upload = new FormData($('#form_upload_bukti_iuran')[0]);
            $.ajax({
                url: '{{ route('upload-iuran-peserta') }}',
                type: 'POST',
                data: data_upload,
                processData: false,
                contentType: false,
                cache: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                enctype: 'multipart/form-data',
            });
        });

        // =====================================================================
        // DETAIL TRANSAKSI IURAN
        // =====================================================================
        $(document).on('click', '.detail_iuran_transaction', function(e) {
            e.preventDefault();
            let iuran_id = $(this).data('iuran_id');

            showLoader('#list_detail_iuran_peserta');

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('detail-transaksi-iuran') }}',
                type: 'GET',
                data: { iuran_id: iuran_id },
                dataType: 'json',
                timeout: 10000,
                success: function(res) {
                    if (res.data) {
                        $('#list_detail_iuran_peserta').empty();
                        $('#source_attchment_iuran').prop('href', res.data.iuran_image_transfer);
                        $('#nomor_iuran').html(res.data.iuran_number);
                        $('#tahun_iuran').html(new Date(res.data.date).getFullYear());
                        $('#upload_file_iuran').attr('data-iuran_id', res.data.id);

                        let no_detail = 1;
                        if (res.data.transaction_detail && res.data.transaction_detail.length > 0) {
                            $.each(res.data.transaction_detail, function(i, data) {
                                $('#list_detail_iuran_peserta').append(`
                                <tr>
                                    <td>${no_detail++}</td>
                                    <td>${data.user?.username || '-'}</td>
                                    <td>Rp ${parseInt(data.biaya_regis_anggota_baru).toLocaleString('id-ID')}</td>
                                    <td>Rp ${parseInt(data.biaya_iuran_tahunan).toLocaleString('id-ID')}</td>
                                    <td>${data.tahun_iuran}</td>
                                </tr>
                                `);
                            });
                        } else {
                            $('#list_detail_iuran_peserta').append(`
                            <tr><td colspan="5" class="text-center">Tidak ada data peserta</td></tr>
                            `);
                        }
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'Data transaksi tidak ditemukan' });
                    }
                },
                error: function() {
                    $('#list_detail_iuran_peserta').empty();
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal memuat detail transaksi. Silahkan coba lagi.' });
                }
            });
        });

        // =====================================================================
        // UPLOAD FILE IURAN (dari modal detail)
        // =====================================================================
        $(document).on('click', '#upload_file_iuran', function(e) {
            e.preventDefault();

            let uploadFile = $('input[name="upload_iuran"]')[0].files;
            if (uploadFile.length === 0) {
                return Swal.fire({ icon: 'warning', title: 'Warning!', text: 'Silahkan pilih file terlebih dahulu' });
            }

            let form_data = new FormData();
            let id = $(this).data('iuran_id');
            form_data.append('master_iuran_id', id);
            form_data.append('file_iuran_data', uploadFile[0]);

            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('upload-iuran-peserta') }}',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 30000,
                success: function(res) {
                    setButtonLoading(btn, false);
                    $('#table_list_iuran').DataTable().ajax.reload();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.meta.message, confirmButtonText: 'OK' })
                        .then((result) => {
                            if (result.isConfirmed) {
                                let modal = bootstrap.Modal.getInstance(document.getElementById('form_detail_iuran'));
                                if (modal) modal.hide();
                            }
                        });
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    let errorMsg = 'Gagal upload file. Silahkan hubungi admin.';
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMsg = response.meta?.message || response.message || errorMsg;
                    } catch (e) {}
                    Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
                }
            });
        });

        // =====================================================================
        // HELPER: tampilkan / bersihkan error per field via span statis di blade
        // =====================================================================
        function showFieldErrors(errors, fieldMap) {
            clearFieldErrors(fieldMap);
            $.each(errors, function(field, messages) {
                if (fieldMap[field]) {
                    let { input, err } = fieldMap[field];
                    $(input).addClass('is-invalid');
                    $(err).text(messages[0]).removeClass('d-none');
                }
            });
        }

        function clearFieldErrors(fieldMap) {
            $.each(fieldMap, function(field, ids) {
                $(ids.input).removeClass('is-invalid');
                $(ids.err).text('').addClass('d-none');
            });
        }

        // Map field server → { input, err span } di modal edit detail
        const editDetailFieldMap = {
            'tahun_iuran':              { input: '#edit_detail_tahun_iuran', err: '#err_tahun_iuran' },
            'biaya_regis_anggota_baru': { input: '#edit_detail_biaya_20k',   err: '#err_biaya_20k'   },
            'biaya_iuran_tahunan':      { input: '#edit_detail_biaya_50k',   err: '#err_biaya_50k'   },
        };

        // =====================================================================
        // EDIT DETAIL IURAN
        // Hanya user pemilik detail (user_id match) yang melihat tombol ini.
        // Cek status 'progress' sudah dilakukan di server.
        // =====================================================================
        $(document).on('click', '.btn_edit_detail_iuran', function() {
            $('#edit_detail_id').val($(this).data('detail_id'));
            $('#edit_detail_nama_peserta').val($(this).data('nama_peserta'));
            $('#edit_detail_tahun_iuran').val($(this).data('tahun_iuran'));
            $('#edit_detail_biaya_20k').val($(this).data('biaya_20k'));
            $('#edit_detail_biaya_50k').val($(this).data('biaya_50k'));
        });

        // =====================================================================
        // CHECKBOX AUTO-FILL 20K
        // =====================================================================
        $(document).on('change', '#check_biaya_20k', function() {
            const input = $('#edit_detail_biaya_20k');
            if ($(this).is(':checked')) {
                input.val(20000).prop('readonly', true).addClass('bg-light').removeClass('is-invalid');
                $('#err_biaya_20k').text('').addClass('d-none');
            } else {
                input.val('0').prop('readonly', false).removeClass('bg-light');
            }
        });

        // =====================================================================
        // CHECKBOX AUTO-FILL 50K
        // =====================================================================
        $(document).on('change', '#check_biaya_50k', function() {
            const input = $('#edit_detail_biaya_50k');
            if ($(this).is(':checked')) {
                input.val(50000).prop('readonly', true).addClass('bg-light').removeClass('is-invalid');
                $('#err_biaya_50k').text('').addClass('d-none');
            } else {
                input.val('0').prop('readonly', false).removeClass('bg-light');
            }
        });

        // Reset state setiap modal dibuka
        $('#modal_edit_detail_iuran').on('show.bs.modal', function() {
            $('#check_biaya_20k').prop('checked', false);
            $('#check_biaya_50k').prop('checked', false);
            $('#edit_detail_biaya_20k').prop('readonly', false).removeClass('bg-light');
            $('#edit_detail_biaya_50k').prop('readonly', false).removeClass('bg-light');
            clearFieldErrors(editDetailFieldMap);
        });

        // Hapus error saat user mulai mengisi field
        $(document).on('input', '#edit_detail_tahun_iuran', function() {
            $(this).removeClass('is-invalid');
            $('#err_tahun_iuran').text('').addClass('d-none');
        });
        $(document).on('input', '#edit_detail_biaya_20k', function() {
            $(this).removeClass('is-invalid');
            $('#err_biaya_20k').text('').addClass('d-none');
        });
        $(document).on('input', '#edit_detail_biaya_50k', function() {
            $(this).removeClass('is-invalid');
            $('#err_biaya_50k').text('').addClass('d-none');
        });

        $(document).on('click', '#btn_save_edit_detail_iuran', function(e) {
            e.preventDefault();
            clearFieldErrors(editDetailFieldMap);

            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('user-edit-detail-iuran') }}',
                type: 'POST',
                data: {
                    detail_id:                $('#edit_detail_id').val(),
                    tahun_iuran:              $('#edit_detail_tahun_iuran').val(),
                    biaya_regis_anggota_baru: $('#edit_detail_biaya_20k').val(),
                    biaya_iuran_tahunan:      $('#edit_detail_biaya_50k').val(),
                },
                dataType: 'json',
                timeout: 10000,
                success: function(res) {
                    setButtonLoading(btn, false);
                    let modal = bootstrap.Modal.getInstance(document.getElementById('modal_edit_detail_iuran'));
                    if (modal) modal.hide();
                    $('#table_list_iuran').DataTable().ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.meta.message });
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (xhr.status === 422 && response.meta?.message && typeof response.meta.message === 'object') {
                            showFieldErrors(response.meta.message, editDetailFieldMap);
                            Swal.fire({ icon: 'warning', title: 'Periksa kembali!', text: 'Masih ada form/data yang kosong.' });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error!', text: response.meta?.message || 'Gagal memperbarui data.' });
                        }
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal memperbarui data.' });
                    }
                }
            });
        });

        // =====================================================================
        // DELETE TRANSAKSI IURAN
        // Hanya user pemilik IuranTransaction (user_id match) yang melihat tombol ini.
        // Cek status 'progress' sudah dilakukan di server.
        // =====================================================================
        $(document).on('click', '.btn_delete_transaksi_iuran', function() {
            let iuranId = $(this).data('iuran_id');

            Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus transaksi iuran ini? Seluruh data detail akan ikut terhapus.',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: '{{ route('user-delete-transaksi-iuran') }}',
                        type: 'DELETE',
                        data: { iuran_id: iuranId },
                        dataType: 'json',
                        timeout: 10000,
                        success: function(res) {
                            $('#table_list_iuran').DataTable().ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.meta.message });
                        },
                        error: function(xhr) {
                            let errorMsg = 'Gagal menghapus transaksi. Silahkan hubungi admin.';
                            try {
                                let response = JSON.parse(xhr.responseText);
                                errorMsg = response.meta?.message || response.message || errorMsg;
                            } catch (e) {}
                            Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
                        }
                    });
                }
            });
        });

        // =====================================================================
        // SELECT2 & APPEND PESERTA
        // =====================================================================
        $("#list_peserta").select2({
            dropdownParent: $('#form_create_iuran'),
            multiple: true,
        });

        $(document).on('click', '#button_append_iuran', function() {
            $('.array-list-peserta-iuran').append(`
                <div class="row cls_data_array_iuran">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Nama</label>
                            <input class="form-control" type="text" name="nama_peserta_iuran[]" placeholder="Nama">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Central Dialisis</label>
                            <select class="form-control central_dialisis_iuran" name="central_dialisis_iuran" placeholder="Central Dialisis"></select>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group">
                            <label>Uang Iuran 20K</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Uang Iuran 20K</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group">
                            <label>Uang Iuran 50K</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">Uang Iuran 50K</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger mt-4 button_pop_iuran">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.button_pop_iuran', function() {
            $(this).closest('.cls_data_array_iuran').remove();
        });
    });
</script>