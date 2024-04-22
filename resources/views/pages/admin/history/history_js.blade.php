<script>
    $(document).ready(function() {
        fetchHistory()

        function fetchHistory() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-history") }}',
                type: 'get',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#content_history').empty()
                    $('.button-action-for-history').empty()
                    if (res.data.length == 0) {
                        $('#content_history').append(
                            `<p class="text-center">Belum ada data</p><br/>`)
                        $('.button-action-for-history').append(
                            `<button type="button" class="btn bg-gradient-primary ms-auto" id="add_history" data-bs-toggle="modal" data-bs-target="#add_modal_history">+ History</button>`
                            )
                    } else {
                        $('.button-action-for-history').append(
                            `<button type="button" class="btn bg-gradient-primary ms-auto detail_history" data-history_id="${res.data[0].id}" data-bs-toggle="modal" data-bs-target="#detail_modal_history">Edit</button>`
                            )
                        $('#content_history').append(`
                            <div class="mx-4">${res.data[0].content}</div>
                        `)
                    }
                }
            })
        }

        $('#input_history').summernote({
            height: 250,
            tabsize: 2,
        });

        $(document).on('click', '#add_history', function(e) {
            e.preventDefault()
            $('#form_add_history')[0].reset()
            $('#input_history').summernote('reset');
            $('.note-editor').removeClass('card')
            $('.note-btn').removeAttr('data-toggle').attr('data-bs-toggle','dropdown')
        })

        $(document).on('click', '#create_history', function(e) {
            e.preventDefault()
            let history = $('#input_history').val()
            $.ajax({
                url: '{{ route('upsert-history') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: '',
                    content: history,
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#add_modal_history').modal('toggle');
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        showCancelButton: false,
                        showConfirmButton: false,
                        text: res.meta.message,
                    })
                    setTimeout(function() { // wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Oops....",
                        text: "Data gagal dibuat!",
                    })
                }
            })
        })

        $('#detail_history').summernote({
            height: 250,
            tabsize: 2,
            // disableDragAndDrop: true
        });

        $(document).on('click', '.detail_history', function(e) {
            e.preventDefault()
            let history_id = $(this).data('history_id')
            $('#form_detail_history')[0].reset()
            $('.note-editor').removeClass('card')
            $('.note-btn').removeAttr('data-toggle').attr('data-bs-toggle','dropdown')
            $.ajax({
                url: '{{ route('detail-history') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: history_id
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('.note-editor').removeClass('card')
                    console.log(res.data.id);
                    $('#update_history').attr('data-id', res.data.id)
                    $('#detail_history').summernote('code', res.data.content)
                    // $("textarea.note-editable").val(res.data.content);
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

        $(document).on('click', '#update_history', function(e) {
            e.preventDefault()
            let id = $(this).data('id')
            // let history = $('#input_history').val()
            let history = $('#detail_history').val()
            $.ajax({
                url: '{{ route("upsert-history") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    content: history,
                },
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#detail_modal_history').modal('toggle');
                    Swal.fire({
                        icon: "success",
                        title: 'Success!',
                        showCancelButton: false,
                        showConfirmButton: false,
                        // text: res.meta.message,
                        text: 'Data berhasih diubah!',
                    })
                    setTimeout(function() { // wait for 5 secs(2)
                        window.location.reload(); // then reload the page.(3)
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        // Display validation errors using SweetAlert2
                        var errorMessage = '<ul>';
                        $.each(errors, function(key, value) {
                            errorMessage += '<li>' + value + '</li>';
                        });
                        errorMessage += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMessage
                        });
                    } else {
                        var errorMessage = JSON.parse(xhr.responseText);
                        alert(errorMessage)
                        Swal.fire({
                            icon: "error",
                            title: "Oops....",
                            // text: errorMessage.meta.message,
                            text: 'Data gagal diubah, silahkan hubungi superadmin!',
                        })
                    }
                }
            })
        })
    })
</script>
