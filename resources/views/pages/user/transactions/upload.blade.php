{{-- Modal Upload dengan Dynamic Form --}}
<div class="modal fade" id="uploadTransaction" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="uploadTransaction_label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form_upload_file" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Upload Bukti Transaksi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto; background-color: #f8f9fe;">
                    <input type="hidden" name="file_transaction_id" id="file_transaction_id">

                    <!-- Info Alert -->
                    <div class="alert alert-info border-0 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lightbulb fs-4 me-3 text-info"></i>
                            <div>
                                <strong>Tips Upload:</strong> Anda dapat mengupload hingga <strong>5 file</strong> sekaligus
                                <br>
                                <small class="text-muted">Format: PNG, JPG, JPEG, PDF | Max: 2MB per file</small>
                            </div>
                        </div>
                    </div>

                    <!-- Container untuk multiple file upload -->
                    <div id="file_upload_container" class="mb-3">
                        <!-- File upload items akan ditambahkan di sini via JavaScript -->
                    </div>

                    <!-- Button untuk add more file -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-lg" id="add_more_file">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span>Tambah File Lainnya</span>
                            <span class="badge bg-primary ms-2" id="file_counter">1/5</span>
                        </button>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit_upload_transaction">
                        <i class="fas fa-upload me-2"></i>Upload Semua File
                    </button>
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

    .file-upload-item .card-header {
        background: linear-gradient(to right, #f8f9fe, #ffffff);
        border-bottom: 1px solid #e9ecef;
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
        padding: 12px;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 8px;
        border-left: 4px solid #28a745;
        animation: slideIn 0.3s ease;
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
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    #add_more_file:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .description-textarea {
        resize: none;
        transition: all 0.3s ease;
    }

    .description-textarea:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
    }

    /* Custom scrollbar */
    #file_upload_container::-webkit-scrollbar {
        width: 8px;
    }

    #file_upload_container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #file_upload_container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    #file_upload_container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Badge Animation */
    #file_counter {
        animation: pulse 0.3s ease;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
</style>