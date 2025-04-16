<script>
    $(document).ready(function() {
        $('#news_content').summernote({
            height: 200,
            tabsize: 2,
        });
        $('.news_select2').select2({
            dropDownParent: $('#formCreateNews')
        })
        $('#news_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("fetch-news-management") }}',
                type: 'GET',
            },
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "title",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "status",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "published_at",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-news_id="' +item.id +'" data-news_title="' +item.title +'" class="btn btn-outline-info btn-sm mt-2 detail_news_data" data-bs-toggle="modal" data-bs-target="#formNews">View</button>'
                    }
                },
            ]
        })

        $(document).on('click', '#for_create_news', function(e) {
            e.preventDefault()
            $('#form_news')[0].reset()
            $('#NewsLabel').html('Create News')
            $('#link_news_image').empty()
            $('#button_approval_iuran').empty()
            $('#button_approval_iuran').append(`
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-primary" id="create_news">Submit</button>
            `)
        })

        $(document).on('click', '#create_news', function(e) {
            e.preventDefault()
            let form_data = new FormData($('#form_news')[0])
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("store-news-management") }}',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $('.data_tables').DataTable().ajax.reload();
                    $('#formNews').modal('hide');
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: response_error.meta.message,
                    })
                }
            })
        })

        $(document).on('click', '.detail_news_data', function(e) {
            e.preventDefault()
            let id = $(this).data('news_id')
            let title = $(this).data('news_title')
            $('#NewsLabel').html(title)
            $('#form_news')[0].reset()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('detail-news-management') }}',
                method: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    let detail_news = res.data
                    $('#form_news')[0].reset()
                    $('#news_id').val(detail_news.id)
                    $('#news_title').val(detail_news.title)
                    $('#news_content').summernote('code', detail_news.content)
                    $('#link_news_image').empty()
                    let final_link = "{{ asset('storage/') }}" + '/' +detail_news.image
                    
                    detail_news.image != null ? $('#link_news_image').append(`<a href="${final_link}" target="_blank">Link Image</a>`) : $('#link_news_image').empty()
                    // $('#news_category').val(detail_news.category_id)
                    $('#news_category').val(detail_news.category_id).trigger('change');
                    $('#news_source_url').val(detail_news.source_url)
                    $('#news_status').val(detail_news.status).trigger('change');
                    $('#news_published_at').val(detail_news.published_at)
                    $('#button_approval_iuran').empty()
                    $('#button_approval_iuran').append(`
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn bg-gradient-primary" id="update_news">Update</button>
                    `)
                },
                error: function(xhr) {
                    $('#formNews').modal('hide');
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: response_error.meta.message,
                    })
                }
            })
        })

        $(document).on('click', '#update_news', function(e) {
            e.preventDefault()
            let form_data = new FormData($('#form_news')[0])
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("update-news-management") }}',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $('.data_tables').DataTable().ajax.reload();
                    $('#formNews').modal('hide');
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: response_error.meta.message,
                    })
                }
            })
        })
    })
</script>