<script>
    $(document).ready(function() {
        VisiMisi()
        function VisiMisi() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("visi-misi") }}',
                type: 'GET',
                dataType: 'json',
                async: true,
                success: function(res) {
                    console.log(res.data['visi'][0]);
                    $('#list_visi').empty()
                    $('#list_visi').append(`
                        <li>${res.data['visi'][0].content}</li>
                    `)
                    $('#list_misi').empty()
                    let misi = res.data['misi']
                    $.each(misi, function(i, data) {
                        $('#list_misi').append(`
                            <li>${data.ordering + '. ' + data.content}</li>
                        `)
                    })
                }
            })
        }
    })
</script>