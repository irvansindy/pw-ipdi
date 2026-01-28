<script>
    $(document).ready(function() {
        fetchNews()
        function fetchNews() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('fetch-berita') }}",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    let news = res.data;
                    $(".latest-news-carousel").empty();
                    $.each(news, (i, data) => {
                        let image = data.image != null ? `{{ asset('storage/${data.image}') }}` : 'https://dummyimage.com/300x280/bcbcd6/000000';
                        $(".latest-news-carousel").append(`
                            <div class="latest-news-item">
                                <div class="bg-light rounded">
                                    <div class="rounded-top px-2 py-1">
                                        <img src="${image}" class="img-zoomin img-fluid rounded-top w-100" alt="">
                                    </div>
                                    <div class="d-flex flex-column p-4">
                                        <a href="#" class="h4">${data.title}</a>
                                        <div class="d-flex justify-content-between">
                                            <a href="#" class="small text-body link-hover">${data.author.username}</a>
                                            <small class="text-body d-block">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                ${moment(data.published_at).format('DD MMMM YYYY')}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                    $(".latest-news-carousel").owlCarousel({
                        autoplay: true,
                        smartSpeed: 2000,
                        center: false,
                        dots: true,
                        loop: true,
                        margin: 25,
                        nav : true,
                        navText : [
                            '<i class="fa-solid fa-arrow-left text-primary"></i>',
                            '<i class="fa-solid fa-arrow-right text-primary"></i>',
                        ],
                        responsiveClass: true,
                        responsive: {
                            0:{
                                items:1
                            },
                            576:{
                                items:1
                            },
                            768:{
                                items:2
                            },
                            992:{
                                items:3
                            },
                            1200:{
                                items:4
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching news:", error);
                }
            });
        }


    });
</script>