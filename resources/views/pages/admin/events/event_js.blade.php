<script>
    $(document).ready(function() {
        $('#event_management').DataTable()

        $('#create_event_category').select2({
            dropParent: '#formCreateEvent'
        })
        $('#update_event_category').select2({
            dropParent: '#formUpdateEvent'
        })

        $(document).on('click', '#for_create_event_data', function(e) {
            e.preventDefault()
            $('#form_create_event')[0].reset()
        })

        $(document).on('click', '#create_event', function(e) {
            e.preventDefault()

            let create_event_name = $('#create_event_name').val()
            let create_event_title = $('#create_event_title').val()
            let create_event_category = $('#create_event_category').val()
            let create_event_description = $('#create_event_description').val()
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
            } else if (create_event_description == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Description event tidak boleh kosong',
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
                'description': create_event_description,
                'date': create_event_date,
            }

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("event-store") }}',
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
                url: '{{ route("event-detail") }}',
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
                    $('#update_event_category').append('<option value="'+res.data[0].category.id+'">'+res.data[0].category.name+'</option>')
                    $.each(res.data[1], function(i, data) {
                        $('#update_event_category').append('<option value="'+data.id+'">'+data.name+'</option>')
                    })

                    $('#update_event_description').val(res.data[0].description)
                    $('#update_event_date').val(res.data[0].date)
                },
                error: function(xhr, status, error) {
                    $('#formUpdateEvent').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                        // title: "Oops....",
                        // text: errorMessage.meta.message,
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
            let update_event_description = $('#update_event_description').val()
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
            } else if (update_event_description == '') {
                Swal.fire({
                    icon: "error",
                    title: 'Oops...',
                    text: 'Description event tidak boleh kosong',
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
                'description': update_event_description,
                'date': update_event_date,
            }

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("event-update") }}',
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