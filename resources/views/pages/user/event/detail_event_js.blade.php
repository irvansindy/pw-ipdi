<script>
    $(document).ready(function() {
        let basePrice = parseInt($('#data_event_price').data('price')) || 0;
        let detailPrice = 0;
        let hotelPrice = 0;

        // Update tampilan harga
        function updateTotal() {
            const total = basePrice + detailPrice + hotelPrice;
            $('#data_event_price')
                .text('Rp.' + total.toLocaleString('id-ID'))
                .data('price', total);
        }

        // Saat event detail dipilih - hapus error highlight
        $('.event-detail-radio').on('change', function() {
            detailPrice = parseInt($(this).data('price')) || 0;
            updateTotal();

            // Hapus error styling jika sudah dipilih
            $(this).closest('.list-group-item').removeClass('border-danger');
        });

        // Saat hotel checkbox diubah
        $('#event_hotel').on('change', function() {
            if ($(this).is(':checked')) {
                calculateHotelPrice(); // kalkulasi awal saat dicentang
            } else {
                hotelPrice = 0;
                $('#create_room_hotel').val('');
                $('#create_total_night').val('');
                // Hapus error styling
                $('#create_room_hotel, #create_total_night').removeClass('is-invalid');
                updateTotal();
            }
        });

        // Kalkulasi ulang kalau jumlah kamar / malam diubah
        $('#create_room_hotel, #create_total_night').on('input', function() {
            // Hapus error styling saat user mulai input
            $(this).removeClass('is-invalid');

            if ($('#event_hotel').is(':checked')) {
                calculateHotelPrice();
            }
        });

        function calculateHotelPrice() {
            const pricePerNight = parseInt($('#event_hotel').data('price')) || 0;
            const rooms = parseInt($('#create_room_hotel').val()) || 0;
            const nights = parseInt($('#create_total_night').val()) || 0;

            hotelPrice = pricePerNight * rooms * nights;
            updateTotal();
        }

        // Validasi sebelum submit
        function validateForm() {
            let errors = [];
            let isValid = true;

            // Reset semua error styling
            $('.event-detail-radio').closest('.list-group-item').removeClass('border-danger');
            $('#create_room_hotel, #create_total_night').removeClass('is-invalid');

            // Validasi event detail untuk kategori JNNS (jika ada radio button)
            if ($('.event-detail-radio').length > 0) {
                if (!$('input[name="event_detail"]:checked').val()) {
                    errors.push('Silahkan pilih salah satu detail event (Simposium/Workshop/Paket)');
                    // Tambahkan border merah pada list item
                    $('.event-detail-radio').closest('.list-group-item').addClass('border-danger');
                    isValid = false;
                }
            }

            // Validasi hotel jika dicentang
            if ($('#event_hotel').length > 0 && $('#event_hotel').is(':checked')) {
                const rooms = parseInt($('#create_room_hotel').val());
                const nights = parseInt($('#create_total_night').val());

                if (!rooms || rooms <= 0) {
                    errors.push('Jumlah kamar hotel harus diisi dengan benar (minimal 1 kamar)');
                    $('#create_room_hotel').addClass('is-invalid');
                    isValid = false;
                }
                if (!nights || nights <= 0) {
                    errors.push('Jumlah malam hotel harus diisi dengan benar (minimal 1 malam)');
                    $('#create_total_night').addClass('is-invalid');
                    isValid = false;
                }
            }

            return {
                isValid: isValid,
                errors: errors
            };
        }

        $(document).on('click', '#submit_regis_event', function(e) {
            e.preventDefault();

            // Validasi form
            const validation = validateForm();

            if (!validation.isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    html: '<div style="text-align: left;">' +
                          validation.errors.map(err => '• ' + err).join('<br>') +
                          '</div>',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });

                // Scroll ke element pertama yang error
                if ($('.border-danger').length > 0) {
                    $('html, body').animate({
                        scrollTop: $('.border-danger').first().offset().top - 100
                    }, 500);
                } else if ($('.is-invalid').length > 0) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 500);
                }

                return;
            }

            let event_id = $('#data_event_id').data('event_id');
            let event_hotel = $('#data_event_id').data('event_hotel');
            let price = $('#data_event_price').data('price');
            let user_id = $('#user_id_for_event').val();
            let detail_event = $('input[name="event_detail"]:checked').val() || null;
            let total_room_hotel = $('#create_room_hotel').val() || 0;
            let total_room_night = $('#create_total_night').val() || 0;
            let is_hotel_checked = $('#event_hotel').prop('checked') ? 1 : 0;

            // Get detail event name untuk konfirmasi
            let detailEventName = '';
            if (detail_event) {
                detailEventName = $('input[name="event_detail"]:checked').next('label').text();
            }

            // Konfirmasi sebelum submit
            let confirmMessage = `
                <div style="text-align: left; padding: 10px;">
                    <p style="margin-bottom: 8px;"><strong>Event:</strong> ${$('#data_event_id').text()}</p>
                    ${detail_event ? '<p style="margin-bottom: 8px;"><strong>Detail:</strong> ' + detailEventName + '</p>' : ''}
                    ${is_hotel_checked ? '<p style="margin-bottom: 8px;"><strong>Hotel:</strong> ' + total_room_hotel + ' kamar, ' + total_room_night + ' malam</p>' : ''}
                    <hr style="margin: 12px 0;">
                    <p style="margin-bottom: 0; font-size: 18px;"><strong>Total Harga:</strong> <span style="color: #e74c3c;">Rp.${price.toLocaleString('id-ID')}</span></p>
                </div>
            `;

            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                html: confirmMessage,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Daftar!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    let data = {
                        id: event_id,
                        price: price,
                        user_id: user_id,
                        detail_event: detail_event,
                        event_hotel: event_hotel,
                        is_hotel_checked: is_hotel_checked,
                        total_room_hotel: total_room_hotel,
                        total_room_night: total_room_night
                    };

                    return $.ajax({
                        url: '{{ route('create-transaction') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        dataType: 'json'
                    }).then(response => {
                        return response;
                    }).catch(error => {
                        let errorMessage = 'Terjadi kesalahan saat mendaftar';
                        try {
                            if (error.responseJSON && error.responseJSON.meta) {
                                errorMessage = error.responseJSON.meta.message;
                            } else if (error.responseText) {
                                let response = JSON.parse(error.responseText);
                                errorMessage = response.meta?.message || errorMessage;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                        Swal.showValidationMessage(`<i class="fas fa-exclamation-circle"></i> ${errorMessage}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Swal.fire({
                        icon: "success",
                        title: 'Pendaftaran Berhasil!',
                        html: `
                            <div style="text-align: left; padding: 10px;">
                                <p>${result.value.meta.message}</p>
                                <hr style="margin: 15px 0;">
                                <p style="margin-bottom: 8px;"><strong>Nomor Transaksi:</strong></p>
                                <p style="font-size: 16px; color: #e74c3c; font-weight: bold; background: #f8f9fa; padding: 10px; border-radius: 5px; text-align: center;">${result.value.data.number}</p>
                                <p style="margin-top: 15px; font-size: 13px; color: #7f8c8d;">Silahkan upload bukti transfer di halaman riwayat transaksi untuk konfirmasi pendaftaran Anda.</p>
                            </div>
                        `,
                        confirmButtonText: '<i class="fas fa-history"></i> Lihat Riwayat',
                        showCancelButton: true,
                        cancelButtonText: '<i class="fas fa-home"></i> Kembali ke Home',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#95a5a6',
                        reverseButtons: true
                    }).then((finalResult) => {
                        if (finalResult.isConfirmed) {
                            window.location.href = "{{ route('list-transaksi') }}";
                        } else {
                            window.location.href = "/";
                        }
                    });
                }
            });
        });
    });
</script>

<style>
    /* Custom styling untuk error validation */
    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .border-danger {
        border: 2px solid #dc3545 !important;
        animation: shake 0.5s;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
</style>