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
                                            <button class="btn btn-primary float-right" type="button">
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
                                            <button class="btn btn-primary float-right" type="button">
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
    })
</script>