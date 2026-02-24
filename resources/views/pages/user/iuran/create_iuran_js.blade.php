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
            $(button).html($(button).data('original-html') || 'Payment');
        }
    }

    function showLoader(containerId) {
        $(containerId).html(`
            <div class="d-flex justify-content-center align-items-center" style="min-height: 150px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
    }

    $(document).ready(function() {
        // <button type="button" class="btn btn-primary btn-sm ms-auto">Save</button>
        $('#button_action_payment_iuran').empty()
        $('#button_action_payment_iuran').append(`
        <div class="ms-auto">
            <a href="{{ route('iuran-anggota') }}" class="btn btn-danger btn-sm ms-auto mx-1">Cancel</a>
            <button type="button" class="btn bg-gradient-dark btn-sm ms-auto" id="generate_payment_iuran" data-iuran_id="">Payment</button>
        </div>
    `)

        function updateIdIuran() {
            $('.cls_data_array_iuran').each(function(i) {
                const index = i + 1
                const id_select_nama_iuran = 'nama_peserta_iuran_central_dialisis_' + index
                const id_iuran_20k = 'iuran_20k_' + index
                const id_iuran_50k = 'iuran_50k_' + index

                $(this).find('select[name="nama_peserta_iuran_central_dialisis"]').attr('id',
                    id_select_nama_iuran);
                $(this).find('input[name="iuran_20k[]"]').attr('id', id_iuran_20k);
                $(this).find('input[name="iuran_50k[]"]').attr('id', id_iuran_50k);

                // Update label 'for' attributes to match the input IDs
                $(this).find('label.label_iuran_20k').attr('for', id_iuran_20k);
                $(this).find('label.label_iuran_50k').attr('for', id_iuran_50k);
            })
        }

        $(document).on('change', '#jumlah_peserta_iuran', function() {
            let jumlah_peserta_iuran = $('#jumlah_peserta_iuran').val()
            if (jumlah_peserta_iuran <= 0) {
                $('.array-list-peserta-iuran').empty()
                $('.array-list-peserta-iuran').append(
                    `<p class="justify-content-center">Belum ada data</p>`)

            } else {
                $('.array-list-peserta-iuran').empty()
                showLoader('.array-list-peserta-iuran');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    url: '{{ route('fetch-peserta') }}',
                    timeout: 10000,
                    success: function(res) {
                        $('.array-list-peserta-iuran').empty();

                        if (!res.data || res.data.length === 0) {
                            $('.array-list-peserta-iuran').append(
                                `<p class="text-danger">Tidak ada data peserta tersedia</p>`
                            );
                            return;
                        }

                        let peserta_iuran = res.data;
                        for (let i = 0; i < jumlah_peserta_iuran; i++) {
                            let pesertaOptions = '<option value="">Pilih Peserta</option>';
                            peserta_iuran.forEach(p => {
                                pesertaOptions +=
                                    `<option value="${p.id}">${p.username} (${p.center_dialisis})</option>`;
                            });

                            $('.array-list-peserta-iuran').append(`
                            <div class="row cls_data_array_iuran">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_peserta_iuran_central_dialisis_${i}" class="form-control-label">Nama Peserta</label>
                                        <select class="form-control nama_peserta_iuran_central_dialisis" name="peserta[]" id="nama_peserta_iuran_central_dialisis_${i}" style="width: 100%" required>
                                            ${pesertaOptions}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="iuran_20k_${i}">Regis 20K</label>
                                        <div class="form-check">
                                            <input class="form-check-input iuran_20k" type="checkbox" value="20" name="iuran_20k[]" id="iuran_20k_${i}">
                                            <label class="form-check-label" for="iuran_20k_${i}">
                                                Ya
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="iuran_50k_${i}">Iuran 50K</label>
                                        <div class="form-check">
                                            <input class="form-check-input iuran_50k" type="checkbox" value="50" name="iuran_50k[]" id="iuran_50k_${i}">
                                            <label class="form-check-label" for="iuran_50k_${i}">
                                                Ya
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tahun_pembayaran_iuran_${i}">Tahun</label>
                                        <input type="number" class="form-control" name="tahun_pembayaran_iuran[]" id="tahun_pembayaran_iuran_${i}" value="${new Date().getFullYear()}">
                                    </div>
                                </div>
                            </div>
                        `);
                        }

                        $('.nama_peserta_iuran_central_dialisis').select2();
                        updateIdIuran();
                    },
                    error: function(xhr, status, error) {
                        $('.array-list-peserta-iuran').empty();
                        $('.array-list-peserta-iuran').append(`
                        <div class="alert alert-danger">
                            <strong>Error!</strong> Gagal memuat data peserta. Silahkan coba lagi.
                        </div>
                    `);
                        console.error('Error:', error);
                    }
                })
            }
        })
        // <button type="button" class="btn btn-danger mt-4 button_pop_iuran" id=""><i class="fa-solid fa-minus"></i></button>

        $(document).on('click', '#generate_payment_iuran', function(e) {
            e.preventDefault()

            let btn = $(this);

            // Validasi form
            let iuranType = $("input[name=iuran_type]:checked").val();
            let pesertaCount = $('.nama_peserta_iuran_central_dialisis').length;
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

            if (pesertaCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan isi data peserta minimal 1 orang',
                });
                return;
            }

            setButtonLoading(btn, true);

            let form_data = new FormData($('#form_create_data_iuran')[0])

            form_data.delete('peserta[]');
            form_data.delete('iuran_20k[]');
            form_data.delete('iuran_50k[]');

            // Iterate through each select option
            $('.nama_peserta_iuran_central_dialisis').each(function(i) {
                let value = $(this).val()
                if (value) {
                    form_data.append('peserta[]', value);
                }
            })
            // Iterate through each checkbox with the class `iuran_20k`
            $('.iuran_20k').each(function(i) {
                let value = $(this).is(':checked') ? 20000 : 0;
                form_data.append('iuran_20k[]', value);
            });
            // Iterate through each checkbox with the class `iuran_50k`
            $('.iuran_50k').each(function(i) {
                let value = $(this).is(':checked') ? 50000 : 0;
                form_data.append('iuran_50k[]', value);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route('generate-payment-iuran') }}',
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 10000,
                success: function(res) {
                    $('.for-grand-total-payment-iuran').empty()
                    let grand_total = res.data != null ? res.data : 0

                    let formattedTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(grand_total);

                    $('.for-grand-total-payment-iuran').append(
                        `<h5 class="text-info"><strong>Total Bayar: ${formattedTotal}</strong></h5>`
                    )
                    $('#val_grand_total_payment_iuran').val(grand_total)
                    $('#button_action_payment_iuran').empty()
                    $('#button_action_payment_iuran').append(`
                        <div class="ms-auto">
                            <a href="{{ route('iuran-anggota') }}" class="btn btn-danger btn-sm ms-auto mx-1">Cancel</a>
                            <button type="button" class="btn bg-gradient-primary btn-sm ms-auto" id="create_transaction_iuran">Submit</button>
                        </div>
                    `)

                    setButtonLoading(btn, false);
                },
                error: function(xhr, status, error) {
                    setButtonLoading(btn, false);

                    let errorMsg = 'Gagal menghitung total pembayaran. Silahkan coba lagi.';
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
                    text: 'Silahkan isi data peserta minimal 1 orang',
                });
                return;
            }

            let btn = $(this);
            setButtonLoading(btn, true);

            let form_data = new FormData($('#form_create_data_iuran')[0])

            form_data.delete('peserta[]');
            form_data.delete('iuran_20k[]');
            form_data.delete('iuran_50k[]');

            // Iterate through each select option
            $('.nama_peserta_iuran_central_dialisis').each(function(i) {
                let value = $(this).val()
                if (value) {
                    form_data.append('peserta[]', value);
                }
            })
            // Iterate through each checkbox with the class `iuran_20k`
            $('.iuran_20k').each(function(i) {
                let value = $(this).is(':checked') ? 20000 : 0;
                form_data.append('iuran_20k[]', value);
            });
            // Iterate through each checkbox with the class `iuran_50k`
            $('.iuran_50k').each(function(i) {
                let value = $(this).is(':checked') ? 50000 : 0;
                form_data.append('iuran_50k[]', value);
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store-iuran-peserta') }}',
                type: 'POST',
                data: form_data,
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

        $('.nama_peserta_iuran_central_dialisis').select2()
    })
</script>
