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
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    {{-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> --}}
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
    <!-- data table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    {{-- select 2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- summer note --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css"
        integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .list-group-item {
            border: 0 !important;
        }

        /* Floating Button Styles */
        .btn-float {
            position: fixed;
            bottom: 40px;
            right: 40px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #11cdef;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 999;
            text-decoration: none;
        }

        .btn-float:hover {
            background-color: #0da5c0;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            transform: scale(1.1);
            text-decoration: none;
            color: white;
        }

        @media (max-width: 768px) {
            .btn-float {
                bottom: 20px;
                right: 20px;
                width: 55px;
                height: 55px;
                font-size: 20px;
            }
        }
    </style>
    @stack('css')
</head>

<body class="{{ $class ?? '' }}">
    @guest
        @yield('content')
    @endguest

    {{-- @auth
        @if (Auth::user()->getRoleNames()[0] == 'admin' || Auth::user()->getRoleNames()[0] == 'superadmin')
            @if (Request::is('/') || Request::is('events'))\
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @else
                <div class="min-height-300 bg-primary position-absolute w-100"></div>
                @include('layouts.navbars.auth.sidenav')
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @endif
        @elseif(Auth::user()->getRoleNames()[0] == 'user')
            <main class="main-content border-radius-lg">
                @yield('content')
            </main>
        @endif
    @endauth --}}
    @auth
        @php
            $role = Auth::user()->getRoleNames()[0];
        @endphp

        @if (in_array($role, ['admin', 'superadmin', 'ketua', 'sekretaris', 'bendahara', 'admin-verificator']))
            @if (Request::is('/') || Request::is('events'))
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @else
                <div class="min-height-300 bg-primary position-absolute w-100"></div>
                @include('layouts.navbars.auth.sidenav')
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @endif
        @elseif($role == 'user')
            <main class="main-content border-radius-lg">
                @yield('content')
            </main>
        @endif
    @endauth


    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>
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
    {{-- summer note --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"
        integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script> --}}
    <script type="text/javascript" src="https://unpkg.com/default-passive-events"></script>
    <script>
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

    <!-- Floating Button -->
    <a href="{{ route('events') }}" class="btn-float" title="Events">
        <i class="fas fa-calendar-alt"></i>
    </a>
</body>

</html>
