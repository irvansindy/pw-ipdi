{{-- modal --}}
<div class="modal fade" id="formNews" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="formNewsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_news">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="NewsLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="news_title" class="form-label">Title</label>
                        <input type="hidden" class="form-control" id="news_id" name="news_id" required>
                        <input type="text" class="form-control" id="news_title" name="news_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="news_content" class="form-label">Content</label>
                        <textarea class="form-control" id="news_content" name="news_content" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="news_image" class="form-label">Images <small class="text-primary"
                                style="font-weight: 300 !important;">(Max 5 gambar)</small></label>

                        <!-- Display existing images -->
                        <div id="news_images_display" class="mb-3" style="display: none;">
                            <small class="text-muted d-block mb-2">Gambar yang sudah diupload:</small>
                            <div id="existing_images_container" class="row g-2"></div>
                        </div>

                        <!-- File counter -->
                        <div class="mb-2">
                            <small class="text-muted">Upload Gambar: <span id="news_file_counter">0/5</span></small>
                        </div>

                        <!-- Multiple file upload container -->
                        <div id="news_file_upload_container" class="mb-3">
                            <!-- File upload items akan ditambahkan di sini via JavaScript -->
                        </div>

                        <!-- Add more button -->
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" id="add_more_news_file">
                            <i class="fas fa-plus-circle me-2"></i>Tambah File
                        </button>

                        <small class="text-muted m-1 d-block" id="image_help_text" style="font-weight: 300 !important;">
                            Format: JPEG, PNG, JPG, GIF | Ukuran Max: 5MB per file | Maksimal 5 gambar per news
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="news_category" class="form-label">Category</label>
                        <select class="form-control news_select2" name="news_category" id="news_category"
                            style="width: 100% !important;">
                            <option value="1">Dummy</option>
                            <option value="2">Dummy 2</option>
                            <option value="3">Dummy 3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="news_source_url" class="form-label">Source Url <small class="text-primary"
                                style="font-weight: 300 !important;">(apabila sumber dari luar)</small></label>
                        <input type="url" class="form-control" id="news_source_url" name="news_source_url">
                    </div>
                    <div class="mb-3">
                        <label for="news_status" class="form-label">Status</label>
                        <select class="form-control news_select2" name="news_status" id="news_status"
                            style="width: 100% !important;">
                            <option value="">Select One</option>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="news_published_at" class="form-label">Published At</label>
                        <input type="datetime-local" class="form-control" id="news_published_at"
                            name="news_published_at" required>
                    </div>
                </div>
                <div class="modal-footer" id="button_approval_iuran">

                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Custom Styling untuk Upload Modal */
    .file-upload-item {
        background: #ffffff;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        padding: 12px;
    }

    .file-upload-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #5e72e4 0%, #825ee4 100%);
    }

    .file-upload-item:hover {
        border-color: #5e72e4;
        box-shadow: 0 4px 12px rgba(94, 114, 228, 0.15);
        transform: translateY(-2px);
    }

    .remove-file-btn {
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
    }

    .remove-file-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .file-input {
        cursor: pointer;
        border: 2px dashed #cbd5e0;
        transition: all 0.3s ease;
    }

    .file-input:hover {
        border-color: #5e72e4;
        background-color: #f8f9fe;
    }

    .file-input:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
    }

    .file-preview {
        padding: 8px 12px;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 6px;
        border-left: 4px solid #28a745;
        animation: slideIn 0.3s ease;
        display: none;
        margin-top: 8px;
        font-size: 0.875rem;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.4s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .remove-animation {
        animation: fadeOut 0.3s ease-out;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }

        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }

    .file-number-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.75rem;
        color: white;
        display: inline-block;
    }

    #add_more_news_file:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    #news_file_counter {
        animation: pulse 0.3s ease;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }
</style>
