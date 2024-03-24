<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}" rel="tooltip" title="Logo Navigation" data-placement="bottom"
            style="line-height: normal;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            color: #163172;
            letter-spacing: 1px;">
            <img src="{{ asset('img/logos/IPDI/LOGO_PW_IPDI_DKIJ.png') }}" class="rounded-3" alt="Logo"
                style="width: 45px; height: 45px;">
            PW IPDI DKI JAKARTA
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav mx-md-4 py-md-3">
                <li class="nav-item mx-md-4">
                    <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
                </li>
                {{-- <li class="nav-item mx-md-4 dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Event
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('jnns') }}">JNNS</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('seminar') }}">Seminar Ilmiah</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('resertifikasi') }}">Resertifikasi</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('wkd') }}">WKD</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Kongres Wilayah</a>
                        </li>
                    </ul>
                </li> --}}
                <li class="nav-item mx-md-4">
                    <a class="nav-link active" aria-current="page" href="{{ route('events') }}">Acara</a>
                </li>
                <li class="nav-item mx-md-4">
                    <a class="nav-link active" aria-current="page" href="#">Bantuan</a>
                </li>
                <li class="nav-item mx-md-4">
                    <a class="nav-link active" aria-current="page" href="#">Pengaduan</a>
                </li>

                @if (auth()->check())
                    <li class="nav-item mx-md-4 dropdown">
                        <a class="nav-link dropdown-toggle text-primary fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user me-sm-1"></i>
                            {{ Auth::user()->username }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#">Profil</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Riwayat Event</a>
                            </li>
                            {{-- <li>
                                <a class="dropdown-item" href="#">WKD</a>
                            </li> --}}
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                {{-- <a class="dropdown-item text-primary" href="{{ route('logout') }}">Logout</a> --}}
                                <form class="dropdown-item" role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="nav-link text-white font-weight-bold px-0">
                                        <span class="d-sm-inline d-none  text-primary">Log out</span>
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                @else
                    <li class="nav-item ms-md-6">
                        <a class="btn bg-gradient-primary my-1 me-2" href="{{ route('login') }}">Login</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
