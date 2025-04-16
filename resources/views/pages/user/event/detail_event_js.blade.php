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

        // Saat event detail dipilih
        $('.event-detail-radio').on('change', function() {
            detailPrice = parseInt($(this).data('price')) || 0;
            updateTotal();
        });

        // Saat hotel checkbox diubah
        $('#event_hotel').on('change', function() {
            alert('Hotel checkbox changed')
            if ($(this).is(':checked')) {
                calculateHotelPrice(); // kalkulasi awal saat dicentang
            } else {
                hotelPrice = 0;
                updateTotal();
            }
        });

        // Kalkulasi ulang kalau jumlah kamar / malam diubah
        $('#create_room_hotel, #create_total_night').on('input', function() {
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
        $(document).on('click', '#submit_regis_event', function(e) {
            e.preventDefault()
            // alert('submit_regis_event')
            let event_id = $('#data_event_id').data('event_id')
            let event_hotel = $('#data_event_id').data('event_hotel')
            let price = $('#data_event_price').data('price')
            let user_id = $('#user_id_for_event').val()
            let detail_event = document.getElementsByName('event_detail')[0] != null ? $(
                'input[name="event_detail"]:checked').val() : null
            let total_room_hotel = document.getElementsByName('create_room_hotel')[0] != null ? $(
                '#create_room_hotel').val() : null
            let total_room_night = document.getElementsByName('create_total_night')[0] != null ? $(
                '#create_total_night').val() : null
            let is_hotel_checked = $('#event_hotel').prop('checked') ? 1 : 0

            let data = {
                id: event_id,
                price: price,
                user_id: user_id,
                detail_event: detail_event,
                event_hotel: event_hotel,
                is_hotel_checked: is_hotel_checked,
                total_room_hotel: total_room_hotel,
                total_room_night: total_room_night
            }
            console.log(data);

            $.ajax({
                url: '{{ route('create-transaction') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                async: true,
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                        footer: '<a href="{{ route('list-transaksi') }}" class="text-primary">Riwayat Transaksi</a>'
                    })
                    // let link = "{{ route('list-transaksi') }}"
                    // window.location.href = link
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                }
            })
        })
    })
</script>
