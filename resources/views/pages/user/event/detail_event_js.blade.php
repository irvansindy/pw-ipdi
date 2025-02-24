<script>
    $(document).ready(function() {
        $(document).on('click', '#submit_regis_event', function(e) {
            e.preventDefault()
            // alert('submit_regis_event')
            let event_id = $('#data_event_id').data('event_id')
            let event_hotel = $('#data_event_id').data('event_hotel')
            let price = $('#data_event_price').data('price')
            let user_id = $('#user_id_for_event').val()
            let detail_event = document.getElementsByName('event_detail')[0] != null ? $('input[name="event_detail"]:checked').val() : null
            let total_room_hotel = document.getElementsByName('create_room_hotel')[0] != null ? $('#create_room_hotel').val() : null
            let total_room_night = document.getElementsByName('create_total_night')[0] != null ? $('#create_total_night').val() : null
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