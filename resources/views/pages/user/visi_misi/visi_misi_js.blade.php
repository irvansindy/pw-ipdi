<script>
    $(document).ready(function() {
        visionAndMision()
        function visionAndMision() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-visi-misi") }}',
                type: 'get',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#content_visi').empty()
                    if (res.data['visi'].length == 0) {
                        $('#content_visi').append(`
                            <p class="text-center">Belum ada data</p><br/>
                        `)
                    } else {
                        $('#content_visi').append(`
                            <p class="text-center px-3">${res.data['visi'][0].content}</p><br/>
                        `)
                    }
                    $('.list_misi').empty()
                    if (res.data['misi'].length == 0) {
                        $('.list_misi').append(`
                            <p class="text-center">Belum ada data</p><br/>
                        `)
                    } else {
                        $.each(res.data['misi'], function(i,data) {
                            $('.list_misi').append(`
                            <div class="col-md-6">
                                <p class="text-center px-3">${data.ordering +'. '+ data.content}</p><br/>
                            </div>
                            `)
                            
                        })
                    }
                }
            })
        }
    })
</script>