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
            $('#NewsLabel').html('Create News')
            $('#link_news_image').empty()

            // Initialize file upload container for creating new news
            $('#news_file_upload_container').html('');
            $('#existing_images_container').html('');
            $('#news_images_display').hide();
            newsFileIndex = 0;
            addNewsFileUploadField();

            $('#button_approval_iuran').empty()
            $('#button_approval_iuran').append(`
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn bg-gradient-primary" id="create_news">
                    <i class="fas fa-save"></i> Submit
                </button>
            `)
        })

        // Form submission is now handled by the #form_news submit handler below

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

                    // Initialize file upload container for editing
                    $('#news_file_upload_container').html('');
                    newsFileIndex = 0;
                    addNewsFileUploadField();

                    // Load existing images
                    loadExistingNewsImages(detail_news.id);

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
                        <button type="submit" form="form_news" class="btn bg-gradient-primary">
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

        // Update is now handled by the #form_news submit handler

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

    // ========== MULTI-UPLOAD IMAGES LOGIC ==========
    let newsFileIndex = 0;
    const newsMaxFiles = 5;

    // Function untuk load existing images
    function loadExistingNewsImages(newsId) {
        $.ajax({
            url: '{{ route('detail-news-management') }}',
            type: 'GET',
            data: {
                id: newsId
            },
            success: function(res) {
                let news = res.data;
                let existingCount = news.images ? news.images.length : 0;

                // Update counter
                $('#news_file_counter').text(`${existingCount}/${newsMaxFiles}`);

                // Display existing images
                let existingHtml = '';
                if (news.images && news.images.length > 0) {
                    news.images.forEach(function(image) {
                        existingHtml += `
                            <div class="col-md-3 mb-2">
                                <div class="position-relative">
                                    <img src="{{ asset('') }}${image.file_path}" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete_existing_news_image" data-image_id="${image.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">${image.file_name}</small>
                            </div>
                        `;
                    });
                    $('#existing_images_container').html(existingHtml);
                    $('#news_images_display').show();
                }
            }
        });
    }

    // Function untuk add file upload field
    function addNewsFileUploadField() {
        newsFileIndex++;

        const newFileItem = `
            <div class="file-upload-item mb-3 fade-in" data-index="${newsFileIndex}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="file-number-badge">File <span class="file-number">${newsFileIndex}</span></span>
                    <button type="button" class="btn btn-danger btn-sm remove-file-btn" style="display: none;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <input type="file" class="form-control file-input" name="news_files[]" accept="image/*">
                <div class="file-preview" style="display: none;">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <span class="preview-filename"></span>
                </div>
            </div>
        `;

        $('#news_file_upload_container').append(newFileItem);
        updateNewsFileNumbers();
        toggleNewsAddButton();
        updateNewsFileCounter();
    }

    // Event handler untuk add more file button
    $(document).on('click', '#add_more_news_file', function(e) {
        e.preventDefault();

        const currentFileCount = $('.file-upload-item').length;

        if (currentFileCount >= newsMaxFiles) {
            Swal.fire({
                icon: "warning",
                title: "Batas Gambar Tercapai",
                text: `Maksimal ${newsMaxFiles} gambar per news`,
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        addNewsFileUploadField();
    });

    // Event handler untuk remove file button
    $(document).on('click', '.remove-file-btn', function(e) {
        e.preventDefault();

        const fileItem = $(this).closest('.file-upload-item');

        Swal.fire({
            title: 'Hapus Gambar?',
            text: 'Anda yakin ingin menghapus gambar ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fileItem.addClass('remove-animation');
                setTimeout(() => {
                    fileItem.remove();
                    updateNewsFileNumbers();
                    toggleNewsAddButton();
                    updateNewsFileCounter();
                }, 300);
            }
        });
    });

    // Event handler untuk delete existing image
    $(document).on('click', '.delete_existing_news_image', function(e) {
        e.preventDefault();
        let imageId = $(this).data('image_id');

        Swal.fire({
            title: 'Hapus Gambar?',
            text: 'Anda yakin ingin menghapus gambar ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('delete-news-image') }}',
                    type: 'POST',
                    data: {
                        image_id: imageId
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: "success",
                            title: res.meta.message,
                            timer: 1500
                        });
                        // Reload existing images
                        let newsId = $('#news_id').val();
                        loadExistingNewsImages(newsId);
                    },
                    error: function(xhr) {
                        let response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Gagal Menghapus Gambar",
                            text: response.meta.message
                        });
                    }
                });
            }
        });
    });

    // Event handler untuk file input change (preview)
    $(document).on('change', '.file-input', function() {
        const fileItem = $(this).closest('.file-upload-item');
        const file = this.files[0];
        const preview = fileItem.find('.file-preview');
        const previewFilename = fileItem.find('.preview-filename');

        if (file) {
            const maxSize = 5120 * 1024; // 5MB
            if (file.size > maxSize) {
                Swal.fire({
                    icon: "error",
                    title: "File Terlalu Besar",
                    text: "Ukuran file maksimal 5MB"
                });
                $(this).val('');
                preview.fadeOut();
                return;
            }

            const fileName = file.name;
            const fileSize = (file.size / 1024).toFixed(2);

            previewFilename.text(`${fileName} (${fileSize} KB)`);
            preview.fadeIn();
        } else {
            preview.fadeOut();
        }
    });

    // Function untuk update file numbers
    function updateNewsFileNumbers() {
        $('.file-upload-item').each(function(index) {
            $(this).find('.file-number').text(index + 1);
        });

        const fileCount = $('.file-upload-item').length;
        if (fileCount === 1) {
            $('.file-upload-item').first().find('.remove-file-btn').hide();
        } else {
            $('.file-upload-item').find('.remove-file-btn').show();
        }
    }

    // Function untuk toggle add button
    function toggleNewsAddButton() {
        const currentFileCount = $('.file-upload-item').length;
        if (currentFileCount >= newsMaxFiles) {
            $('#add_more_news_file').attr('disabled', true);
            $('#add_more_news_file span:first').text('Maksimal 5 Gambar Tercapai');
        } else {
            $('#add_more_news_file').attr('disabled', false);
            $('#add_more_news_file span:first').text('Tambah Gambar Lainnya');
        }
    }

    // Function untuk update file counter
    function updateNewsFileCounter() {
        const currentFileCount = $('.file-upload-item').length;
        $('#news_file_counter').text(`${currentFileCount}/${newsMaxFiles}`);
    }

    // Event handler untuk submit news form
    $(document).on('submit', '#form_news', function(e) {
        e.preventDefault();

        let newsId = $('#news_id').val();
        let isCreate = !newsId || newsId === '';
        let url = isCreate ? '{{ route('store-news-management') }}' :
            '{{ route('update-news-management') }}';

        // Collect files for upload
        const fileInputs = $('input[name="news_files[]"]');
        let filesToUpload = [];

        fileInputs.each(function() {
            if (this.files.length > 0) {
                Array.from(this.files).forEach(file => {
                    filesToUpload.push(file);
                });
            }
        });

        // Prepare form data for news (WITHOUT files)
        let newsFormData = new FormData();
        newsFormData.append('news_title', $('#news_title').val());
        newsFormData.append('news_content', $('#news_content').val());
        newsFormData.append('news_category', $('#news_category').val());
        newsFormData.append('news_source_url', $('#news_source_url').val());
        newsFormData.append('news_status', $('#news_status').val());
        newsFormData.append('news_published_at', $('#news_published_at').val());
        if (!isCreate) {
            newsFormData.append('news_id', newsId);
        }

        Swal.fire({
            title: 'Menyimpan...',
            html: 'Harap tunggu',
            didOpen: () => {
                Swal.showLoading()
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            type: 'POST',
            data: newsFormData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(res) {
                // News berhasil disimpan, sekarang upload files jika ada
                let createdNewsId = res.data.id || newsId;

                if (filesToUpload.length > 0) {
                    // Upload files
                    let uploadFormData = new FormData();
                    uploadFormData.append('id', createdNewsId);

                    filesToUpload.forEach(file => {
                        uploadFormData.append('files[]', file);
                    });

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        },
                        url: '{{ route('upload-news-images') }}',
                        type: 'POST',
                        data: uploadFormData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(uploadRes) {
                            Swal.close();
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: uploadRes.meta.message ||
                                    'News dan gambar berhasil disimpan',
                                timer: 2000
                            });

                            setTimeout(() => {
                                $('#formNews').modal('hide');
                                $('#news_management').DataTable()
                                    .ajax.reload();
                            }, 500);
                        },
                        error: function(uploadXhr) {
                            Swal.close();
                            let uploadResponse = JSON.parse(uploadXhr
                                .responseText);
                            Swal.fire({
                                icon: "warning",
                                title: "News Berhasil, Gambar Gagal",
                                text: uploadResponse.meta.message ||
                                    'News disimpan tapi gagal upload gambar',
                                timer: 3000
                            });

                            setTimeout(() => {
                                $('#formNews').modal('hide');
                                $('#news_management').DataTable()
                                    .ajax.reload();
                            }, 1000);
                        }
                    });
                } else {
                    // Tidak ada file, langsung close dan reload
                    Swal.close();
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: res.meta.message || 'News berhasil disimpan',
                        timer: 2000
                    });

                    setTimeout(() => {
                        $('#formNews').modal('hide');
                        $('#news_management').DataTable().ajax.reload();
                    }, 500);
                }
            },
            error: function(xhr) {
                Swal.close();
                let response = JSON.parse(xhr.responseText);
                let errorMessage = response.meta.message;
                if (response.meta.errors) {
                    errorMessage = Object.values(response.meta.errors).flat().join(
                        ', ');
                }
                Swal.fire({
                    icon: "error",
                    title: "Gagal Menyimpan",
                    text: errorMessage
                });
            }
        });
    });

    // Event handler untuk submit upload (jika ada separate form upload untuk legacy support)
    $(document).on('submit', '#form_upload_news_images', function(e) {
        e.preventDefault();

        const fileInputs = $('input[name="files[]"]');
        let hasFile = false;

        fileInputs.each(function() {
            if (this.files.length > 0) {
                hasFile = true;
            }
        });

        if (!hasFile) {
            Swal.fire({
                icon: "warning",
                title: "Pilih Gambar",
                text: "Silakan pilih setidaknya satu gambar untuk diupload"
            });
            return;
        }

        let form_data = new FormData(this);
        let news_id = $('#news_id_upload').val();
        form_data.append('id', news_id);

        Swal.fire({
            title: 'Mengupload Gambar...',
            html: 'Harap tunggu',
            didOpen: () => {
                Swal.showLoading()
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('upload-news-images') }}',
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            success: function(res) {
                Swal.close();
                Swal.fire({
                    icon: "success",
                    title: res.meta.message,
                    timer: 2000
                });
                $('#form_upload_news_images')[0].reset();
                $('#news_file_upload_container').html('');
                newsFileIndex = 0;

                // Reload existing images
                let newsId = $('#news_id_upload').val();
                loadExistingNewsImages(newsId);

                // Add first file upload field again
                addNewsFileUploadField();
            },
            error: function(xhr) {
                Swal.close();
                let response = JSON.parse(xhr.responseText);
                let errorMessage = response.meta.message;
                if (response.meta.errors) {
                    errorMessage = Object.values(response.meta.errors).flat().join(
                        ', ');
                }
                Swal.fire({
                    icon: "error",
                    title: "Gagal Upload Gambar",
                    text: errorMessage
                });
            }
        });
    });
</script>
