{{-- modal --}}
<div class="modal fade" id="formUpdateEvent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="formCreateEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_update_event" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="formUpdateEventLabel">Edit Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-3">
                            <label for="update_event_name" class="form-label">Event Name <span
                                    class="text-danger">*</span></label>
                            <input type="hidden" class="form-control" name="update_event_id" id="update_event_id"
                                placeholder="" value="">
                            <input type="text" class="form-control" name="update_event_name" id="update_event_name"
                                placeholder="Event name" value="" required>
                            <small class="text-danger d-none error-update_event_name"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_title" class="form-label">Event Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="update_event_title" id="update_event_title"
                                placeholder="Event title" value="" required>
                            <small class="text-danger d-none error-update_event_title"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_category" class="form-label">Category <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" name="update_event_category" id="update_event_category"
                                required
                                style="display: block !important; width: 100% !important; padding: 0.5rem 0.75rem !important; font-size: 0.875rem !important; font-weight: 400 !important; line-height: 1.4rem !important; color: #495057 !important; background-color: #fff !important; background-clip: padding-box !important; border: 1px solid #d2d6da !important; appearance: none !important; border-radius: 0.5rem !important; transition: box-shadow 0.15s ease, border-color 0.15s ease !important;">
                            </select>
                            <input type="hidden" class="form-control" id="update_event_category_value"
                                name="update_event_category_value" value="">
                            <small class="text-danger d-none error-update_event_category"></small>
                        </div>
                        <div class="detail_event_update"></div>
                        <div class="update_event_price">
                            <div class="form-group mb-3">
                                <label for="update_event_price" class="form-label">Price</label>
                                <input type="text" class="form-control price" name="update_event_price"
                                    id="update_event_price" placeholder="0" value="">
                                <small class="text-danger d-none error-update_event_price"></small>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value=""
                                    name="update_check_event_hotel" id="update_check_event_hotel">
                                <label class="form-check-label" for="update_check_event_hotel">
                                    Include Hotel
                                </label>
                            </div>
                        </div>
                        <div id="update_select_event_hotel"></div>
                        <div class="form-group mb-3">
                            <label for="update_event_description" class="form-label">Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" name="update_event_description" id="update_event_description" rows="3"
                                placeholder="Event description" required></textarea>
                            <small class="text-danger d-none error-update_event_description"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_quota" class="form-label">Quota User <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="number" name="update_event_quota"
                                id="update_event_quota" min="1" placeholder="Quota" value="" required>
                            <small class="text-danger d-none error-update_event_quota"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_date" class="form-label">Date <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="datetime-local" name="update_event_date"
                                id="update_event_date" value="" required>
                            <small class="text-danger d-none error-update_event_date"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_start_date" class="form-label">Start Date <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="update_event_start_date"
                                id="update_event_start_date" value="" required>
                            <small class="text-danger d-none error-update_event_start_date"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_end_date" class="form-label">End Date <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="update_event_end_date"
                                id="update_event_end_date" value="" required>
                            <small class="text-danger d-none error-update_event_end_date"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_date_last_register" class="form-label">Last Register Date <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="update_event_date_last_register"
                                id="update_event_date_last_register" value="" required>
                            <small class="text-danger d-none error-update_event_date_last_register"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_max_attendance" class="form-label">Max Attendance</label>
                            <input class="form-control" type="number" name="update_event_max_attendance"
                                id="update_event_max_attendance" min="1"
                                placeholder="Leave empty for auto-calculate">
                            <small class="text-muted">Auto-calculated based on event duration if left empty</small>
                            <small class="text-danger d-none error-update_event_max_attendance"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="update_event_image" class="form-label">Image</label>
                            <input class="form-control" type="file" name="update_event_image"
                                id="update_event_image" value="" accept="image/png,image/jpg,image/jpeg">
                            <small class="text-muted">Accepted formats: PNG, JPG (Max: 2MB)</small>
                            <small class="text-danger d-none error-update_event_image"></small>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="update_event_is_active">
                                <label class="form-check-label" for="update_event_is_active"
                                    id="update_event_label"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="update_event">Update Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
