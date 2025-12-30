<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Scan QR & Tampilkan Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-right-button {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #0d6efd;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .top-right-button:hover {
            background-color: #0b5ed7;
        }

        .container {
            text-align: center;
            margin-top: 100px;
        }

        .scan-info {
            font-size: 1.3rem;
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        .scan-info.success {
            color: #198754;
            background-color: #d1e7dd;
            border: 2px solid #198754;
        }

        .scan-info.error {
            color: #dc3545;
            background-color: #f8d7da;
            border: 2px solid #dc3545;
        }

        .scan-info.warning {
            color: #fd7e14;
            background-color: #fff3cd;
            border: 2px solid #fd7e14;
        }

        .attendance-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #0d6efd;
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="{{ route('view-list-audience') }}" class="top-right-button">List Peserta Hadir</a>

        <h1>Silakan Scan QR Code</h1>
        <p class="text-muted">Data akan tampil otomatis setelah QR terbaca.</p>
        {{-- <p class="text-info"><strong>Sistem Multi-Day Attendance Aktif</strong></p> --}}

        <form action="">
            @csrf
            <input type="text" id="qr-input" autofocus autocomplete="off">
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
            setInterval(maintainFocus, 10000);

            let timeout;

            $qrInput.on('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const value = $qrInput.val().trim();
                    if (value !== '') {
                        $result.removeClass('success error warning').text("Memproses...");

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{ route('check-audience') }}',
                            data: {
                                id: value
                            },
                            type: 'get',
                            dataType: 'json',
                            async: true,
                            success: function(response) {
                                const data = response.data;
                                const dialisis = data.user.dialisis != null ?
                                    data.user.dialisis.name : 'Tidak terdaftar';

                                const attendanceInfo = data.attendance_info;
                                const dayBadge =
                                    `<span class="attendance-badge">Hari ke-${attendanceInfo.day_number}</span>`;

                                $result.addClass('success').html(
                                    `<h4>✓ Absensi Berhasil!</h4>` +
                                    dayBadge + `<br><br>` +
                                    `<strong>No. Transaksi:</strong> ${data.number}<br>` +
                                    `<strong>Nama:</strong> ${data.user.username}<br>` +
                                    `<strong>NIRA IPDI:</strong> ${data.user.nira_ipdi}<br>` +
                                    `<strong>Center Dialisis:</strong> ${dialisis}<br>` +
                                    `<strong>Event:</strong> ${data.event.title}<br>` +
                                    `<strong>Jam Kedatangan:</strong> ${moment().tz("Asia/Jakarta").format("DD-MM-YYYY HH:mm:ss")}<br>` +
                                    `<strong>Status Pembayaran:</strong> ${data.status}<br>` +
                                    `<strong>Total Kehadiran:</strong> ${attendanceInfo.total_attendance} hari`
                                );

                                // Auto clear setelah 5 detik
                                setTimeout(() => {
                                    $result.removeClass('success').text('');
                                }, 5000);
                            },
                            error: function(xhr, status, error) {
                                console.error("Error:", xhr.responseJSON);

                                let errorMsg = "Gagal mendapatkan data.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;

                                    // Cek jika sudah absen hari ini
                                    if (xhr.status === 400 && xhr.responseJSON.message
                                        .includes('sudah')) {
                                        $result.addClass('warning').html(
                                            `<h4>⚠ Sudah Absen Hari Ini</h4>` +
                                            `<p>${xhr.responseJSON.message}</p>`
                                        );

                                        setTimeout(() => {
                                            $result.removeClass('warning').text(
                                                '');
                                        }, 4000);

                                        $qrInput.val('');
                                        return;
                                    }
                                }

                                $result.addClass('error').html(
                                    `<h4>✗ Error</h4>` +
                                    `<p>${errorMsg}</p>`
                                );

                                setTimeout(() => {
                                    $result.removeClass('error').text('');
                                }, 4000);
                            }
                        });

                        $qrInput.val('');
                    }
                }, 300);
            });
        });
    </script>
</body>

</html>
