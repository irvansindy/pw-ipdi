<script>
    $(document).ready(function() {
        // alert('hahahahah')
        $('#event_management').DataTable({
            processing: true,
            ajax: {
                url: '{{ route("event-fetch") }}',
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
                    "data": "name",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "quota",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    "data": "date",
                    "defaultContent": "<i>Not set</i>"
                },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-event_id="' +item.id +'" class="btn btn-outline-info btn-sm mt-2 detail_event_data" data-bs-toggle="modal" data-bs-target="#formUpdateEvent">View</button> <a href="/ticket-qrcode?transnumber=' + item.id + '" class="btn btn-outline-danger btn-sm mt-2">Qr Code</a>'
                    }
                },
            ]
        })

        $('#create_event_category').select2({
            dropParent: '#formCreateEvent'
        })
        
        $('#create_event_hotel').select2({
            dropParent: '#formCreateEvent',
            allowClear: true,
            width: '100%'
        })
        
        $('#update_event_category').select2({
            dropParent: '#formUpdateEvent'
        })

        $(document).on('click', '#for_create_event_data', function(e) {
            e.preventDefault()
            $('#form_create_event')[0].reset()
            $.ajax({
                url: '{{ route('event-category') }}',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $('#select_event_hotel').empty()
                    $('#create_event_category').empty()
                    $('#create_event_category').append('<option value="">Pilih Satu</option>')
                    $.each(res.data, function (i, data) {
                        $('#create_event_category').append('<option value="'+ data.id + '">'+ data.name + '</option>')
                    })
                }
            })
        })

        $(document).on('change', '#create_event_category', function(e) {
            e.preventDefault()
            var value_category = $("#create_event_category option:selected").val();
            // alert(value_category)
            if (value_category == 1) {
                $('.detail_event').empty()
                $('#master_event_price').empty()
                $('.detail_event').append(`
                    <div class="row g-3 align-items-center form-group">
                        <div class="col-md-4">
                            <label for="create_event_detail_name_simposium">Harga Simposium</label>
                            <input type="hidden" name="create_event_detail_name[]" id="create_event_detail_name_simposium" class="form-control" value="simposium">
                            <input type="text" name="create_event_detail_price[]" id="create_event_detail_price_simposium" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="create_event_detail_name_simposium">Harga Workshop</label>
                            <input type="hidden" name="create_event_detail_name[]" id="create_event_detail_name_simposium" class="form-control" value="workshop">
                            <input type="text" name="create_event_detail_price[]" id="create_event_detail_price_simposium" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="create_event_detail_name_simposium">Harga Paket</label>
                            <input type="hidden" name="create_event_detail_name[]" id="create_event_detail_name_simposium" class="form-control" value="paket">
                            <input type="text" name="create_event_detail_price[]" id="create_event_detail_price_simposium" class="form-control">
                        </div>
                    </div>
                `)
            } else {
                $('.detail_event').empty()
                $('#master_event_price').append(`
                <div class="form-group">
                    <label for="create_event_price">Price</label>
                    <input type="text" class="form-control price" name="create_event_price" id="create_event_price" placeholder="Event Price" value="">
                </div>
                `)
            }
        })

        $(document).on('change', '#check_event_hotel', function() {
            if ($('#check_event_hotel').prop('checked')) {
                $('#select_event_hotel').empty()
                $('#select_event_hotel').append(`
                <div class="row g-2 align-items-center form-group">
                    <div class="col-md-6">
                        <label for="create_event_hotel">Nama Hotel</label>
                        <input type="text" name="create_event_hotel" id="create_event_hotel" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="create_event_detail_name_simposium">Harga Hotel</label>
                        <input type="text" name="create_event_harga_hotel" id="create_event_harga_hotel" class="form-control">
                    </div>
                </div>
                `)
            } else {
                $('#select_event_hotel').empty()
            }
        })

        $(document).on('click', '#create_event', function(e) {
            e.preventDefault()

            let create_event_name = $('#create_event_name').val()
            let create_event_title = $('#create_event_title').val()
            let create_event_category = $('#create_event_category').val()
            let create_event_price = $('#create_event_price').val()
            let create_event_description = $('#create_event_description').val()
            let create_event_quota = $('#create_event_quota').val()
            let create_event_date = $('#create_event_date').val()
            let create_event_hotel = $('#create_event_hotel').val()
            let create_event_harga_hotel = $('#create_event_harga_hotel').val()
            var value_detail_name = [];
            $("input[name='create_event_detail_name[]']").each(function() {
                var value = $(this).val();
                value_detail_name.push(value);
            });
            // Anda sekarang memiliki semua nilai dalam value_detail_name
            console.log(value_detail_name);
            var value_detail_price = [];
            $("input[name='create_event_detail_price[]']").each(function() {
                var value = $(this).val();
                value_detail_price.push(value);
            });

            // Anda sekarang memiliki semua nilai dalam value_detail_price
            console.log(value_detail_price);

            let allow_image_extensions = ['jpeg', 'jpg', 'png']
            
            let data = {
                'name': create_event_name,
                'title': create_event_title,
                'category': create_event_category,
                'price': create_event_price,
                'description': create_event_description,
                'quota': create_event_quota,
                'date': create_event_date,
                'hotel': create_event_hotel,
                'detail_event': value_detail_price,
                'image': $('input[name="create_event_image"]')[0].files[0]
            }

            let form_data = new FormData();
            form_data.append('name', create_event_name);
            form_data.append('title', create_event_title);
            form_data.append('category', create_event_category);
            form_data.append('price', create_event_price);
            form_data.append('description', create_event_description);
            form_data.append('quota', create_event_quota);
            form_data.append('date', create_event_date);
            form_data.append('hotel', create_event_hotel);
            form_data.append('harga_hotel', create_event_harga_hotel);
            form_data.append('last_register', create_event_date_last_register);

            for (let i = 0; i < value_detail_name.length; i++) {
                form_data.append('detail_event_name[]', value_detail_name[i]);
            }
            for (let i = 0; i < value_detail_price.length; i++) {
                form_data.append('detail_event_price[]', value_detail_price[i]);
            }

            form_data.append('image', $('input[name="create_event_image"]')[0].files[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('event-store') }}',
                type: 'post',
                data: form_data,
                // dataType: 'json',
                cache : false,
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $('#formCreateEvent').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                    // location.reload()
                },
                error: function(xhr) {
                    // console.log(xhr.responseText);
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "error",
                        title: 'Error!',
                        text: 'Form field tidak boleh kosong.',
                    })
                }
            })
        })

        $(document).on('click', '.detail_event_data', function(e) {
            let event_id = $(this).data('event_id')
            $.ajax({
                url: '{{ route('event-detail') }}',
                type: 'GET',
                data: {
                    id: event_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#form_update_event')[0].reset()
                    // alert(res.data.name)
                    $('#update_event_id').val(res.data[0].id)
                    $('#update_event_is_active').val(res.data[0].is_active)

                    let is_active = res.data[0].is_active
                    const checkbox = document.getElementById("update_event_is_active");
                    const label = document.getElementById("update_event_label");
                    checkbox.checked = is_active === 1;
                    label.textContent = is_active === 1 ? "Active" : "Non-Active";

                    checkbox.addEventListener("change", function () {
                        label.textContent = this.checked ? "Active" : "Non-Active";
                    });

                    $('#update_event_name').val(res.data[0].name)
                    $('#update_event_title').val(res.data[0].title)
                    

                    $('#update_event_category_value').val(res.data[0].category.id)
                    $('#update_event_category').empty()
                    $('#update_event_category').append('<option value="' + res.data[0].category.id + '">' + res.data[0].category.name + '</option>')
                    $.each(res.data[1], function(i, data) {
                        $('#update_event_category').append('<option value="' + data.id + '">' + data.name + '</option>')
                    })
                    $('#update_event_price').val(res.data[0].price)
                    if(res.data[0].hotel != null) {
                        $('#update_check_event_hotel').prop('checked', true) 
                        $('#update_select_event_hotel').empty()
                        $('#update_select_event_hotel').append(`
                        <div class="row g-2 align-items-center form-group">
                            <div class="col-md-6">
                                <label for="update_event_hotel">Nama Hotel</label>
                                <input type="text" name="update_event_hotel" id="update_event_hotel" class="form-control" value="${res.data[0].hotel.name}">
                            </div>
                            <div class="col-md-6">
                                <label for="update_event_harga_hotel">Harga Hotel</label>
                                <input type="text" name="update_event_harga_hotel" id="update_event_harga_hotel" class="form-control" value="${res.data[0].hotel.price}">
                            </div>
                        </div>
                        `);
                    } else {
                        $('#update_select_event_hotel').empty()
                        $('#update_check_event_hotel').prop('checked', false)
                    }
                    
                    $('#update_event_description').val(res.data[0].description)
                    $('#update_event_quota').val(res.data[0].quota)
                    $('#update_event_date').val(res.data[0].date)
                    $('#update_event_date_last_register').val(res.data[0].last_register)
                },
                error: function(xhr, status, error) {
                    $('#formUpdateEvent').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                    })
                }
            })
        })

        $(document).on('click', '#update_event', function(e) {
            e.preventDefault()

            let update_event_id = $('#update_event_id').val()
            let update_event_name = $('#update_event_name').val()
            let update_event_title = $('#update_event_title').val()
            let update_event_category = $('#update_event_category').val()
            let update_event_price = $('#update_event_price').val()
            let update_event_description = $('#update_event_description').val()
            let update_event_quota = $('#update_event_quota').val()
            let update_event_date = $('#update_event_date').val()
            let update_event_date_last_register = $('#update_event_date_last_register').val()
            let update_is_active = $('#update_event_is_active').checked ? 1 : 0

            let form_data = new FormData();
            form_data.append('id', update_event_id);
            form_data.append('name', update_event_name);
            form_data.append('title', update_event_title);
            form_data.append('category', update_event_category);
            form_data.append('price', update_event_price);
            form_data.append('description', update_event_description);
            form_data.append('quota', update_event_quota);
            form_data.append('date', update_event_date);
            form_data.append('hotel', update_event_hotel);
            form_data.append('harga_hotel', update_event_harga_hotel);
            form_data.append('last_register', update_event_date_last_register);
            form_data.append('is_active', update_is_active);
            form_data.append('image', $('input[name="update_event_image"]')[0].files[0]);
            if (update_event_name == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Nama event tidak boleh kosong',
                })
            } else if (update_event_title == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Title event tidak boleh kosong',
                })
            } else if (update_event_category == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Category event tidak boleh kosong',
                })
            } else if (update_event_price == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Price event tidak boleh kosong',
                })
            } else if (update_event_description == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Description event tidak boleh kosong',
                })
            } else if (update_event_quota == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Quota event tidak boleh kosong',
                })
            } else if (update_event_date == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Date event tidak boleh kosong',
                })
            } else if (update_event_date_last_register == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Date Last Register event tidak boleh kosong',
                })
            }

            let data = {
                'id': update_event_id,
                'name': update_event_name,
                'title': update_event_title,
                'category': update_event_category,
                'price': update_event_price,
                'description': update_event_description,
                'quota': update_event_quota,
                'date': update_event_date,
                'date_end': update_event_date_last_register,
                'is_active': update_is_active
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('event-update') }}',
                type: 'post',
                data: form_data,
                // dataType: 'json',
                cache : false,
                processData: false,
                contentType: false,
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $('#formUpdateEvent').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                    // location.reload()
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: errorMessage.data.message,
                        // text: "User role cannot empty"
                    })
                }
            })
        })
    })
    $(function() {
        $(".price").keyup(function(e) {
            $(this).val(format($(this).val()));
        });
    });
    var format = function(num) {
        var str = num.toString().replace("", ""),
            parts = false,
            output = [],
            i = 1,
            formatted = null;
        if (str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }
        str = str.split("").reverse();
        for (var j = 0, len = str.length; j < len; j++) {
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
    };
</script>