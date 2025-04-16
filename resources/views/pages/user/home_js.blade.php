<script>
    $(document).ready(function() {
        visiMisi()
        headLine()
        function visiMisi() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("visi-misi") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    console.log(res.data['visi'][0]);
                    $('#list_visi').empty()
                    $('#list_visi').append(`
                        <li>${res.data['visi'][0].content}</li>
                    `)
                    $('#list_misi').empty()
                    let misi = res.data['misi']
                    $.each(misi, function(i, data) {
                        // <li>${data.ordering + '. ' + data.content}</li>
                        $('#list_misi').append(`
                            <li>${data.content}</li>
                        `)
                    })
                }
            })
        }

        function headLine() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-headline") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    let events = res.data.events
                    let news = res.data.news
                    $('#data-event-headline').empty()
                    $('#data-news-headline').empty()

                    $.each(events, function(i, data) {
                        $('#data-event-headline').append(`
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <div class="card card-plain card-blog">
                                        <div class="card-image border-radius-lg position-relative">
                                            <a href="javascript:;">
                                                <div class="blur-shadow-image">
                                                    <img class="img border-radius-lg move-on-hover img-fluid"
                                                        src="{{ asset('img/logos/IPDI/event/${data.image}') }}"
                                                        style="height: 30% !important;" loading="lazy">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="card-body px-0">
                                            <h5>
                                                <a href="javascript:;" class="text-dark font-weight-bold">
                                                    ${data.title}
                                                </a>
                                            </h5>
                                            <p align="justify">
                                                ${data.description}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <a href="javascript:;">
                                            <div class="card move-on-hover mb-4">
                                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/logos/IPDI/event/${data.image}') }}" style="height: 10% !important;" loading="lazy">
                                            </div>
                                        </a>
                                        <a href="javascript:;">
                                            <div class="card move-on-hover mb-4">
                                                <img class="img img-fluid border-radius-lg move-on-hover img-fluid" src="{{ asset('img/logos/IPDI/event/${data.image}') }}" style="height: 10% !important;" loading="lazy">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `)
                    })
                    $.each(news, function(i, data) {
                        $('#data-news-headline').append(`
                            <div class="col-md-4 mb-3">
                                <div class="card card-body shadow-sm">
                                    <a href="/news/${data.id}"><h5>${data.title}</h5></a>
                                    <p>${data.created_at}</p>
                                </div>
                            </div>
                        `)
                    })
                }
            })
        }
    })
</script>