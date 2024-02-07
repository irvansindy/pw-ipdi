{{-- modal --}}
<div class="modal fade" id="formCreateEvent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  role="dialog" aria-labelledby="formCreateEventLabel" aria-hidden="true">
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered me-lg-8" role="document"> --}}
    {{-- <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document"> --}}
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="form_create_event">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: #172b4d !important;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="create_event_name">Name</label>
                            <input type="text" class="form-control" name="create_event_name" id="create_event_name" placeholder="Event Name">
                        </div>
                        <div class="form-group">
                            <label for="create_event_title">Title</label>
                            <input type="text" class="form-control" name="create_event_title" id="create_event_title" placeholder="Event Title">
                        </div>
                        <div class="form-group">
                            <label for="create_event_category">Category</label>
                            {{-- <input type="text" class="form-control" name="create_event_category" id="create_event_category" placeholder="Event Category"> --}}
                            <select class="form-control" name="create_event_category" id="create_event_category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="create_event_description">Description</label>
                            <textarea class="form-control" name="create_event_description" id="create_event_description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="create_event_date" class="form-control-label">Date</label>
                            <input class="form-control" type="datetime-local" value="" id="create_event_date">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="create_event">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>