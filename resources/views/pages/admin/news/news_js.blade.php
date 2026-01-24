<script>
    // Global loading overlay helper
    function showLoadingOverlay(title = 'Processing...') {
        if ($('#loading-overlay').length === 0) {
            $('body').append(`
                <div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0,0,0,0.5); z-index: 9999; display: none;">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-white fw-semibold">${title}</p>
                    </div>
                </div>
            `);
        }
        $('#loading-overlay').fadeIn(200);
    }

    function hideLoadingOverlay() {
        $('#loading-overlay').fadeOut(300, function() {
            $(this).remove();
        });
    }

    $(document).ready(function() {
        $('#news_content').summernote({
            height: 200,
            tabsize: 2,
        });
        $('.news_select2').select2({
            dropDownParent: $('#formNews')
        })

        // Initialize DataTable with loading indicator
        var newsTable = $('#news_management').DataTable({
            processing: false,
            ajax: {
                url: '{{ route('fetch-news-management') }}',
                type: 'GET',
                beforeSend: function() {
                    showLoadingOverlay('Loading News...');
                },
                dataSrc: function(json) {
                    hideLoadingOverlay();
                    return json.data;
                },
                error: function(xhr, status, error) {
                    hideLoadingOverlay();
                    console.error('DataTable Error:', error);
                }
            },
            columns: [{
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
                    "render": function(data) {
                        let badgeClass = 'bg-warning';
                        if (data === 'published') badgeClass = 'bg-success';
                        if (data === 'archived') badgeClass = 'bg-secondary';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    },
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "published_at",
                    "render": function(data) {
                        if (!data) return '<i>Not set</i>';
                        return new Date(data).toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    },
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    wrap: true,
                    "render": function(item) {
                        return `
                            <button type="button" data-news_id="${item.id}" data-news_title="${item.title}" class="btn btn-outline-info btn-sm mt-2 detail_news_data" data-bs-toggle="modal" data-bs-target="#formNews">
                                <i class="fas fa-eye"></i> View
                            </button>
                        `;
                    }
                },
            ]
        })

        $(document).on('click', '#for_create_news', function(e) {
            e.preventDefault()
            $('#form_news')[0].reset()
            $('#news_image').attr('required', true)
            $('#image_help_text').text('Image is required for new news')
            $('#NewsLabel').html('Create News')
            $('#link_news_image').empty()
            $('#button_approval_iuran').empty()
            $('#button_approval_iuran').append(`
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-primary" id="create_news">
                    <i class="fas fa-save"></i> Submit
                </button>
            `)
        })

        $(document).on('click', '#create_news', function(e) {
            e.preventDefault()

            // Validate image is present for create
            if (!$('#news_image')[0].files.length) {
                Swal.fire({
                    icon: "warning",
                    title: "Image Required",
                    text: "Please select an image to create news",
                });
                return;
            }

            let form_data = new FormData($('#form_news')[0])
            showLoadingOverlay('Creating News...')

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('store-news-management') }}',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(res) {
                    hideLoadingOverlay()
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                        timer: 2000
                    });
                    newsTable.ajax.reload();
                    $('#formNews').modal('hide');
                },
                error: function(xhr) {
                    hideLoadingOverlay()
                    let response_error = JSON.parse(xhr.responseText)
                    let errorMessage = response_error.meta.message;
                    if (response_error.meta.errors) {
                        errorMessage = Object.values(response_error.meta.errors).flat()
                            .join(', ');
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Failed to Create News",
                        text: errorMessage,
                    })
                },
                complete: function() {
                    hideLoadingOverlay()
                }
            })
        })

        $(document).on('click', '.detail_news_data', function(e) {
            e.preventDefault()
            let id = $(this).data('news_id')
            let title = $(this).data('news_title')
            $('#NewsLabel').html(title)
            $('#form_news')[0].reset()
            $('#news_image').removeAttr('required')
            $('#image_help_text').text('For update: Leave empty to keep the current image')

            showLoadingOverlay('Loading News Details...')

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
                    hideLoadingOverlay()
                    let detail_news = res.data
                    $('#form_news')[0].reset()
                    $('#news_id').val(detail_news.id)
                    $('#news_title').val(detail_news.title)
                    $('#news_content').summernote('code', detail_news.content)
                    $('#link_news_image').empty()

                    if (detail_news.image) {
                        let final_link = "{{ asset('storage/') }}" + '/' + detail_news.image
                        $('#link_news_image').append(
                            `<a href="${final_link}" target="_blank"><i class="fas fa-image"></i> View Image</a>`
                        )
                    }

                    $('#news_category').val(detail_news.category_id).trigger('change');
                    $('#news_source_url').val(detail_news.source_url)
                    $('#news_status').val(detail_news.status).trigger('change');
                    $('#news_published_at').val(detail_news.published_at)

                    $('#button_approval_iuran').empty()
                    $('#button_approval_iuran').append(`
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn bg-gradient-danger" id="delete_news">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <button type="button" class="btn bg-gradient-primary" id="update_news">
                            <i class="fas fa-edit"></i> Update
                        </button>
                    `)
                },
                error: function(xhr) {
                    hideLoadingOverlay()
                    $('#formNews').modal('hide');
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: response_error.meta.message,
                    })
                },
                complete: function() {
                    hideLoadingOverlay()
                }
            })
        })

        $(document).on('click', '#update_news', function(e) {
            e.preventDefault()
            let form_data = new FormData($('#form_news')[0])
            showLoadingOverlay('Updating News...')

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('update-news-management') }}',
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                success: function(res) {
                    hideLoadingOverlay()
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                        timer: 2000
                    });
                    newsTable.ajax.reload();
                    $('#formNews').modal('hide');
                },
                error: function(xhr) {
                    hideLoadingOverlay()
                    let response_error = JSON.parse(xhr.responseText)
                    let errorMessage = response_error.meta.message;
                    if (response_error.meta.errors) {
                        errorMessage = Object.values(response_error.meta.errors).flat()
                            .join(', ');
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Failed to Update News",
                        text: errorMessage,
                    })
                },
                complete: function() {
                    hideLoadingOverlay()
                }
            })
        })

        $(document).on('click', '#delete_news', function(e) {
            e.preventDefault()
            let news_id = $('#news_id').val()

            Swal.fire({
                title: 'Delete News?',
                text: "Are you sure you want to delete this news? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingOverlay('Deleting News...')

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('delete-news-management') }}',
                        type: 'DELETE',
                        data: {
                            news_id: news_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            hideLoadingOverlay()
                            Swal.fire({
                                icon: "success",
                                title: res.meta.message,
                                timer: 2000
                            });
                            newsTable.ajax.reload();
                            $('#formNews').modal('hide');
                        },
                        error: function(xhr) {
                            hideLoadingOverlay()
                            let response_error = JSON.parse(xhr.responseText)
                            Swal.fire({
                                icon: "error",
                                title: "Failed to Delete News",
                                text: response_error.meta.message,
                            })
                        },
                        complete: function() {
                            hideLoadingOverlay()
                        }
                    })
                }
            })
        })
    })
</script>
