<script>
    $(document).ready(function() {
        // fetch-categories
        $.ajax({
            url: '{{ route("fetch-categories") }}',
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
                url: '{{ route("fetch-events") }}',
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
                                            <img src="{{ asset('img/logos/IPDI/LOGO_PW_IPDI_DKIJ.png') }}" class="img-fluid border-radius-lg">
                                        </a>
                                    </div>
                                    <div class="card-body pt-2">
                                        <span
                                            class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2">House</span>
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
                                            <button class="btn btn-primary float-right mt-2 event-register" type="button" data-event_id="${card.id}">
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
        }

        $(document).on('click', '#btn_search_events', function() {
            let search_events = $('#search_events').val()
            let filter_events = $('#filter_events').val()
            $.ajax({
                url: '{{ route("fetch-events") }}',
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
                                            <img src="{{ asset('img/logos/IPDI/LOGO_PW_IPDI_DKIJ.png') }}" class="img-fluid border-radius-lg">
                                        </a>
                                    </div>
                                    <div class="card-body pt-2">
                                        <span
                                            class="text-gradient text-primary text-uppercase text-xs font-weight-bold my-2">House</span>
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
                                            <button class="btn btn-primary float-right mt-2 event-register" type="button" data-event_id="${card.id}">
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
            $.ajax({
                url: '{{ route("check-transaction") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    event_id: event_id
                },
                dataType: 'json',
                async: true,
                success:function(res){
                    if (res.data[0] == null) {
                        $('#form_create_event').modal('show')
                        $('#name_event').html(res.data[1].name)
                        $('#title_event').html(res.data[1].title)
                        let price_event = res.data[1].price.replace(/(\d)(?=(\d{3})+\b)/g, '$1.');
                        $('#price_event').html('Rp.'+price_event)
                        $('#quota_event').html(res.data[1].quota+' Audien/Peserta')
                        $('#desc_event').html(res.data[1].description)
                        let date_event = moment(res.data[1].date).format('D-MM-YYYY, h:mm:ss')+' WIB'
                        $('#date_event').html(date_event)
                        let last_register = moment(res.data[1].date).format('D-MM-YYYY, h:mm:ss')+' WIB'
                        $('#last_register').html(last_register)

                        $('#create_transaction_event').attr('data-event_id', res.data[1].id);
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
                    // alert(response_error.meta.message)
                    Swal.fire({
                        icon: "warning",
                        title: 'Alert!',
                        text: response_error.meta.message,
                    })
                }
            })
        })

        $(document).on('click', '#create_transaction_event', function(e) {
            e.preventDefault()
            let id = $(this).data('event_id')
            $.ajax({
                
                url: '{{ route("create-transaction") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    // console.log(res.data[1][0].quota)
                    // if (res.data[0] < res.data[1][0].quota) {
                    //     alert('bisa join')
                    // }
                    $('#form_create_event').modal('hide')
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        text: res.meta.message,
                    })
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            })
        })
    })
</script>