<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 ps" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="false" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('dashboard') }}">
            {{-- <img src="./img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo"> --}}
            <span class="ms-5 font-weight-bold">PW-IPDI DKI Jakarta</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto mb-3" id="sidenav-collapse-main">
        <ul class="navbar-nav" id="master_menu">
            @php
                $user = Auth::user();
                $role = $user->roles->first();
                @endphp
                @if ($role->name == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'transaction' ? 'active' : '' }}" href="{{ route('transaction') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-money-coins text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Transaksi Event</span>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'users' ? 'active' : '' }}" href="{{ route('users') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'roles' ? 'active' : '' }}" href="{{ route('roles') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Roles</span>
                    </a>
                </li>
                <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'vision-mision' ? 'active' : '' }}" href="{{ route('vision-mision') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Visi Misi</span>
                    </a>
                </li>
                <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'history' ? 'active' : '' }}" href="{{ route('history') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Sejarah</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'organization' ? 'active' : '' }}" href="{{ route('organization') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Organisasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'news-management' ? 'active' : '' }}" href="{{ route('news-management') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-newspaper text-dark text-sm opacity-10 mb-2"></i>
                        </div>
                        <span class="nav-link-text ms-1">Berita</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'event' ? 'active' : '' }}" href="{{ route('event') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'iuran' ? 'active' : '' }}" href="{{ route('iuran') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-money-coins text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Transaksi Iuran</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'transaction' ? 'active' : '' }}" href="{{ route('transaction') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-money-coins text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Transaksi Event</span>
                    </a>
                </li>

                @endif
        </ul>
    </div>
</aside>
