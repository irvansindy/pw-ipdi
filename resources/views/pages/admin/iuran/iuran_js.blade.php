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

    $(document).ready(function() {

        // =====================================================================
        // DATATABLE
        // =====================================================================
        $('#iuran_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route('fetch-iuran') }}',
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
                    "data": "user.username",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "grand_total",
                    render: function(data, type, row) {
                        return 'Rp ' + parseInt(row.grand_total).toLocaleString('id-ID');
                    },
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "for_date",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "status",
                    "defaultContent": "<i>Not set</i>",
                    render: function(data) {
                        const map = {
                            'progress': ['warning', 'Menunggu'],
                            'verified': ['info',    'Terverifikasi'],
                            'success':  ['success', 'Disetujui'],
                        };
                        const [cls, label] = map[data] || ['secondary', data];
                        return `<span class="badge badge-sm bg-gradient-${cls}">${label}</span>`;
                    }
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return `
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button"
                                    data-iuran_id="${item.id}"
                                    class="btn btn-outline-info btn-sm mt-1 detail_iuran_data"
                                    data-bs-toggle="modal"
                                    data-bs-target="#approval_iuran">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button type="button"
                                    data-iuran_id="${item.id}"
                                    class="btn btn-outline-danger btn-sm mt-1 btn_delete_transaksi_iuran">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>`;
                    }
                },
            ]
        });

        // =====================================================================
        // CREATE IURAN
        // =====================================================================
        $(document).on('click', '#for_create_iuran_data', function(e) {
            e.preventDefault();
            $('#form_create_iuran')[0].reset();
        });

        $(document).on('click', '#create_data_iuran', function(e) {
            e.preventDefault();
            let data = {
                'name': $('#create_iuran_name').val(),
                'description': $('#create_iuran_desc').val(),
                'price': $('#create_iuran_price').val(),
                'year': $('#create_iuran_year').val()
            };

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('store-iuran') }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    Swal.fire({ icon: 'success', title: res.meta.message });
                    $('#formCreateIuran').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({ icon: 'error', title: errorMessage.meta.message, text: errorMessage.data?.message });
                }
            });
        });

        // =====================================================================
        // DETAIL / APPROVAL IURAN
        // =====================================================================
        $(document).on('click', '.detail_iuran_data', function(e) {
            e.preventDefault();
            let iuran_id = $(this).data('iuran_id');
            $('#form_approval_iuran')[0].reset();
            $('#button_approval_iuran').empty();

            $.ajax({
                url: '{{ route('detail-iuran') }}',
                type: 'GET',
                data: { id: iuran_id },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#master_iuran_id').val(res.data.id);
                    $('#data_list_user_iuran').empty();

                    let data_list_user_iuran = res.data.transaction_detail;
                    $.each(data_list_user_iuran, function(i, user) {
                        $('#data_list_user_iuran').append(`
                            <tr>
                                <td>${i + 1}</td>
                                <td>${user.user.username}</td>
                                <td>${user.user.nira_ipdi ?? 'Kosong'}</td>
                                <td>${user.user.dialisis ? user.user.dialisis.name : 'Kosong'}</td>
                                <td>${user.tahun_iuran}</td>
                                <td>
                                    <button type="button"
                                        class="btn btn-outline-warning btn-sm btn_edit_detail_iuran"
                                        data-detail_id="${user.id}"
                                        data-tahun_iuran="${user.tahun_iuran}"
                                        data-biaya_20k="${user.biaya_regis_anggota_baru}"
                                        data-biaya_50k="${user.biaya_iuran_tahunan}"
                                        data-nama_peserta="${user.user.username}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal_edit_detail_iuran">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    $('#bukti_transaksi_iuran').empty();
                    $('#bukti_transaksi_iuran').append(`
                        <a href="{{ asset('') }}${res.data.iuran_image_transfer}" target="_blank" class="text-primary">Bukti Transaksi</a>
                    `);

                    $('#button_approval_iuran').append(
                        `<button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>`
                    );

                    if (res.data.status === 'progress') {
                        $('#button_approval_iuran').append(
                            `<button type="button" class="btn bg-gradient-primary" id="approve_data_iuran">Approved</button>`
                        );
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Oops....', text: 'Data tidak ditemukan!' });
                }
            });
        });

        // =====================================================================
        // UPDATE IURAN (master)
        // =====================================================================
        $(document).on('click', '#update_data_iuran', function(e) {
            e.preventDefault();
            let data_detail = {
                'id': $(this).data('id'),
                'name': $('#detail_iuran_name').val(),
                'description': $('#detail_iuran_desc').val(),
                'price': $('#detail_iuran_price').val(),
                'year': $('#detail_iuran_year').val()
            };

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('update-iuran') }}',
                type: 'POST',
                data: data_detail,
                dataType: 'json',
                success: function(res) {
                    Swal.fire({ icon: 'success', title: res.meta.message });
                    $('#formDetailIuran').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({ icon: 'error', title: errorMessage.meta.message, text: errorMessage.data?.message });
                }
            });
        });

        // =====================================================================
        // APPROVE IURAN
        // =====================================================================
        $(document).on('click', '#approve_data_iuran', function(e) {
            e.preventDefault();
            let master_iuran_id = $('#master_iuran_id').val();
            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('approve-iuran') }}',
                type: 'POST',
                data: { 'master_iuran_id': master_iuran_id },
                dataType: 'json',
                success: function(res) {
                    setButtonLoading(btn, false);
                    Swal.fire({ icon: 'success', title: res.meta.message });
                    $('#approval_iuran').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({ icon: 'error', title: errorMessage.meta.message, text: errorMessage.data?.message });
                }
            });
        });

        // =====================================================================
        // HELPER: tampilkan / bersihkan error per field via span statis di blade
        // fieldMap: { 'nama_field_server': { input: '#id_input', err: '#id_span_error' } }
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
        // EDIT DETAIL IURAN — populate modal
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

        // =====================================================================
        // SIMPAN EDIT DETAIL IURAN
        // =====================================================================
        $(document).on('click', '#btn_save_edit_detail_iuran', function(e) {
            e.preventDefault();
            clearFieldErrors(editDetailFieldMap);

            let btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route('edit-detail-iuran') }}',
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
                    bootstrap.Modal.getInstance(document.getElementById('modal_edit_detail_iuran'))?.hide();
                    $('#iuran_management').DataTable().ajax.reload(null, false);
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
        // =====================================================================
        $(document).on('click', '.btn_delete_transaksi_iuran', function() {
            let iuran_id = $(this).data('iuran_id');

            Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus transaksi ini? Seluruh data detail akan ikut terhapus.',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        url: '{{ route('delete-transaksi-iuran') }}',
                        type: 'DELETE',
                        data: { iuran_id: iuran_id },
                        dataType: 'json',
                        timeout: 10000,
                        success: function(res) {
                            $('#iuran_management').DataTable().ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.meta.message });
                        },
                        error: function(xhr) {
                            let errorMsg = 'Gagal menghapus transaksi.';
                            try {
                                let response = JSON.parse(xhr.responseText);
                                errorMsg = response.meta?.message || errorMsg;
                            } catch (e) {}
                            Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
                        }
                    });
                }
            });
        });
    });
</script>