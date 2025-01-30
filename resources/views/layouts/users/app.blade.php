<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png"> --}}
    {{-- <link rel="icon" type="image/png" href="/img/favicon.png"> --}}
    <link rel="icon" type="image/png" href="{{ asset('img/logos/IPDI/LOGO_PW_IPDI_DKIJ.png') }}">
    <title>
        @isset($title)
            {{ $title }}
        @endisset
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    {{-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/argon-dashboard.css" rel="stylesheet" />
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* .carousel-inner {
            height: 0;
            padding-bottom: 43%;
        }
        .carousel-item {
            position: absolute !important;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
        .carousel-item img {
            height: 100%;
            object-fit: fill;
        } */
    </style>
    @stack('css')
</head>

<body class="{{ $class ?? '' }}">
    @guest
        @yield('content')
    @endguest

    @auth
        <main class="main-content border-radius-lg">
            @yield('content')
        </main>
    @endauth

    <!--   Core JS Files   -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/argon-dashboard.js"></script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- moment js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"
        integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- font awesome --}}
    {{-- <script src="https://example.com/fontawesome/v6.5.1/js/all.js" data-auto-replace-svg="nest"></script> --}}
    {{-- qrcode --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- html canvas --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js" integrity="sha512-sn/GHTj+FCxK5wam7k9w4gPPm6zss4Zwl/X9wgrvGMFbnedR8lTUSLdsolDRBRzsX6N+YgG6OWyvn9qaFVXH9w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/scannerdetection/1.2.0/jquery.scannerdetection.compatibility.js" integrity="sha512-YQRu5Y2eFL0L4LrZk2rGxCH5nD8G9ppSaQIqg5mmB/SLd8c0qTJ/cEua3ETXzXzWITvt4x1tiTXFC4M5bJBA4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script>
    <script>
        // Skrip untuk memulai pemindai QR code
        // let scanner = new Instascan.Scanner({
        //     video: document.getElementById('preview')
        // });
        // scanner.addListener('scan', function(content) {
        //     // Menampilkan SweetAlert ketika QR code di-scan
        //     Swal.fire({
        //         title: 'QR Code Scanned!',
        //         text: content,
        //         icon: 'success',
        //         confirmButtonText: 'OK'
        //     });
        // });
        // Instascan.Camera.getCameras().then(function(cameras) {
        //     if (cameras.length > 0) {
        //         scanner.start(cameras[0]);
        //     } else {
        //         console.error('No cameras found.');
        //     }
        // }).catch(function(e) {
        //     console.error(e);
        // });

        function CreateOrUpdateOrDelete(url, method, data, id_modal) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: method,
                data: data,
                dataType: 'json',
                // processData: false,
                // contentType: false,
                async: true,
                cache: false,
                success: function(res) {
                    Swal.fire({
                        icon: "success",
                        title: res.meta.message,
                    });
                    $(id_modal).modal('toggle');
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
        }
        
    </script>
    @stack('js')
</body>

</html>
