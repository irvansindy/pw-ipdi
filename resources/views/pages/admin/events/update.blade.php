{{-- modal --}}
<div class="modal fade" id="formUpdateEvent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formCreateEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_update_event">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="formUpdateEventLabel">Detail Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="update_event_name">Name</label>
                            <input type="hidden" class="form-control" name="update_event_id" id="update_event_id" placeholder="" value="">
                            <input type="text" class="form-control" name="update_event_name" id="update_event_name" placeholder="Event Name" value="">
                        </div>
                        <div class="form-group">
                            <label for="update_event_title">Title</label>
                            <input type="text" class="form-control" name="update_event_title" id="update_event_title" placeholder="Event Title" value="">
                        </div>
                        <div class="form-group">
                            <label for="update_event_category">Category</label>
                            <select class="form-control" name="update_event_category" id="update_event_category" style="
                                display: block !important;
                                width: 100% !important;
                                padding: 0.5rem 0.75rem !important;
                                font-size: 0.875rem !important;
                                font-weight: 400 !important;
                                line-height: 1.4rem !important;
                                color: #495057 !important;
                                background-color: #fff !important;
                                background-clip: padding-box !important;
                                border: 1px solid #d2d6da !important;
                                appearance: none !important;
                                border-radius: 0.5rem !important;
                                transition: box-shadow 0.15s ease, border-color 0.15s ease !important;">
                            </select>
                            <input type="hidden" class="form-control" id="update_event_category_value" name="update_event_category_value" value="">
                        </div>
                        <div class="form-group">
                            <label for="update_event_price">Price</label>
                            <input type="text" class="form-control price" name="update_event_price" id="update_event_price" placeholder="Event Price" value="">
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="update_check_event_hotel" id="update_check_event_hotel">
                                <label class="form-check-label" for="update_check_event_hotel">
                                    Hotel
                                </label>
                            </div>
                        </div>
                        <div id="update_select_event_hotel">
                            
                        </div>
                        <div class="form-group">
                            <label for="update_event_description">Description</label>
                            <textarea class="form-control" name="update_event_description" id="update_event_description" rows="3" value=""></textarea>
                        </div>
                        <div class="form-group">
                            <label for="update_event_quota">Quota User</label>
                            <input class="form-control" name="update_event_quota" id="update_event_quota" rows="3" value="" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" type="number" placeholder="Quota">
                        </div>
                        <div class="form-group">
                            <label for="update_event_date" class="form-control-label">Date</label>
                            <input class="form-control" type="datetime-local" name="update_event_date" id="update_event_date" value="">
                        </div>
                        <div class="form-group">
                            <label for="update_event_date_last_register" class="form-control-label">Date Last Register</label>
                            <input class="form-control" type="date" name="update_event_date_last_register" id="update_event_date_last_register" value="">
                        </div>
                        <div class="form-group">
                            <label for="update_event_image" class="form-control-label">Image</label>
                            <input class="form-control" type="file" name="update_event_image" id="update_event_image" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="update_event">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>