<script>
    $(document).ready(function() {
        let fileIndex = 0;
        const maxFiles = 5;

        // DataTable initialization
        $('#transaction_list').DataTable({
            processing: true,
            ajax: {
                url: '{{ route('histori-transaksi') }}',
                type: 'GET',
            },
            columns: [{
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

                            if (item.transactions.status === 'pending') {
                                return `
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button>
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-success btn-sm mt-2 upload_transaction" data-bs-toggle="modal" data-bs-target="#uploadTransaction">Upload</button>
                                    <button type="button" data-transaction_id="${transId}" data-transaction_number="${item.transactions.number}" class="btn btn-outline-danger btn-sm mt-2 delete_transaction">Delete</button>
                                `;
                            } else if (item.transactions.status === 'progress') {
                                return `
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button>
                                `;
                            } else if (item.transactions.status === 'success') {
                                return `
                                    <button type="button" data-transaction_id="${transId}" class="btn btn-outline-info btn-sm mt-2 detail_transaction" data-bs-toggle="modal" data-bs-target="#detailTransaction">View</button>
                                    <a href="/generate-ticket-qrcode?transnumber=${transNumber}" class="btn btn-outline-danger btn-sm mt-2">Qr Code</a>
                                `;
                            }
                        } else {
                            return '';
                        }
                    }
                },
            ]
        });

        // Refresh transaction
        $(document).on('click', '#refresh_transaction', function(e) {
            e.preventDefault();
            $('#transaction_list').DataTable().ajax.reload();
        });

        // ========== DYNAMIC UPLOAD FORM ==========

        // Event handler untuk tombol upload transaction
        $(document).on('click', '.upload_transaction', function(e) {
            e.preventDefault();

            // Reset form
            $('#form_upload_file')[0].reset();
            $('#file_upload_container').html('');
            fileIndex = 0;

            // Add first file upload field
            addFileUploadField();

            let transaction_id = $(this).data('transaction_id');
            $('#file_transaction_id').val(transaction_id);
        });

        // Function untuk add file upload field
        function addFileUploadField() {
            fileIndex++;

            const newFileItem = `
                <div class="file-upload-item mb-3 fade-in" data-index="${fileIndex}">
                    <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
                        <div class="d-flex align-items-center">
                            <span class="file-number-badge text-white me-3">
                                #<span class="file-number">${fileIndex}</span>
                            </span>
                            <h6 class="mb-0 text-dark">
                                <i class="fas fa-file-upload text-primary me-2"></i>Upload File
                            </h6>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-file-btn" ${fileIndex === 1 ? 'style="display: none;"' : ''}>
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-paperclip text-primary me-2"></i>Pilih File
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-lg file-input" name="files[]"
                                       accept=".png,.jpg,.jpeg,.pdf" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Format: <strong>PNG, JPG, JPEG, PDF</strong> | Maksimal: <strong>2MB</strong>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-edit text-primary me-2"></i>Keterangan File
                                    <span class="text-muted">(Nama Vendor)</span>
                                </label>
                                <textarea class="form-control description-textarea" name="descriptions[]" rows="3"
                                          placeholder="Contoh: Bukti transfer BCA tanggal 20 Desember 2024"></textarea>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb text-warning"></i>
                                    Tambahkan keterangan untuk memudahkan identifikasi file
                                </div>
                            </div>

                            <!-- Preview file name -->
                            <div class="col-12">
                                <div class="file-preview" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success fs-5 me-3"></i>
                                        <div>
                                            <strong class="text-success">File Siap Diupload:</strong>
                                            <div class="preview-filename text-dark mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#file_upload_container').append(newFileItem);
            updateFileNumbers();
            toggleAddButton();
            updateFileCounter();
        }

        // Event handler untuk add more file button
        $(document).on('click', '#add_more_file', function(e) {
            e.preventDefault();

            const currentFileCount = $('.file-upload-item').length;

            if (currentFileCount >= maxFiles) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    text: `Anda hanya dapat mengupload maksimal ${maxFiles} file sekaligus`,
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            addFileUploadField();
        });

        // Event handler untuk remove file button
        $(document).on('click', '.remove-file-btn', function(e) {
            e.preventDefault();

            const fileItem = $(this).closest('.file-upload-item');

            Swal.fire({
                title: 'Hapus File?',
                text: 'Anda yakin ingin menghapus file ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fileItem.addClass('remove-animation');
                    setTimeout(() => {
                        fileItem.remove();
                        updateFileNumbers();
                        toggleAddButton();
                        updateFileCounter();
                    }, 300);
                }
            });
        });

        // Event handler untuk file input change (preview)
        $(document).on('change', '.file-input', function() {
            const fileItem = $(this).closest('.file-upload-item');
            const file = this.files[0];
            const preview = fileItem.find('.file-preview');
            const previewFilename = fileItem.find('.preview-filename');

            if (file) {
                const fileSizeKB = file.size / 1024;
                const fileSizeMB = fileSizeKB / 1024;

                if (fileSizeMB > 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: `Ukuran file ${file.name} adalah ${fileSizeMB.toFixed(2)}MB. Maksimal 2MB per file.`,
                        confirmButtonColor: '#d33'
                    });
                    $(this).val('');
                    preview.hide();
                    return;
                }

                const fileSize = fileSizeMB > 1 ?
                    fileSizeMB.toFixed(2) + ' MB' :
                    fileSizeKB.toFixed(2) + ' KB';

                previewFilename.text(`${file.name} (${fileSize})`);
                preview.fadeIn();
            } else {
                preview.hide();
            }
        });

        // Function untuk update file numbers
        function updateFileNumbers() {
            $('.file-upload-item').each(function(index) {
                $(this).find('.file-number').text(index + 1);
            });

            const fileCount = $('.file-upload-item').length;
            if (fileCount === 1) {
                $('.file-upload-item').first().find('.remove-file-btn').hide();
            } else {
                $('.file-upload-item').first().find('.remove-file-btn').show();
            }
        }

        // Function untuk toggle add button
        function toggleAddButton() {
            const currentFileCount = $('.file-upload-item').length;
            if (currentFileCount >= maxFiles) {
                $('#add_more_file').prop('disabled', true).addClass('disabled');
                $('#add_more_file span:first').text('Maksimal 5 File Tercapai');
            } else {
                $('#add_more_file').prop('disabled', false).removeClass('disabled');
                $('#add_more_file span:first').text('Tambah File Lainnya');
            }
        }

        // Function untuk update file counter
        function updateFileCounter() {
            const currentFileCount = $('.file-upload-item').length;
            $('#file_counter').text(`${currentFileCount}/${maxFiles}`);
        }

        // Event handler untuk submit upload
        $(document).on('submit', '#form_upload_file', function(e) {
            e.preventDefault();

            const fileInputs = $('input[name="files[]"]');
            let hasFile = false;

            fileInputs.each(function() {
                if (this.files && this.files.length > 0) {
                    hasFile = true;
                    return false;
                }
            });

            if (!hasFile) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silahkan pilih minimal 1 file untuk diupload',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            let form_data = new FormData(this);
            let trans_id = $('#file_transaction_id').val();
            form_data.append('id', trans_id);

            Swal.fire({
                title: 'Mengupload File...',
                html: '<div class="progress mt-3"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ route('upload-transaction') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                success: function(res) {
                    $('#transaction_list').DataTable().ajax.reload();
                    $('#uploadTransaction').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <p>${res.meta.message}</p>
                            <p class="mt-2">
                                <i class="fas fa-check-circle text-success"></i>
                                ${res.data.uploaded_files.length} file berhasil diupload
                            </p>
                        `,
                        confirmButtonColor: '#3085d6',
                        timer: 3000
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Gagal upload file. Silahkan coba lagi.';

                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (response.meta && response.meta.message) {
                            errorMessage = response.meta.message;
                        } else if (response.data) {
                            let errors = [];
                            Object.values(response.data).forEach(function(error) {
                                if (Array.isArray(error)) {
                                    errors = errors.concat(error);
                                }
                            });
                            errorMessage = errors.join('<br>');
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Upload',
                        html: errorMessage,
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });

        // ========== DELETE TRANSACTION ==========

        $(document).on('click', '.delete_transaction', function(e) {
            e.preventDefault();

            let transaction_id = $(this).data('transaction_id');
            let transaction_number = $(this).data('transaction_number');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `Anda akan menghapus transaksi:<br><strong>${transaction_number}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('delete-transaksi') }}',
                        type: 'DELETE',
                        data: {
                            transaction_id: transaction_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('#transaction_list').DataTable().ajax.reload();

                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: res.meta.message ||
                                    'Transaksi berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            let response_error = JSON.parse(xhr.responseText);

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response_error.meta?.message ||
                                    'Gagal menghapus transaksi',
                            });
                        }
                    });
                }
            });
        });

        // ========== DETAIL TRANSACTION ==========

        $(document).on('click', '.detail_transaction', function(e) {
            e.preventDefault();
            let transaction_id = $(this).data('transaction_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('detail-transaksi') }}',
                type: 'GET',
                data: {
                    transaction_id: transaction_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_id').html(res.data.number);
                    $('#detail_username').html(res.data.user.username);
                    $('#detail_event').html(res.data.event.name);

                    // Jika event_detail_id ada, gunakan start_date dari event_detail
                    // Jika event_detail_id kosong, gunakan date dari event
                    if (res.data.event_detail_id) {
                        $('#detail_date').html(moment(res.data.event_detail.start_date)
                            .format('ll') + ' (07.00 - 16.30 WIB)' + ' s/d ' + moment(
                                res.data.event_detail.end_date).format('ll') +
                            ' (07.00 - 16.30 WIB)');
                    } else {
                        $('#detail_date').html(moment(res.data.event.date).format('ll') +
                            ' (07.00 - 16.30 WIB)');
                    }

                    $('#detail_status').html(res.data.status);
                    $('#detail_price').html('Rp.' + res.data.price);

                    if (res.data.with_hotel == true) {
                        $('.is_with_hotel').empty();
                        $('.is_with_hotel').append(`
                            <div class="col-sm-6 col-sm-6 col-6">
                                <span style="font-weight: 700 !important;">Harga Hotel</span>
                                <p id="">Rp.${res.data.harga_hotel}</p>
                            </div>
                        `);
                        $('.is_with_hotel').append(`
                            <div class="col-sm-6 col-sm-6 col-6">
                                <span style="font-weight: 700 !important;">Jumlah Kamar Hotel</span>
                                <p id="">${res.data.total_room + ` Room`}</p>
                            </div>
                        `);
                    } else {
                        $('.is_with_hotel').empty();
                    }

                    $('.is_payment').empty();
                    if (res.data.proof_of_payment != null) {
                        let link_proof_of_payment = '/uploads/bukti_transaksi/' + res.data
                            .proof_of_payment;
                        $('.is_payment').append(`
                            <div class="col-sm-6 col-sm-6 col-6">
                                <span style="font-weight: 700 !important;">Link bukti pembayaran</span>
                                <br/>
                                <a href="${link_proof_of_payment}" target="_blank" style="font-size: 12px;">klik disini</a>
                            </div>
                        `);
                    }

                    let numbering_user = 1;
                    $('#list_data_pererta').empty();
                    $.each(res.data.transaction_detail, function(i, peserta) {
                        $('#list_data_pererta').append(`
                            <tr>
                                <td>${numbering_user++}</td>
                                <td>${peserta.user.username}</td>
                            </tr>
                        `);
                    });
                }
            });
        });
    });
</script>
