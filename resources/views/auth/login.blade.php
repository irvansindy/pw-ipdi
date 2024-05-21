@extends('layouts.app')

@section('content')
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Sign In</h4>
                                    <p class="mb-0">Enter your email and password to sign in</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg"
                                                value="" aria-label="Email">
                                            @error('email')
                                                <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                                            @enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg"
                                                aria-label="Password" value="">
                                            @error('password')
                                                <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                                            @enderror
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                                {{-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                        Forgot you password? Reset your password
                                        <a href="{{ route('reset-password') }}"
                                            class="text-primary text-gradient font-weight-bold">here</a>
                                    </p>
                                </div> --}}
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Don't have an account?
                                        <a href="{{ route('register') }}"
                                            class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <h4 class="mt-2 text-black font-weight-bolder position-relative">Selamat Datang</h4>
                            <p class="text-black position-relative">
                                Di Website <br> Pengurus Wilayah Ikatan Perawat Dialisis Indonesia Prov.DKI Jakarta
                            </p>
                            
                            <div class="position-relative h-100 m-3 px-7 mb-5 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="margin-left:130px !important;
                            background-image: url('{{ asset("img/logos/IPDI/logo_login.png") }}'); background-size: cover; width: 350px;
                            height: 300px;">
                                {{-- <span class="mask bg-gradient-primary opacity-2"></span> --}}
                                {{-- <img src="{{ asset("img/logos/IPDI/LOGO_PW_IPDI_DKIJ.png") }}" class="rounded-3" alt="" srcset=""> --}}
                            </div>
                            <p class="text-black position-relative">
                                Jl. Abdul Rahman Saleh Raya No.24, RT.10/RW.5, Senen, Kec. Senen, <br> Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10410
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
