<script>
    var harga_event = 0
    var with_hotel = 0
    $(document).ready(function() {
        // fetch-categories
        $.ajax({
            url: '{{ route('fetch-categories') }}',
            type: 'GET',
            dataType: 'json',
            async: true,
            success: function(res) {
                $('#filter_events').empty()
                $('#filter_events').append(`
                    <option value="">Select Category</option>
                `)
                $.each(res.data, function(i, data) {
                    $('#filter_events').append(`
                        <option value="${data.name}">${data.name}</option>
                    `)
                })
            }
        })

        fetchAllEvent()

        function fetchAllEvent() {
            $.ajax({
                url: '{{ route('fetch-events') }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#list_events').empty()
                    if (res.data.length == 0) {
                        $('#list_events').append(`
                            <div class="col-md-12 my-2">
                                <div class="card shadow">
                                    <div class="card-body pt-2">
                                        <p class="card-title h5 d-block text-darker text-center">Belum ada event yang diselenggarakan tahun ini</p>
                                    </div>`)
                    }
                    $.each(res.data, function(i, card) {
                        var button_regis = '';

                        if (card.is_active == 1) {
                            button_regis =
                                `<a href="/detail-event/${card.id}/${card.name}" class="btn btn-primary float-right mt-2 event-register" type="button" data-event_id="${card.id}" data-category_event_id="${card.category_event_id}">Register</a>`;
                        }
                        $('#list_events').append(`
                            <div class="col-md-4 my-2">
                                <div class="card shadow">
                                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                        <a href="javascript:;" class="d-block">
                                            <img src="{{ asset('img/logos/IPDI/event/${card.image}') }}" class="img-fluid border-radius-lg" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="card-body pt-2">
                                        <span
                                            class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2">${card.category.name}</span>
                                        <a href="javascript:;" class="card-title h5 d-block text-darker">
                                            ${card.title}
                                        </a>
                                        <p class="card-description mb-2">
                                            ${card.description}
                                            <br/>
                                            <span class="text-grey"></span>
                                        </p>

                                    </div>

                                    <div class="card-footer">
                                        <div class="author d-flex justify-content-between">
                                            <div class="name ps-1">
                                                <span>Tanggal acara</span>
                                                <div class="stats">
                                                    <small>${moment(card.date).format('DD-MM-YYYY')} </small> -
                                                    <small>${moment(card.date_end).format('DD-MM-YYYY')} WIB</small>
                                                </div>
                                            </div>
                                            ${button_regis}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        `)
                    })

                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            })
        }

        $(document).on('click', '#btn_search_events', function() {
            let search_events = $('#search_events').val()
            let filter_events = $('#filter_events').val()
            $.ajax({
                url: '{{ route('fetch-events') }}',
                type: 'GET',
                data: {
                    search: search_events,
                    filter: filter_events
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#list_events').empty()
                    if (res.data.length == 0) {
                        $('#list_events').append(`
                            <div class="col-md-12 my-2">
                                <div class="card shadow">
                                    <div class="card-body pt-2">
                                        <p class="card-title h5 d-block text-darker text-center">Belum ada event yang diselenggarakan tahun ini</p>
                                    </div>`)
                    }
                    $.each(res.data, function(i, card) {
                        var button_regis = '';
                        if (card.is_active == 1) {
                            button_regis =
                                `<a href="/detail-event/${card.id}/${card.name}" class="btn btn-primary float-right mt-2 event-register" type="button" data-event_id="${card.id}" data-category_event_id="${card.category_event_id}">Register</a>`;
                        }
                        $('#list_events').append(`
                            <div class="col-md-4 my-2">
                                <div class="card shadow">
                                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                        <a href="javascript:;" class="d-block">
                                            <img src="{{ asset('img/logos/IPDI/event/${card.image}') }}" class="img-fluid border-radius-lg" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="card-body pt-2">
                                        <span
                                            class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2">${card.category.name}</span>
                                        <a href="javascript:;" class="card-title h5 d-block text-darker">
                                            ${card.title}
                                        </a>
                                        <p class="card-description mb-2">
                                            ${card.description}
                                            <br/>
                                            <span class="text-grey"></span>
                                        </p>

                                    </div>

                                    <div class="card-footer">
                                        <div class="author d-flex justify-content-between">
                                            <div class="name ps-1">
                                                <span>Tanggal acara</span>
                                                <div class="stats">
                                                    <small>${moment(card.date).format('DD-MM-YYYY')} </small> -
                                                    <small>${moment(card.date_end).format('DD-MM-YYYY')} WIB</small>
                                                </div>
                                            </div>
                                            ${button_regis}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        `)
                    })
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            })
        })

        $(document).on('change', '.data_event_type', function() {
            $('.data_event').show()
            let data = $('input[name=event_type]:checked').val()
            if (data == 'kolektif') {
                $('#form_data_peserta').show()
                $('#list_peserta').empty()
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('fetch-peserta') }}',
                    type: 'GET',
                    dataType: 'json',
                    async: true,
                    success: function(res) {
                        $.each(res.data, function(i, peserta) {
                            $('#list_peserta').append(`
                                <option value="${peserta.id}">${peserta.username +' - '+ peserta.nama_perusahaan}</option>
                            `)
                        })
                    }
                })
            } else if (data == 'mandiri') {
                $('#form_data_peserta').hide()
                $('#list_peserta').empty()
            }
        })

        $(document).on('click', '.data_jnns_type', function() {
            let data_harga = $(this).data('harga')
            $('#create_transaction_event').data('jnns_harga', data_harga);
        })

        $(document).on('change', '#check_event_hotel', function() {
            if ($('#check_event_hotel').prop('checked')) {
                let value = $('#check_event_hotel').val()
                let price = $(this).data('price')
                let hotel_name = $(this).data('hotel_name')
                var Rupiah = Intl.NumberFormat('en-ID', {
                    style: 'currency',
                    currency: 'idr',
                    maximumSignificantDigits: 3,
                });
                $('#total_room_hotel').empty()
                $('#total_room_hotel').append(`
                    ${hotel_name} - ${Rupiah.format(price)} <input type="number" class="form-control" name="create_room_hotel" id="create_room_hotel" data-price_hotel="${price}" placeholder="jumlah kamar" style="width: 50% !important;" required/>
                    <input type="number" class="form-control" name="create_total_night" id="create_total_night" data-price_hotel="${price}" placeholder="jumlah Malam" style="width: 50% !important;" required/>
                `)
            } else {
                $('#total_room_hotel').empty()
            }
        })

        $(document).on('change', '#create_room_hotel', function(e) {
            let price_hotel = $(this).data('price_hotel')
            let create_room_hotel = $('#create_room_hotel').val()
        })

        $(document).on('click', '#create_transaction_event', function(e) {
            e.preventDefault()
            let id = $(this).data('event_id')

            let event_harga = $(this).data('event_harga')
            let harga_hotel = $('input[name="check_event_hotel"]').data('price')
            let total_kamar_hotel = $('#create_room_hotel').val()
            let total_permalam = $('#create_total_night').val()
            let total_harga_hotel = 0;
            let grand_total_harga = 0;

            if (id == 6) {
                event_harga = $('input[name="jnns_type"]:checked').data('harga')
                // <input class="form-check-input data_jnns_type" type="radio" name="jnns_type"
                // event_harga = $('input[name=jnns_type]:checked').parent('.data_jnns_type').attr('data-harga')
                if ($('#check_event_hotel').prop('checked')) {
                    total_harga_hotel = (harga_hotel * total_kamar_hotel) * total_permalam
                    // grand_total_harga = event_harga + total_harga_hotel
                    with_hotel = 1
                } else {
                    // grand_total_harga = event_harga
                    with_hotel = 0
                }

            } else {
                // grand_total_harga = $('#price_event').data('price_data')
                // grand_total_harga = 'gratis'
                with_hotel = 0
            }
            let form_data = new FormData($('#form_create_data_event')[0])
            form_data.append('with_hotel', with_hotel);
            form_data.append('harga_hotel', harga_hotel);
            form_data.append('total_kamar_hotel', total_kamar_hotel);
            form_data.append('total_permalam', total_permalam);
            form_data.append('total_harga_hotel', total_harga_hotel);
            form_data.append('event_harga', event_harga);
            // let data_harga= $('input[name=event_type]:checked').parent('.data_jnns_type').attr('data-harga')
            // let data_harga= $('.data_jnns_type').attr('data-harga')
            // alert(grand_total_harga)
            $.ajax({
                url: '{{ route('create-transaction') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                success: function(res) {
                    $('#form_create_event').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                        footer: '<a href="{{ route('list-transaksi') }}" class="text-primary">Riwayat Transaksi</a>'
                    })
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            })
        })

        $("#list_peserta").select2({
            dropdownParent: $('#form_create_event'),
            multiple: true,
        });
    })
</script>
