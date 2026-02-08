<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"
    integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        initDataTable();
        initSelect2();
        setupEventListeners();
    });

    /**
     * Initialize DataTable
     */
    function initDataTable() {
        $('#event_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route('event-fetch') }}',
                type: 'GET',
            },
            columns: [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "quota",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "date",
                    "defaultContent": "<i>Not set</i>",
                    "render": function(data) {
                        return data ? moment(data).format('DD/MM/YYYY HH:mm') : '<i>Not set</i>';
                    }
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-event_id="' + item.id +
                            '" class="btn btn-outline-info btn-sm mt-2 detail_event_data" data-bs-toggle="modal" data-bs-target="#formUpdateEvent">View</button> <a href="/ticket-qrcode?transnumber=' +
                            item.id + '" class="btn btn-outline-danger btn-sm mt-2">Qr Code</a>'
                    }
                },
            ]
        })
    }

    /**
     * Initialize Select2
     */
    function initSelect2() {
        $('#create_event_category').select2({
            dropParent: '#formCreateEvent'
        });

        $('#create_event_hotel').select2({
            dropParent: '#formCreateEvent',
            allowClear: true,
            width: '100%'
        });

        $('#update_event_category').select2({
            dropParent: '#formUpdateEvent'
        });
    }

    /**
     * Setup all event listeners
     */
    function setupEventListeners() {
        $(document).on('click', '#for_create_event_data', handleCreateEventModal);
        $(document).on('change', '#create_event_category', handleCategoryChange);
        $(document).on('change', '#check_event_hotel', handleHotelCheckbox);
        $(document).on('change', '#update_check_event_hotel', handleUpdateHotelCheckbox);
        $(document).on('click', '#create_event', handleCreateEvent);
        $(document).on('click', '.detail_event_data', handleDetailEvent);
        $(document).on('click', '#update_event', handleUpdateEvent);
        $(document).on('keyup', '.price', formatPriceInput);
    }

    /**
     * Handle Create Event Modal
     */
    function handleCreateEventModal(e) {
        e.preventDefault();
        $('#form_create_event')[0].reset();

        $.ajax({
            url: '{{ route('event-category') }}',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                $('#select_event_hotel').empty();
                $('#create_event_category').empty().append('<option value="">Pilih Satu</option>');

                $.each(res.data, function(i, data) {
                    $('#create_event_category').append(
                        '<option value="' + data.id + '" data-event_category="' + data.name +
                        '">' + data.name + '</option>'
                    );
                });
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: 'Error',
                    text: 'Failed to load categories',
                });
            }
        });
    }

    /**
     * Handle Category Change
     */
    function handleCategoryChange(e) {
        e.preventDefault();

        let selectedCategory = $('#create_event_category option:selected').data('event_category');

        if (selectedCategory && selectedCategory.toLowerCase() === 'jnns') {
            renderJnnsDetail();
        } else {
            renderDefaultPrice();
        }
    }

    /**
     * Render JNNS Detail (Symposium, Workshop, Paket)
     */
    function renderJnnsDetail() {
        $('.detail_event').empty();
        $('#master_event_price').empty();

        $('.detail_event').append(`
            <div class="row g-3 align-items-center form-group">
                <div class="col-md-4">
                    <label for="create_event_detail_price_simposium">Harga Simposium</label>
                    <input type="hidden" name="create_event_detail_name[]" class="form-control" value="simposium">
                    <input type="text" name="create_event_detail_price[]" class="form-control price" placeholder="0">
                </div>
                <div class="col-md-4">
                    <label for="create_event_detail_price_workshop">Harga Workshop</label>
                    <input type="hidden" name="create_event_detail_name[]" class="form-control" value="workshop">
                    <input type="text" name="create_event_detail_price[]" class="form-control price" placeholder="0">
                </div>
                <div class="col-md-4">
                    <label for="create_event_detail_price_paket">Harga Paket</label>
                    <input type="hidden" name="create_event_detail_name[]" class="form-control" value="paket">
                    <input type="text" name="create_event_detail_price[]" class="form-control price" placeholder="0">
                </div>
            </div>
        `);
    }

    /**
     * Render Default Price Field
     */
    function renderDefaultPrice() {
        $('.detail_event').empty();
        $('#master_event_price').empty().append(`
            <div class="form-group">
                <label for="create_event_price">Price</label>
                <input type="text" class="form-control price" name="create_event_price" id="create_event_price" placeholder="0">
            </div>
        `);
    }

    /**
     * Handle Hotel Checkbox
     */
    function handleHotelCheckbox() {
        if ($('#check_event_hotel').prop('checked')) {
            $('#select_event_hotel').empty().append(`
                <div class="row g-2 align-items-center form-group">
                    <div class="col-md-6">
                        <label for="create_event_hotel">Nama Hotel</label>
                        <input type="text" name="create_event_hotel" id="create_event_hotel" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="create_event_harga_hotel">Harga Hotel</label>
                        <input type="text" name="create_event_harga_hotel" id="create_event_harga_hotel" class="form-control price">
                    </div>
                </div>
            `);
        } else {
            $('#select_event_hotel').empty();
        }
    }

    /**
     * Handle Update Hotel Checkbox
     */
    function handleUpdateHotelCheckbox() {
        if ($('#update_check_event_hotel').prop('checked')) {
            let hotelName = $('#update_event_hotel').val() || '';
            let hotelPrice = $('#update_event_harga_hotel').val() || '';

            $('#update_select_event_hotel').empty().append(`
                <div class="row g-2 align-items-center form-group">
                    <div class="col-md-6">
                        <label for="update_event_hotel">Nama Hotel</label>
                        <input type="text" name="update_event_hotel" id="update_event_hotel" class="form-control" value="${hotelName}">
                    </div>
                    <div class="col-md-6">
                        <label for="update_event_harga_hotel">Harga Hotel</label>
                        <input type="text" name="update_event_harga_hotel" id="update_event_harga_hotel" class="form-control price" value="${hotelPrice}">
                    </div>
                </div>
            `);
        } else {
            $('#update_select_event_hotel').empty();
        }
    }

    /**
     * Handle Create Event
     */
    function handleCreateEvent(e) {
        e.preventDefault();

        let form = $('#form_create_event')[0];
        let formData = new FormData(form);

        // Collect detail names and prices
        let detailNames = [];
        let detailPrices = [];

        $("input[name='create_event_detail_name[]']").each(function() {
            let value = $(this).val();
            if (value) detailNames.push(value);
        });

        $("input[name='create_event_detail_price[]']").each(function() {
            let value = $(this).val();
            if (value) detailPrices.push(value);
        });

        // Add arrays to FormData
        detailNames.forEach(name => {
            formData.append('detail_event_name[]', name);
        });

        detailPrices.forEach(price => {
            formData.append('detail_event_price[]', price);
        });

        // Basic validation
        let eventName = $('#create_event_name').val();
        let eventTitle = $('#create_event_title').val();
        let eventCategory = $('#create_event_category').val();
        let eventDescription = $('#create_event_description').val();
        let eventQuota = $('#create_event_quota').val();
        let eventDate = $('#create_event_date').val();
        let eventStartDate = $('#create_event_start_date').val();
        let eventEndDate = $('#create_event_end_date').val();
        let eventLastRegister = $('#create_event_date_last_register').val();
        let eventMaxAttendance = $('#create_event_max_attendance').val();

        if (!eventName) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event name is required'
            });
        }
        if (!eventTitle) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event title is required'
            });
        }
        if (!eventCategory) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event category is required'
            });
        }
        if (!eventDescription) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event description is required'
            });
        }
        if (!eventQuota) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event quota is required'
            });
        }
        if (!eventDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event date is required'
            });
        }
        if (!eventStartDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event start date is required'
            });
        }
        if (!eventEndDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event end date is required'
            });
        }
        if (!eventLastRegister) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event last register date is required'
            });
        }

        // Validate date relationships
        if (eventEndDate < eventStartDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'End date must be after or equal to start date'
            });
        }
        if (eventLastRegister > eventStartDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Last register date must be before or equal to start date'
            });
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('event-store') }}',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(res) {
                Swal.fire({
                    icon: "success",
                    title: res.meta.message,
                });
                $('#formCreateEvent').modal('toggle');
                $('#event_management').DataTable().ajax.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let response = xhr.responseJSON;
                    let firstError = Object.values(response.data)[0][0];
                    Swal.fire({
                        icon: "error",
                        title: 'Validation Error',
                        text: firstError,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'Failed to create event',
                    });
                }
            }
        });
    }

    /**
     * Handle Detail Event (View/Edit)
     */
    function handleDetailEvent(e) {
        let eventId = $(this).data('event_id');

        $.ajax({
            url: '{{ route('event-detail') }}',
            type: 'GET',
            data: {
                id: eventId
            },
            dataType: 'json',
            success: function(res) {
                $('#form_update_event')[0].reset();

                let event = res.data.event;
                let categories = res.data.categories;

                // Set basic fields
                $('#update_event_id').val(event.id);
                $('#update_event_name').val(event.name);
                $('#update_event_title').val(event.title);
                $('#update_event_description').val(event.description);
                $('#update_event_quota').val(event.quota);

                // Format datetime-local field
                if (event.date) {
                    $('#update_event_date').val(moment(event.date).format('YYYY-MM-DDTHH:mm'));
                }

                // Format date fields
                if (event.start_date) {
                    $('#update_event_start_date').val(moment(event.start_date).format('YYYY-MM-DD'));
                }
                if (event.end_date) {
                    $('#update_event_end_date').val(moment(event.end_date).format('YYYY-MM-DD'));
                }
                if (event.last_register) {
                    $('#update_event_date_last_register').val(moment(event.last_register).format(
                        'YYYY-MM-DD'));
                }

                $('#update_event_max_attendance').val(event.max_attendance);
                $('#update_event_price').val(event.price);

                // Set active checkbox
                let isActive = event.is_active === 1;
                $('#update_event_is_active').prop('checked', isActive);
                $('#update_event_label').text(isActive ? 'Active' : 'Non-Active');

                // Set category
                $('#update_event_category_value').val(event.category.id);
                $('#update_event_category').empty()
                    .append('<option value="' + event.category.id + '">' + event.category.name +
                        '</option>');

                $.each(categories, function(i, data) {
                    $('#update_event_category').append(
                        '<option value="' + data.id + '">' + data.name + '</option>'
                    );
                });

                // Render details based on category
                if (event.category.name.toLowerCase() === 'jnns') {
                    renderUpdateJnnsDetail(event.event_detail);
                    $('.update_event_price').empty();
                } else {
                    renderUpdateDefaultPrice(event.price);
                    $('.detail_event_update').empty();
                }

                // Handle hotel
                if (event.hotel) {
                    $('#update_check_event_hotel').prop('checked', true);
                    $('#update_select_event_hotel').empty().append(`
                        <div class="row g-2 align-items-center form-group">
                            <div class="col-md-6">
                                <label for="update_event_hotel">Nama Hotel</label>
                                <input type="text" name="update_event_hotel" id="update_event_hotel" class="form-control" value="${event.hotel.name}">
                            </div>
                            <div class="col-md-6">
                                <label for="update_event_harga_hotel">Harga Hotel</label>
                                <input type="text" name="update_event_harga_hotel" id="update_event_harga_hotel" class="form-control price" value="${event.hotel.price || 0}">
                            </div>
                        </div>
                    `);
                } else {
                    $('#update_check_event_hotel').prop('checked', false);
                    $('#update_select_event_hotel').empty();
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to load event details",
                });
            }
        });
    }

    /**
     * Render Update JNNS Detail
     */
    function renderUpdateJnnsDetail(details) {
        $('.detail_event_update').empty();

        let html = '<div class="row g-3 align-items-center form-group">';

        if (Array.isArray(details) && details.length > 0) {
            details.forEach((detail, index) => {
                html += `
                    <div class="col-md-4">
                        <label for="update_event_detail_price_${index}">Harga ${detail.name.charAt(0).toUpperCase() + detail.name.slice(1)}</label>
                        <input type="hidden" name="update_event_detail_name[]" class="form-control" value="${detail.name}">
                        <input type="text" name="update_event_detail_price[]" class="form-control price" id="update_event_detail_price_${index}" value="${detail.price}">
                    </div>
                `;
            });
        } else {
            // Default JNNS types if empty
            const defaultTypes = ['simposium', 'workshop', 'paket'];
            defaultTypes.forEach((type, index) => {
                html += `
                    <div class="col-md-4">
                        <label>Harga ${type.charAt(0).toUpperCase() + type.slice(1)}</label>
                        <input type="hidden" name="update_event_detail_name[]" class="form-control" value="${type}">
                        <input type="text" name="update_event_detail_price[]" class="form-control price">
                    </div>
                `;
            });
        }

        html += '</div>';
        $('.detail_event_update').append(html);
    }

    /**
     * Render Update Default Price
     */
    function renderUpdateDefaultPrice(price) {
        $('.update_event_price').empty().append(`
            <div class="form-group">
                <label for="update_event_price">Price</label>
                <input type="text" class="form-control price" name="update_event_price" id="update_event_price" placeholder="0" value="${price || 0}">
            </div>
        `);
    }

    /**
     * Handle Update Event
     */
    function handleUpdateEvent(e) {
        e.preventDefault();

        let eventId = $('#update_event_id').val();
        let eventName = $('#update_event_name').val();
        let eventTitle = $('#update_event_title').val();
        let eventCategory = $('#update_event_category').val();
        let eventPrice = $('#update_event_price').length ?
            ($('#update_event_price').val() || 0) :
            0;
        let eventDescription = $('#update_event_description').val();
        let eventQuota = $('#update_event_quota').val();
        let eventDate = $('#update_event_date').val();
        let eventStartDate = $('#update_event_start_date').val();
        let eventEndDate = $('#update_event_end_date').val();
        let eventLastRegister = $('#update_event_date_last_register').val();
        let eventMaxAttendance = $('#update_event_max_attendance').val();
        let isActive = $('#update_event_is_active').is(':checked') ? 1 : 0;

        // Validation
        if (!eventName) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event name is required'
            });
        }
        if (!eventTitle) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event title is required'
            });
        }
        if (!eventCategory) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event category is required'
            });
        }
        if (!eventDescription) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event description is required'
            });
        }
        if (!eventQuota) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event quota is required'
            });
        }
        if (!eventDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event date is required'
            });
        }
        if (!eventStartDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event start date is required'
            });
        }
        if (!eventEndDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event end date is required'
            });
        }
        if (!eventLastRegister) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Event last register date is required'
            });
        }

        // Validate date relationships
        if (eventEndDate < eventStartDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'End date must be after or equal to start date'
            });
        }
        if (eventLastRegister > eventStartDate) {
            return Swal.fire({
                icon: "error",
                title: 'Oops...',
                text: 'Last register date must be before or equal to start date'
            });
        }

        let formData = new FormData();
        formData.append('id', eventId);
        formData.append('name', eventName);
        formData.append('title', eventTitle);
        formData.append('category', eventCategory);
        formData.append('price', eventPrice);
        formData.append('description', eventDescription);
        formData.append('quota', eventQuota);
        formData.append('date', eventDate);
        formData.append('start_date', eventStartDate);
        formData.append('end_date', eventEndDate);
        formData.append('last_register', eventLastRegister);
        formData.append('max_attendance', eventMaxAttendance);
        formData.append('is_active', isActive);

        // Hotel data
        if ($('#update_check_event_hotel').prop('checked')) {
            let hotelName = $('#update_event_hotel').val();
            let hotelPrice = $('#update_event_harga_hotel').val();
            formData.append('update_event_hotel', hotelName);
            formData.append('update_event_harga_hotel', hotelPrice);
        }

        // Event details
        $('input[name="update_event_detail_name[]"]').each(function() {
            formData.append('update_event_detail_name[]', $(this).val());
        });

        $('input[name="update_event_detail_price[]"]').each(function() {
            formData.append('update_event_detail_price[]', $(this).val());
        });

        // Image if exists
        const imageFile = $('input[name="update_event_image"]')[0].files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('event-update') }}',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(res) {
                Swal.fire({
                    icon: "success",
                    title: res.meta.message,
                });
                $('#formUpdateEvent').modal('toggle');
                $('#event_management').DataTable().ajax.reload();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let response = xhr.responseJSON;
                    let firstError = Object.values(response.data)[0][0];
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: firstError,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: xhr.responseJSON?.data?.message || "Failed to update event",
                    });
                }
            }
        });
    }

    /**
     * Format Price Input
     */
    function formatPriceInput(e) {
        $(this).val(formatPrice($(this).val()));
    }

    /**
     * Format number to price format (with thousand separator)
     */
    function formatPrice(num) {
        if (!num) return '';

        let str = num.toString().replace(/[^\d.,]/g, '');
        let parts = false;
        let output = [];
        let i = 1;
        let formatted = null;

        if (str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }

        str = str.split("").reverse();

        for (let j = 0, len = str.length; j < len; j++) {
            if (str[j] != ",") {
                output.push(str[j]);
                if (i % 3 == 0 && j < (len - 1)) {
                    output.push(",");
                }
                i++;
            }
        }

        formatted = output.reverse().join("");
        return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    }

    /**
     * Update Active Label on checkbox change
     */
    $(document).on('change', '#update_event_is_active', function() {
        $('#update_event_label').text(this.checked ? "Active" : "Non-Active");
    });
</script>
