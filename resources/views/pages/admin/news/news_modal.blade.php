{{-- modal --}}
<div class="modal fade" id="formNews" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="formNewsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_news">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="NewsLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
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
                        <label for="news_image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="news_image" name="news_image" required>
                        <small class="text-primary m-1" id="link_news_image" style="font-weight: 300 !important; cursor: pointer;">
                            <a href=""></a>
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="news_category" class="form-label">Category</label>
                        <select class="form-control news_select2" name="news_category" id="news_category" style="width: 100% !important;">
                            <option value="1">Dummy</option>
                            <option value="2">Dummy 2</option>
                            <option value="3">Dummy 3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="news_source_url" class="form-label">Source Url <small class="text-primary" style="font-weight: 300 !important;">(apabila sumber dari luar)</small></label>
                        <input type="url" class="form-control" id="news_source_url" name="news_source_url" required>
                    </div>
                    <div class="mb-3">
                        <label for="news_status" class="form-label">Status</label>
                        <select class="form-control news_select2" name="news_status" id="news_status" style="width: 100% !important;">
                            <option value="">Select One</option>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="news_published_at" class="form-label">Published At</label>
                        <input type="datetime-local" class="form-control" id="news_published_at" name="news_published_at" required>
                    </div>
                </div>
                <div class="modal-footer" id="button_approval_iuran">
                    
                </div>
            </form>
        </div>
    </div>
</div>