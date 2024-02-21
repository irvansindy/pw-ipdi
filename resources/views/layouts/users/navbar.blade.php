<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="" rel="tooltip" title="Logo Navigation" data-placement="bottom" target="_blank"
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
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item mx-md-4 dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Event
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#">JNNS</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Seminar Ilmiah</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Resertifikasi</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">WKD</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">Kongres Wilayah</a>
                        </li>
                        {{-- <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li> --}}
                    </ul>
                </li>
                <li class="nav-item mx-md-4">
                    <a class="nav-link active" aria-current="page" href="#">Bantuan</a>
                </li><li class="nav-item mx-md-4">
                    <a class="nav-link active" aria-current="page" href="#">Pengaduan</a>
                </li>
                <li class="nav-item ms-md-6">
                    <button class="btn bg-gradient-primary my-1 me-2">Login</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
