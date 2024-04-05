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
                            <button class="btn btn-primary visi_data" data-visi_id="" data-bs-toggle="modal" data-bs-target="#formCreateVisi">+ Visi</button>
                        `)
                    } else {
                        $('#content_visi').append(`
                            <p class="text-center px-3">${res.data[0].content}</p><br/>
                        `)
                        $('#content_visi').append(`
                            <button class="btn btn-primary d-flex justify-content-end visi_data" data-visi_id="${res.data[0].id}" data-bs-toggle="modal" data-bs-target="#formCreateVisi">Edit</button>
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
                url: '{{ route('fetch-mision') }}',
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
                            <button type="button" class="btn bg-gradient-primary ms-auto" data-bs-toggle="modal" data-bs-target="#formCreateMisi">Edit</button>
                                <div class="d-flex align-items-center">
                                </div>
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

        $(document).on('click', '.visi_data', function(e) {
            e.preventDefault()
            let visi_id = $(this).data('visi_id')
            let visi_content = $('#visi_content').val()
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
            })
        })
    })
</script>
