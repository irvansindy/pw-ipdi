<script>
    // =========================================================================
    // HELPERS
    // =========================================================================
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

    // Cache daftar peserta agar tidak fetch ulang tiap tambah row
    let cachedPeserta = null;

    // Buat satu row form peserta
    // isKolektif: true  → tampilkan tombol minus (hapus row)
    // isKolektif: false → sembunyikan tombol minus (mandiri selalu 1 row)
    function buildRowPeserta(index, pesertaList, isKolektif) {
        let options = '<option value="">Pilih Peserta</option>';
        pesertaList.forEach(p => {
            options +=
                `<option value="${p.id}">${p.username} (${p.dialisis ? p.dialisis.name : 'center dialisis belum diisi'})</option>`;
        });

        // generate year options (current year down to -5)
        let yearOptions = '';
        const currentYear = new Date().getFullYear();
        for (let y = currentYear; y >= currentYear - 5; y--) {
            yearOptions += `<option value="${y}" ${y === currentYear ? 'selected' : ''}>${y}</option>`;
        }

        const minusBtn = isKolektif ?
            `<div class="col-md-auto d-flex align-items-end">
                    <button type="button" class="btn btn-danger mb-1 btn_hapus_peserta">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>` :
            '';

        return `
        <div class="row g-2 align-items-end cls_data_array_iuran mb-2" data-index="${index}">
            <div class="col-md-5">
                <div class="form-group">
                    <label class="form-control-label">Nama Peserta</label>
                    <select class="form-control nama_peserta_iuran_central_dialisis" name="peserta[]" style="width:100%" required>
                        ${options}
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label d-block">Regis 20K</label>
                    <div class="form-check mt-1">
                        <input class="form-check-input iuran_20k" type="checkbox" value="20" name="iuran_20k[]">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label d-block">Iuran 50K</label>
                    <div class="form-check mt-1">
                        <input class="form-check-input iuran_50k" type="checkbox" value="50" name="iuran_50k[]">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label">Tahun</label>
                    <select class="form-control tahun_pembayaran_iuran" name="tahun_pembayaran_iuran[${index}][]" multiple required>
                        ${yearOptions}
                    </select>
                </div>
            </div>
            ${minusBtn}
        </div>`;
    }

    // Inisialisasi select2 pada row terbaru saja
    function initSelect2LastRow() {
        $('.cls_data_array_iuran:last .nama_peserta_iuran_central_dialisis').select2({
            width: '100%'
        });
        $('.cls_data_array_iuran:last .tahun_pembayaran_iuran').select2({
            width: '100%',
            placeholder: 'Pilih tahun',
            allowClear: true
        });
    }

    // Fetch peserta (gunakan cache jika sudah ada)
    function fetchPeserta(callback) {
        if (cachedPeserta) {
            callback(cachedPeserta);
            return;
        }
        showLoader('.array-list-peserta-iuran');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route('fetch-peserta') }}',
            timeout: 10000,
            success: function(res) {
                if (!res.data || res.data.length === 0) {
                    $('.array-list-peserta-iuran').html(
                        `<p class="text-danger">Tidak ada data peserta tersedia.</p>`);
                    return;
                }
                cachedPeserta = res.data;
                callback(cachedPeserta);
            },
            error: function() {
                $('.array-list-peserta-iuran').html(`
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Gagal memuat data peserta. Silahkan coba lagi.
                    </div>`);
            }
        });
    }

    $(document).ready(function() {

        // Tombol aksi default
        $('#button_action_payment_iuran').html(`
            <div class="ms-auto">
                <a href="{{ route('iuran-anggota') }}" class="btn btn-danger btn-sm mx-1">Cancel</a>
                <button type="button" class="btn bg-gradient-dark btn-sm" id="generate_payment_iuran">Payment</button>
            </div>
        `);

        // =====================================================================
        // PILIH TIPE IURAN — render row awal secara dinamis
        // =====================================================================
        $(document).on('change', '.data_iuran_type', function() {
            const tipe = $('input[name=iuran_type]:checked').val();

            // Reset area
            $('.array-list-peserta-iuran').empty();
            $('.for-grand-total-payment-iuran').empty();
            cachedPeserta = null; // reset cache agar fresh

            if (tipe === 'mandiri') {
                $('#wrapper_btn_tambah').hide();
                $('#section_peserta').show();

                fetchPeserta(function(list) {
                    $('.array-list-peserta-iuran').html(buildRowPeserta(0, list, false));
                    initSelect2LastRow();
                });

            } else if (tipe === 'kolektif') {
                $('#wrapper_btn_tambah').show();
                $('#section_peserta').show();

                fetchPeserta(function(list) {
                    $('.array-list-peserta-iuran').html(buildRowPeserta(0, list, true));
                    initSelect2LastRow();
                });
            }
        });

        // =====================================================================
        // TAMBAH ROW PESERTA (kolektif saja)
        // =====================================================================
        $(document).on('click', '#btn_tambah_peserta', function() {
            if (!cachedPeserta) return;

            const index = $('.cls_data_array_iuran').length;
            $('.array-list-peserta-iuran').append(buildRowPeserta(index, cachedPeserta, true));
            initSelect2LastRow();
        });

        // =====================================================================
        // HAPUS ROW PESERTA
        // =====================================================================
        $(document).on('click', '.btn_hapus_peserta', function() {
            // Minimal selalu ada 1 row
            if ($('.cls_data_array_iuran').length <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Minimal harus ada 1 peserta.'
                });
                return;
            }
            $(this).closest('.cls_data_array_iuran').remove();
        });

        // =====================================================================
        // GENERATE PAYMENT (hitung total)
        // =====================================================================
        $(document).on('click', '#generate_payment_iuran', function(e) {
            e.preventDefault();

            const iuranType = $('input[name=iuran_type]:checked').val();
            const pesertaCount = $('.nama_peserta_iuran_central_dialisis').length;
            const fileUpload = $('input[name="iuran_file_upload"]')[0].files.length;

            if (!iuranType) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan pilih tipe iuran (Mandiri atau Batch/Kolektif).'
                });
            }
            if (fileUpload === 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan upload bukti transfer.'
                });
            }
            if (pesertaCount === 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan isi data peserta minimal 1 orang.'
                });
            }

            let btn = $(this);
            setButtonLoading(btn, true);

            let form_data = new FormData($('#form_create_data_iuran')[0]);
            form_data.delete('iuran_20k[]');
            form_data.delete('iuran_50k[]');
            $('.iuran_20k').each(function() {
                form_data.append('iuran_20k[]', $(this).is(':checked') ? 20000 : 0);
            });
            $('.iuran_50k').each(function() {
                form_data.append('iuran_50k[]', $(this).is(':checked') ? 50000 : 0);
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
                    setButtonLoading(btn, false);
                    const grand_total = res.data ?? 0;
                    const formattedTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(grand_total);

                    $('.for-grand-total-payment-iuran').html(
                        `<h5 class="text-info"><strong>Total Bayar: ${formattedTotal}</strong></h5>`
                    );
                    $('#val_grand_total_payment_iuran').val(grand_total);
                    $('#button_action_payment_iuran').html(`
                        <div class="ms-auto">
                            <a href="{{ route('iuran-anggota') }}" class="btn btn-danger btn-sm mx-1">Cancel</a>
                            <button type="button" class="btn bg-gradient-primary btn-sm" id="create_transaction_iuran">Submit</button>
                        </div>
                    `);
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    let errorMsg = 'Gagal menghitung total pembayaran. Silahkan coba lagi.';
                    try {
                        const r = JSON.parse(xhr.responseText);
                        errorMsg = r.meta?.message || r.message || errorMsg;
                    } catch (e) {}
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg
                    });
                }
            });
        });

        // =====================================================================
        // SUBMIT TRANSAKSI
        // =====================================================================
        $(document).on('click', '#create_transaction_iuran', function(e) {
            e.preventDefault();

            const iuranType = $('input[name=iuran_type]:checked').val();
            const pesertaCount = $('.nama_peserta_iuran_central_dialisis').length;
            const fileUpload = $('input[name="iuran_file_upload"]')[0].files.length;

            if (!iuranType) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan pilih tipe iuran.'
                });
            }
            if (fileUpload === 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan upload bukti transfer.'
                });
            }
            if (pesertaCount === 0) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Silahkan isi data peserta minimal 1 orang.'
                });
            }

            let btn = $(this);
            setButtonLoading(btn, true);

            let form_data = new FormData($('#form_create_data_iuran')[0]);
            form_data.delete('peserta[]');
            form_data.delete('iuran_20k[]');
            form_data.delete('iuran_50k[]');

            $('.nama_peserta_iuran_central_dialisis').each(function() {
                const val = $(this).val();
                if (val) form_data.append('peserta[]', val);
            });
            $('.iuran_20k').each(function() {
                form_data.append('iuran_20k[]', $(this).is(':checked') ? 20000 : 0);
            });
            $('.iuran_50k').each(function() {
                form_data.append('iuran_50k[]', $(this).is(':checked') ? 50000 : 0);
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
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.meta.message,
                            confirmButtonText: 'OK'
                        })
                        .then(result => {
                            if (result.isConfirmed) window.location.href =
                                "{{ route('iuran-anggota') }}";
                        });
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    let errorMsg = 'Gagal menyimpan data. Silahkan hubungi admin.';
                    try {
                        const r = JSON.parse(xhr.responseText);
                        errorMsg = r.meta?.message || r.message || errorMsg;
                    } catch (e) {}
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg
                    });
                }
            });
        });

    });
</script>
