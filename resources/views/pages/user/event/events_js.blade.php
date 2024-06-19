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
                    $.each(res.data, function(i, card) {
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
                                    
                                    <div class="card-footer" style="display: ${card.category_event_id != 1 ? 'none' : ''}">
                                        <div class="author d-flex justify-content-between">
                                            <div class="name ps-1">
                                                <span>Tanggal acara</span>
                                                <div class="stats">
                                                    <small>${moment(card.date).format('DD-MM-YYYY')} </small> - 
                                                    <small>${moment(card.date_end).format('DD-MM-YYYY')} WIB</small>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary float-right mt-2 event-register" type="button" data-event_id="${card.id}" data-category_event_id="${card.category_event_id}">
                                                Register
                                            </button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `)
                        // if (card.category_event_id != 1) {
                        //     $('.event-register').hide()
                        //     $('.card-footer').hide()
                        // }
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
                    $.each(res.data, function(i, card) {
                        $('#list_events').append(`
                            <div class="col-md-4 my-2">
                                <div class="card shadow">
                                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                        <a href="javascript:;" class="d-block">
                                            <img src="{{ asset('img/logos/IPDI/LOGO_PW_IPDI_DKIJ.png') }}" class="img-fluid border-radius-lg" loading="lazy">
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
                                                    <small>${moment(card.date).format('DD-MM-YYYY h:mm:ss')} WIB</small>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary float-right mt-2 event-register" type="button" data-event_id="${card.id}"data-category_event_id="${card.category_event_id}">
                                                Register
                                            </button> 
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

        $(document).on('click', '.event-register', function(e) {
            e.preventDefault()
            let event_id = $(this).data('event_id')
            let category_id = $(this).data('category_event_id')
            $('#form_data_peserta').hide()
            $('#form_create_data_event')[0].reset()
            // alert(category_id)
            $.ajax({
                url: '{{ route('check-transaction') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    event_id: event_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    // if (res.data[2].address == null || res.data[2].city == null || res.data[2].country == null || res.data[2].postal == null) {
                    //     Swal.fire({
                    //         icon: "warning",
                    //         title: 'Alert!',
                    //         text: 'Silahkan lengkapi biodata terlebih dahulu!.',
                    //         showCancelButton: false,
                    //         showConfirmButton: false,
                    //         footer: '<a href="{{ route('profile') }}" class="text-primary">Klik link di bawah ini</a>'
                    //     })
                    // } else {
                    // }
                    $('.list_type_jnns').empty()
                    $('.event_hotel').empty()
                    harga_event = res.data[1].price
                    // alert(category_id)
                    if (category_id == 1) {
                        $('#price_event').hide()
                        $('.price_event').hide()
                        $('.list_type_jnns').empty()
                        if (res.data[1].event_detail.length != 0) {
                            $('.list_type_jnns').append(`<p>Pilihan :</p>`)
                            $.each(res.data[1].event_detail, function(i, detail) {
                                $('.list_type_jnns').append(`
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input data_jnns_type" type="radio" name="jnns_type"
                                        id="jnns_${detail.name}" value="${detail.id}" data-harga="${detail.price}">
                                    <label class="form-check-label" for="jnns_${detail.name}">${detail.name+' - '+detail.price}</label>
                                </div>
                                `)
                            })
                        }
                    } else {
                        $('#price_event').show()
                        $('.price_event').show()
                    }
                    $('.data_event').hide()
                    if (res.data[0] == null) {
                        $('#form_create_event').modal('show')
                        $('#event_id').val(res.data[1].id)
                        $('#category_id').val(res.data[1].category_event_id)
                        $('#name_event').html(res.data[1].name)
                        $('#title_event').html(res.data[1].title)

                        let price_event = res.data[1].price.replace(/(\d)(?=(\d{3})+\b)/g,
                            '$1.');
                        $('#price_event').html('Rp.' + price_event)
                        $('#price_event').attr('data-price_data', res.data[1].price)

                        $('#quota_event').html(res.data[1].quota + ' Audien/Peserta')
                        if (res.data[1].hotel != null) {
                            $('.event_hotel').empty()
                            $('.event_hotel').append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${res.data[1].hotel.id}" data-hotel_name="${res.data[1].hotel.name}" data-price="${res.data[1].hotel.price}" name="check_event_hotel" id="check_event_hotel">
                                <label class="form-check-label" for="check_event_hotel">
                                    Hotel
                                </label>
                            </div>
                            <div id="total_room_hotel"></div>
                            `)
                        }
                        // $('#desc_event').html(res.data[1].description)
                        let date_event = moment(res.data[1].date).format('D-MM-YYYY') +
                            ' WIB'
                        $('#date_event').html(date_event)
                        let last_register = moment(res.data[1].last_register).format(
                            'D-MM-YYYY') + ' WIB'
                        $('#last_register').html(last_register)
                        $('#create_transaction_event').data('event_id', res.data[1].id);
                        $('#create_transaction_event').data('event_harga', res.data[1]
                            .price);
                        let bank_data = res.data[3][0].name + '(' + res.data[3][0]
                            .account_number + ')' + ' a/n ' + res.data[3][0].account_name
                        $('#bank_account_number').html(bank_data)
                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: 'Alert!',
                            text: 'Kamu sudah terdaftar sebagai peserta!.',
                        })
                    }
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    // alert(response_error.message)
                    if (response_error.message == 'Unauthenticated.') {
                        Swal.fire({
                            icon: "warning",
                            title: 'Alert!',
                            text: 'Silahkan login dahulu.',
                        })
                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: 'Alert!',
                            text: response_error.meta.message,
                        })

                    }
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
                $('#total_room_hotel').empty()
                $('#total_room_hotel').append(`
                    ${hotel_name} - Rp.${price} <input type="number" class="form-control" name="create_room_hotel" id="create_room_hotel" data-price_hotel="${price}" placeholder="jumlah kamar" style="width: 50% !important;"/> 
                    <input type="number" class="form-control" name="create_total_night" id="create_total_night" data-price_hotel="${price}" placeholder="jumlah Malam" style="width: 50% !important;"/> 
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
            // alert(id)
            let event_harga = $(this).data('event_harga')
            let harga_hotel = $('input[name="check_event_hotel"]').data('price')
            let total_kamar_hotel = $('#create_room_hotel').val()
            let total_permalam = $('#create_total_night').val()
            let total_harga_hotel = 0;
            let grand_total_harga = 0;

            if (id == 1) {
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
