<script>
    $(document).ready(function() {
        fetchVision()
        fetchMision()
        
        function fetchVision() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-vision') }}',
                type: 'get',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#content_visi').empty()
                    if (res.data.length == 0) {
                        $('#content_visi').append(`
                            <p class="text-center">Belum ada data</p><br/>
                        `)
                        $('#content_visi').append(`
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary float-right me-4 add_visi" data-visi_id="" data-bs-toggle="modal" data-bs-target="#add_modal_visi">+ Visi</button>
                        </div>
                        `)
                    } else {
                        $('#content_visi').append(`
                            <p class="text-center px-3">${res.data[0].content}</p><br/>
                        `)
                        
                        $('#content_visi').append(`
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary float-right me-4 detail_visi" data-visi_id="${res.data[0].id}" data-bs-toggle="modal" data-bs-target="#detail_modal_visi">Edit</button>
                        </div>
                        `)
                    }
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    // alert(response_error.meta.message)
                    Swal.fire({
                        icon: "warning",
                        title: 'Alert!',
                        text: response_error.meta.message,
                    })
                }
            })
        }

        function fetchMision() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-mision") }}',
                type: 'get',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('.list_misi').empty()
                    if (res.data.length == 0) {
                        $('.list_misi').append(`
                            <p class="text-center">Belum ada data</p><br/>
                        `)
                    } else {
                        $('.list_misi').append(`
                            <ul class="list-group list-group-numbered px-2" id="data_misi"></ul>
                        `)
                        $('#data_misi').empty()
                        $.each(res.data, function(i, mision) {
                            $('#data_misi').append(`
                            <li class="list-group-item">${mision.content}</li>
                            <button type="button" class="btn bg-gradient-primary ms-auto me-3 detail_misi" data-misi_id="${mision.id}" data-bs-toggle="modal" data-bs-target="#detail_modal_misi">Edit</button>
                            `)
                        })
                    }
                },
                error: function(xhr) {
                    let response_error = JSON.parse(xhr.responseText)
                    Swal.fire({
                        icon: "warning",
                        title: 'Alert!',
                        text: response_error.meta.message,
                    })
                }
            })
        }

        $(document).on('click', '.add_visi', function(e) {
            e.preventDefault()
            $('#form_add_visi')[0].reset()
        })

        $(document).on('click', '#create_visi', function(e) {
            e.preventDefault()
            let add_content = $('#add_visi').val()
            $.ajax({
                url: '{{ route("upsert-vision") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: '',
                    content: add_content
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#add_modal_visi').modal('toggle');
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        showCancelButton: false,
                        showConfirmButton: false,
                        text: res.meta.message,
                    })
                    setTimeout(function(){// wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    // $('#modal_visi').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak bisa dibuat!",
                    })
                }
            })
        })

        $(document).on('click', '.detail_visi', function(e) {
            let visi_id = $(this).data('visi_id')
            let visi_content = $('#visi_content').val()
            $('#form_detail_visi')[0].reset()
            $.ajax({
                url: '{{ route("detail-vision") }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: visi_id,
                    content: visi_content
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_id').val(res.data.id)
                    $('#detail_visi').val(res.data.content)
                    $('#update_visi').attr('data-id', res.data.id)
                },
                error: function(xhr, status, error) {
                    $('#detail_modal_visi').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                    })
                }
            })
        })

        $(document).on('click', '#update_visi', function(e) {
            e.preventDefault()
            let id = $('#detail_id').val()
            let visi_id = $(this).data('id')
            let visi_content = $('#detail_visi').val()
            $.ajax({
                url: '{{ route("upsert-vision") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: visi_id,
                    content: visi_content
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_modal_visi').modal('toggle');
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        showCancelButton: false,
                        showConfirmButton: false,
                        text: res.meta.message,
                    })
                    setTimeout(function(){// wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    // $('#modal_visi').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak bisa diubah!",
                    })
                }
            })
        })

        $(document).on('click', '#add_visi', function(e) {
            e.preventDefault()
            $('#form_add_misi')[0].reset()
        })

        $(document).on('click', '#create_misi', function(e) {
            e.preventDefault()
            let add_content = $('#add_misi').val()
            $.ajax({
                url: '{{ route("upsert-mision") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: '',
                    content: add_content,
                    ordering: ''
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#add_modal_misi').modal('toggle');
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        showCancelButton: false,
                        showConfirmButton: false,
                        text: res.meta.message,
                    })
                    setTimeout(function(){// wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak bisa dibuat!",
                    })
                }
            })
        })

        $(document).on('click', '.detail_misi', function(e) {
            e.preventDefault()
            let misi_id = $(this).data('misi_id')
            $('#form_detail_misi')[0].reset()
            $.ajax({
                url: '{{ route("detail-mision") }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: misi_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_id').val(res.data.id)
                    $('#detail_ordering').val(res.data.ordering)
                    $('#detail_misi').val(res.data.content)
                    $('#update_misi').attr('data-id', res.data.id)
                },
                error: function(xhr, status, error) {
                    $('#detail_modal_visi').modal('toggle');
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data tidak ditemukan!",
                    })
                }
            })
        })

        $(document).on('click', '#update_misi', function(e) {
            e.preventDefault()
            let detail_id = $('#detail_id').val()
            let detail_ordering = $('#detail_ordering').val()
            let detail_misi = $('#detail_misi').val()
            $.ajax({
                url: '{{ route("upsert-mision") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: detail_id,
                    content: detail_misi,
                    ordering: detail_ordering
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_modal_misi').modal('toggle');
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        showCancelButton: false,
                        showConfirmButton: false,
                        text: res.meta.message,
                    })
                    setTimeout(function(){// wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data gagal diubah!",
                    })
                }
            })
        })
    })
</script>
