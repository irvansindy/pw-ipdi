<script>
    $(document).ready(function() {
        fetchHistory()
        function fetchHistory() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("fetch-sejarah") }}',
                type: 'get',
                dataType: 'json',
                async: true,
                success: function(res) {
                    $('#content_history').empty()
                    $('.button-action-for-history').empty()
                    if (res.data.length == 0) {
                        $('#content_history').append(
                            `<p class="text-center">Belum ada data</p><br/>`)
                    } else {
                        $('#content_history').append(`
                            <div class="mx-4">${res.data[0].content}</div>
                        `)
                    }
                }
            })
        }
    })
</script>