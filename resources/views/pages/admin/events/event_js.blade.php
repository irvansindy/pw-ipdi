<script>
    $(document).ready(function() {
        $('#event_management').DataTable({
            processing: true,
            // serverside: false,
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
                        return '<button type="button" data-event_id="' +
    item.id +
    '" class="btn btn-outline-info btn-sm mt-2 detail_event_data" data-bs-toggle="modal" data-bs-target="#formUpdateEvent">View</button>'
                    }
                },
            ]
        })

        $('#create_event_category').select2({
            dropParent: '#formCreateEvent'
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
                    $('#create_event_category').empty()
                    $.each(res.data, function (i, data) {
                        $('#create_event_category').append('<option value="'+ data.id + '">'+ data.name + '</option>')
                    })
                }
            })
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

            let allow_image_extensions = ['jpeg', 'jpg', 'png']

            if (create_event_name == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Nama event tidak boleh kosong',
                })
            } else if (create_event_title == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Title event tidak boleh kosong',
                })
            } else if (create_event_category == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Category event tidak boleh kosong',
                })
            } else if (create_event_price == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Price event tidak boleh kosong',
                })
            } else if (create_event_description == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Description event tidak boleh kosong',
                })
            } else if (create_event_quota == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Quota event tidak boleh kosong',
                })
            } else if (create_event_date == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Date event tidak boleh kosong',
                })
            }

            let data = {
                'name': create_event_name,
                'title': create_event_title,
                'category': create_event_category,
                'price': create_event_price,
                'description': create_event_description,
                'quota': create_event_quota,
                'date': create_event_date,
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('event-store') }}',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $('#formCreateEvent').modal('toggle');
                    $('.data_tables').DataTable().ajax.reload();
                    // location.reload()
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: errorMessage.meta.message,
                        text: errorMessage.data.message,
                        // text: "User role cannot empty"
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
                    $('#update_event_name').val(res.data[0].name)
                    $('#update_event_title').val(res.data[0].title)

                    $('#update_event_category_value').val(res.data[0].category.id)
                    $('#update_event_category').empty()
                    $('#update_event_category').append('<option value="' + res.data[0].category.id + '">' + res.data[0].category.name + '</option>')
                    $.each(res.data[1], function(i, data) {
                        $('#update_event_category').append('<option value="' + data.id + '">' + data.name + '</option>')
                    })

                    $('#update_event_price').val(res.data[0].price)
                    $('#update_event_description').val(res.data[0].description)
                    $('#update_event_quota').val(res.data[0].quota)
                    $('#update_event_date').val(res.data[0].date)
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
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('event-update') }}',
                type: 'post',
                data: data,
                dataType: 'json',
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
</script>
<script>
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
