<script>
    $(document).ready(function() {
        // var page = 1

        fetchEventJnns()

        function fetchEventJnns() {
            $.ajax({
                // url: '{{ route('event-jnns') }}' + '?page=' + page,
                url: '{{ route("event-jnns") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#list_jnns').empty()
                    $.each(res.data, function(i, card) {
                        $('#list_jnns').append(`
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
                                        <p class="card-description mb-4">
                                            ${card.description}
                                            <br/>
                                            <span class="text-grey"></span>
                                        </p>
                                        <div class="author justify-content-start">
                                            <div class="name ps-3">
                                                <span>Tanggal acara</span>
                                                <div class="stats">
                                                    <small>${moment(card.date).format('DD-MM-YYYY h:mm:ss')} WIB</small>
                                                </div>
                                            </div>
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

        // $(document).on('input', '#search', function() {
        $(document).on('click', '#btn_search', function() {
            // let search_value = $(this).val()
            let search_value = $('#search').val()
            $.ajax({
                url: '{{ route("event-jnns") }}',
                type: 'GET',
                data: {
                    search: search_value
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#list_jnns').empty()
                    $.each(res.data, function(i, card) {
                        $('#list_jnns').append(`
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
                                        <p class="card-description mb-4">
                                            ${card.description}
                                            <br/>
                                            <span class="text-grey"></span>
                                        </p>
                                        <div class="author justify-content-start">
                                            <div class="name ps-3">
                                                <span>Tanggal acara</span>
                                                <div class="stats">
                                                    <small>${moment(card.date).format('DD-MM-YYYY h:mm:ss')} WIB</small>
                                                </div>
                                            </div>
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
