<script>
    $(document).ready(function() {
        fetchOrganization()

        function fetchOrganization() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-organization') }}',
                type: 'get',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#content_organization').empty()
                    $('.button-action-for-organization').empty()
                    if (res.data.length == 0) {
                        $('#content_organization').append(
                            `<p class="text-center">Belum ada video</p>`)
                    $('.button-action-for-organization').append(
                        `<button type="button" class="btn bg-gradient-primary ms-auto" id="add_video" data-bs-toggle="modal" data-bs-target="#add_modal_organization">+ Video</button>`
                    )
                    } else {
                        $('#content_organization').append(`
                        <video width="100%" controls>
                            <source src="{{ asset('uploads/video/`+res.data[0].video+`') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        `)
                        // <div class = "embed-responsive embed-responsive-16by9" >
                        //     <iframe class="h-50 embed-responsive-item" src = "https://www.youtube.com/embed/5fxmLXbwDgU"
                        //     title = "Video Organisasi"
                        //     style = "max-width: 100%;height: 100%;"
                        //     allowfullscreen></iframe>
                        // </div>
                    }
                }
            })
        }

        $(document).on('click', '#add_video', function(e) {
            e.preventDefault()
            $('#form_add_history')[0].reset()
        })

    })

    $('#form_add_history').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '{{ route("upload-video") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Logika sukses
            },
            error: function(xhr, status, error) {
                // Logika kesalahan
            }
        });
    })
</script>
