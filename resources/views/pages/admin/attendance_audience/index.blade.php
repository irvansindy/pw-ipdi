<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Scan QR & Tampilkan Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .top-right-button {
            position: absolute;
            top: 20px;
            right: 30px;
            background-color: #fff;
            color: #667eea;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .top-right-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: #764ba2;
        }

        .container {
            text-align: center;
            margin-top: 80px;
        }

        .scanner-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }

        .scan-info {
            font-size: 1.1rem;
            margin-top: 30px;
            padding: 25px;
            border-radius: 12px;
            background-color: #f8f9fa;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .scan-info.success {
            color: #198754;
            background: linear-gradient(135deg, #d1f4e0 0%, #a7e9c4 100%);
            border: 2px solid #198754;
        }

        .scan-info.error {
            color: #dc3545;
            background: linear-gradient(135deg, #ffe0e0 0%, #ffc0c0 100%);
            border: 2px solid #dc3545;
        }

        .scan-info.warning {
            color: #fd7e14;
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: 2px solid #fd7e14;
        }

        .scan-info.completed {
            color: #0d6efd;
            background: linear-gradient(135deg, #cfe2ff 0%, #9ec5fe 100%);
            border: 2px solid #0d6efd;
            animation: celebration 0.6s ease-in-out;
        }

        @keyframes celebration {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .attendance-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            margin: 10px 5px;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .progress-bar-container {
            width: 100%;
            background: #e9ecef;
            border-radius: 10px;
            height: 30px;
            margin: 15px 0;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .info-badge {
            display: inline-block;
            padding: 5px 12px;
            background: #e7f3ff;
            color: #0d6efd;
            border-radius: 15px;
            font-size: 0.85rem;
            margin: 5px;
            font-weight: 500;
        }

        .celebration-icon {
            font-size: 3rem;
            margin-bottom: 10px;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .data-row {
            margin: 8px 0;
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="{{ route('view-list-audience') }}" class="top-right-button">📋 List Peserta Hadir</a>

        <div class="scanner-card">
            <h1>🎯 Scan QR Code</h1>
            <p class="subtitle">Data akan tampil otomatis setelah QR terbaca</p>
            <div class="info-badge">✅ Multi-Day Attendance System</div>

            <form action="">
                @csrf
                <input type="text" id="qr-input" autofocus style="">
                {{-- <input type="text" id="qr-input" autofocus style="position: absolute;"> --}}
                <div id="result" class="scan-info">
                    <div style="color: #999;">
                        <div style="font-size: 3rem; margin-bottom: 10px;">📱</div>
                        <div>Silakan scan QR code untuk melakukan absensi</div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#qr-input').on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault()
                    return false
                }
            })

            $('form').on('submit', function (e) {
                e.preventDefault()
            })

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
                        $result.removeClass('success error warning completed')
                            .html('<div style="color: #999;">⏳ Memproses...</div>');

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

                                if (!response || !response.meta || response.meta
                                    .code !== 200) {
                                    showError('Response tidak valid dari server');
                                    return;
                                }

                                const data = response.data || {};
                                const info = data.attendance_info || {};
                                const metaMessage = response.meta.message ||
                                    'Absensi berhasil';

                                const user = data.user || {};
                                const event = data.event || {};

                                const dialisis = user.dialisis?.name ||
                                    'Tidak terdaftar';

                                const total = info.total_attendance || 0;
                                const max = info.max_attendance || 1;
                                const progressPercent = Math.min((total / max) * 100,
                                    100);

                                let htmlContent = '';

                                // =====================
                                // HEADER (STATUS)
                                // =====================
                                if (info.is_completed) {
                                    $result.addClass('completed');

                                    htmlContent = `
            <div class="celebration-icon">🎉</div>
            <h4 style="margin-bottom: 15px;">${metaMessage}</h4>
            <div class="attendance-badge">
                Hari ke-${info.user_attendance_number} dari ${max}
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: 100%">
                    100% Selesai ✓
                </div>
            </div>
        `;
                                } else {
                                    $result.addClass('success');

                                    htmlContent = `
            <h4>✓ Absensi Berhasil Dicatat</h4>
            <div class="attendance-badge">
                Hari ke-${info.user_attendance_number} dari ${max}
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: ${progressPercent}%">
                    ${total}/${max} hari
                </div>
            </div>
            ${
                info.remaining_attendance > 0
                    ? `<div style="margin: 10px 0; color: #666;">
                            Sisa ${info.remaining_attendance} hari lagi
                           </div>`
                    : ''
            }
        `;
                                }

                                // =====================
                                // DETAIL USER & EVENT
                                // =====================
                                htmlContent += `
        <div style="margin-top: 20px; text-align: left; width: 100%;">
            <div class="data-row"><strong>Nama:</strong> ${user.username || '-'}</div>
            <div class="data-row"><strong>NIRA IPDI:</strong> ${user.nira_ipdi || '-'}</div>
            <div class="data-row"><strong>Center Dialisis:</strong> ${dialisis}</div>
            <div class="data-row"><strong>Event:</strong> ${event.title || '-'}</div>
            <div class="data-row">
                <strong>Event Day:</strong>
                Hari ke-${info.event_day_number}
                (${info.event_info?.start_date || '-'} - ${info.event_info?.end_date || '-'})
            </div>
            <div class="data-row">
                <strong>Waktu Absen:</strong>
                ${moment().tz("Asia/Jakarta").format("DD-MM-YYYY HH:mm:ss")}
            </div>
        </div>
    `;

                                $result.html(htmlContent);

                                // =====================
                                // AUTO CLEAR
                                // =====================
                                setTimeout(() => {
                                    $result
                                        .removeClass('success completed')
                                        .html(`
                <div style="color: #999;">
                    <div style="font-size: 3rem; margin-bottom: 10px;">📱</div>
                    <div>Silakan scan QR code untuk melakukan absensi</div>
                </div>
            `);
                                }, info.is_completed ? 8000 : 6000);
                            },
                            error: function(xhr) {
                                const errorData = xhr.responseJSON;

                                if (!errorData || !errorData.meta) {
                                    showError('Terjadi kesalahan. Silakan coba lagi.');
                                    return;
                                }

                                const code = errorData.meta.code;
                                const title = errorData.meta.message ||
                                    'Terjadi kesalahan';
                                const message = errorData.data?.message || title;

                                if (code === 400) {

                                    if (message.includes('sudah melakukan absensi')) {
                                        $result.addClass('warning').html(`
                <h4>⚠️ Sudah Absen Hari Ini</h4>
                <p style="margin: 15px 0;">${message}</p>
                <div style="color: #666;">Silakan kembali besok untuk absen lagi</div>
            `);

                                    } else if (message.includes('batas maksimal')) {
                                        $result.addClass('completed').html(`
                <div class="celebration-icon">🏆</div>
                <h4>Absensi Sudah Lengkap!</h4>
                <p style="margin: 15px 0;">${message}</p>
                <div style="color: #666;">Terima kasih atas partisipasi Anda!</div>
            `);

                                    } else if (message.includes(
                                            'belum dikonfigurasi')) {
                                        $result.addClass('error').html(`
                <h4>⚙️ Konfigurasi Event Belum Lengkap</h4>
                <p style="margin: 15px 0;">${message}</p>
                <div style="color: #666;">Silakan hubungi admin</div>
            `);

                                    } else if (message.includes('belum dimulai')) {
                                        $result.addClass('warning').html(`
                <h4>⏰ Event Belum Dimulai</h4>
                <p style="margin: 15px 0;">${message}</p>
            `);

                                    } else if (message.includes('sudah berakhir')) {
                                        $result.addClass('error').html(`
                <h4>⏱️ Event Sudah Berakhir</h4>
                <p style="margin: 15px 0;">${message}</p>
            `);

                                    } else {
                                        showError(message);
                                    }

                                } else {
                                    showError(title);
                                }

                                setTimeout(() => {
                                    $result.removeClass(
                                            'warning error completed')
                                        .html(`
                <div style="color: #999;">
                    <div style="font-size: 3rem; margin-bottom: 10px;">📱</div>
                    <div>Silakan scan QR code untuk melakukan absensi</div>
                </div>
            `);
                                }, 5000);
                            }

                        });

                        $qrInput.val('');
                    }
                }, 300);
            });

            function showError(message) {
                $result.addClass('error').html(`
                    <h4>✗ Error</h4>
                    <p style="margin: 15px 0;">${message}</p>
                `);
            }
        });
    </script>
</body>

</html>
