<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Scan QR & Tampilkan Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* #qr-input {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            height: 1px;
            width: 1px;
            z-index: -1;
        } */

        .container {
            text-align: center;
            margin-top: 100px;
        }

        .scan-info {
            font-size: 1.5rem;
            margin-top: 30px;
            color: green;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Silakan Scan QR Code</h1>
        <p class="text-muted">Data akan tampil otomatis setelah QR terbaca.</p>
        <form action="">
            @csrf
            <input type="text" id="qr-input" autofocus>
            <div id="result" class="scan-info"></div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            const $qrInput = $('#qr-input');
            const $result = $('#result');

            function maintainFocus() {
                $qrInput.focus();
            }

            maintainFocus();
            setInterval(maintainFocus, 10000); // Jaga agar tetap fokus

            let timeout;

            $qrInput.on('input', function() {
                clearTimeout(timeout); // reset jika masih mengetik
                timeout = setTimeout(() => {
                    const value = $qrInput.val().trim();
                    if (value !== '') {
                        // Tampilkan hasil
                        $result.text("Data QR Code: " + value);

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{ route("check-audience") }}',
                            data: {
                                id: value
                            },
                            type: 'get',
                            dataType: 'json',
                            async: true,
                            success: function(response) {
                                // Tampilkan data di sini
                                console.log(response);
                                // Misalnya, tampilkan data di elemen lain
                                // $result.text("Data QR Code: " + response.data);
                                $result.html("Data QR Code: " + response.data.number + "<br>" +
                                    "Nama: " + response.data.user.username + "<br>" +
                                    "Nira IPDI: " + response.data.user.nira_ipdi + "<br>" +
                                    "Center Dialisis: " + response.data.user.nama_perusahaan + "<br>" +
                                    "Event/Acara: " + response.data.event.title + "<br>" +
                                    "Jam kedatangan: " + moment().tz("Asia/Jakarta").format("DD-MM-YYYY HH:mm:ss") + "<br>" +
                                    "Status Pembayaran: " + response.data.status);
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching data:", error);
                                $result.text("Gagal mendapatkan data.");
                            }
                        });

                        // Atau redirect langsung
                        // window.location.href = '/detail/' + value;

                        // Reset input
                        $qrInput.val('');
                    }
                }, 300); // delay pendek agar tidak langsung trigger saat scanner masih input
            });
        });
    </script>
</body>

</html>
