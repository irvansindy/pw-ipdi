{{-- @extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100']) --}}
@extends('layouts.users.app')

@section('content')
    <style>
        .h1 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .profile-pic-wrapper {
            /* height: 100vh; */
            width: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .pic-holder {
            text-align: center;
            position: relative;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .pic-holder .pic {
            height: 100%;
            width: 100%;
            -o-object-fit: cover;
            object-fit: cover;
            -o-object-position: center;
            object-position: center;
        }

        .pic-holder .upload-file-block,
        .pic-holder .upload-loader {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(90, 92, 105, 0.7);
            color: #f8f9fc;
            font-size: 12px;
            font-weight: 600;
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .pic-holder .upload-file-block {
            cursor: pointer;
        }

        .pic-holder:hover .upload-file-block,
        .uploadProfileInput:focus~.upload-file-block {
            opacity: 1;
        }

        .pic-holder.uploadInProgress .upload-file-block {
            display: none;
        }

        .pic-holder.uploadInProgress .upload-loader {
            opacity: 1;
        }

        /* Snackbar css */
        .snackbar {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 14px;
            transform: translateX(-50%);
        }

        .snackbar.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @-webkit-keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }
    </style>
    @include('layouts.users.navbar')
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form role="form" method="POST" action={{ route('profile.update') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Profil</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Data Peserta</p>
                            <div class="col-auto">
                                {{-- <div class="avatar avatar-xxl position-relative">
                                    <img src="/img/team-1.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                                </div>
                                <p class="text-danger" style="font-size: 12px">file harus berjenis: jpeg/jpg/png, max size: 128kb</p> --}}
                                <div class="profile-pic-wrapper">
                                    <h1 class="h5">Photo Profile</h1>
                                    <div class="pic-holder">
                                        <!-- uploaded pic shown here -->
                                        <img id="profilePic" class="pic"
                                            src="https://source.unsplash.com/random/150x150?person">

                                        <Input class="uploadProfileInput" type="file" name="profile_pic"
                                            id="newProfilePhoto" accept="image/*" style="opacity: 0;" />
                                        <label for="newProfilePhoto" class="upload-file-block">
                                            <div class="text-center">
                                                <div class="mb-2">
                                                    <i class="fa fa-camera fa-2x"></i>
                                                </div>
                                                <div class="text-uppercase">
                                                    Update <br /> Profile Photo
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    </hr>
                                    <p class="text-danger text-center small">file photo harus berjenis: jpeg/jpg/png, max size: 1024kb</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nama</label>
                                        <input class="form-control" type="text" name="username"
                                            value="{{ old('username', auth()->user()->username) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', auth()->user()->email) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">NIK</label>
                                        <input class="form-control" type="text" name="nik"
                                            value="{{ old('nik', auth()->user()->nik) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">NIRA PPNI</label>
                                        <input class="form-control" type="text" name="nira_ppni"
                                            value="{{ old('nira_ppni', auth()->user()->nira_ppni) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">NIRA IPDI</label>
                                        <input class="form-control" type="text" name="nira_ipdi"
                                            value="{{ old('nira_ipdi', auth()->user()->nira_ipdi) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">PW IPDI Provinsi</label>
                                        <input class="form-control" type="text" name="pw_ipdi_provinsi"
                                            value="{{ old('pw_ipdi_provinsi', auth()->user()->pw_ipdi_provinsi) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nama Perusahaan</label>
                                        <input class="form-control" type="text" name="nama_perusahaan"
                                            value="{{ old('nama_perusahaan', auth()->user()->nama_perusahaan) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Central Dialisis</label>
                                        <input class="form-control" type="text" name="center_dialisis"
                                            value="{{ old('center_dialisis', auth()->user()->center_dialisis) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Alamat Central Dialisis/Perusahaan</label>
                                        <input class="form-control" type="text" name="alamat_center_dialisis_perusahaan"
                                            value="{{ old('alamat_center_dialisis_perusahaan', auth()->user()->alamat_center_dialisis_perusahaan) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">No. Hp</label>
                                        <input class="form-control" type="text" name="number_phone"
                                            value="{{ old('number_phone', auth()->user()->number_phone) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Pendidikan Terakhir</label>
                                        <input class="form-control" type="text" name="last_edication"
                                            value="{{ old('last_edication', auth()->user()->last_edication) }}">
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">First name</label>
                                        <input class="form-control" type="text" name="firstname"
                                            value="{{ old('firstname', auth()->user()->firstname) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Last name</label>
                                        <input class="form-control" type="text" name="lastname"
                                            value="{{ old('lastname', auth()->user()->lastname) }}">
                                    </div>
                                </div> --}}
                            </div>
                            {{-- <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Informasi</p> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Alamat</label>
                                        <input class="form-control" type="text" name="address"
                                            value="{{ old('address', auth()->user()->address) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Kota</label>
                                        <input class="form-control" type="text" name="city"
                                            value="{{ old('city', auth()->user()->city) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Negara</label>
                                        <input class="form-control" type="text" name="country"
                                            value="{{ old('country', auth()->user()->country) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Kode Pos</label>
                                        <input class="form-control" type="text" name="postal"
                                            value="{{ old('postal', auth()->user()->postal) }}">
                                    </div>
                                </div>
                            </div>
                            {{-- <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">About me</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">About me</label>
                                        <input class="form-control" type="text" name="about"
                                            value="{{ old('about', auth()->user()->about) }}">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </form>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="card card-profile">
                    <img src="/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                <a href="javascript:;">
                                    <img src="/img/team-2.jpg"
                                        class="rounded-circle img-fluid border border-2 border-white">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                        <div class="d-flex justify-content-between">
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Connect</a>
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-block d-lg-none"><i
                                    class="ni ni-collection"></i></a>
                            <a href="javascript:;"
                                class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block">Message</a>
                            <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-block d-lg-none"><i
                                    class="ni ni-email-83"></i></a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-center">
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">22</span>
                                        <span class="text-sm opacity-8">Friends</span>
                                    </div>
                                    <div class="d-grid text-center mx-4">
                                        <span class="text-lg font-weight-bolder">10</span>
                                        <span class="text-sm opacity-8">Photos</span>
                                    </div>
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">89</span>
                                        <span class="text-sm opacity-8">Comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <h5>
                                Mark Davis<span class="font-weight-light">, 35</span>
                            </h5>
                            <div class="h6 font-weight-300">
                                <i class="ni location_pin mr-2"></i>Bucharest, Romania
                            </div>
                            <div class="h6 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div>
                            <div>
                                <i class="ni education_hat mr-2"></i>University of Computer Science
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        {{-- @include('layouts.footers.auth.footer') --}}
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener("change", function(event) {
            if (event.target.classList.contains("uploadProfileInput")) {
                var triggerInput = event.target;
                var currentImg = triggerInput.closest(".pic-holder").querySelector(".pic")
                    .src;
                var holder = triggerInput.closest(".pic-holder");
                var wrapper = triggerInput.closest(".profile-pic-wrapper");

                var alerts = wrapper.querySelectorAll('[role="alert"]');
                alerts.forEach(function(alert) {
                    alert.remove();
                });

                triggerInput.blur();
                var files = triggerInput.files || [];
                if (!files.length || !window.FileReader) {
                    return;
                }

                if (/^image/.test(files[0].type)) {
                    var reader = new FileReader();
                    reader.readAsDataURL(files[0]);

                    reader.onloadend = function() {
                        holder.classList.add("uploadInProgress");
                        holder.querySelector(".pic").src = this.result;

                        var loader = document.createElement("div");
                        loader.classList.add("upload-loader");
                        loader.innerHTML =
                            '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
                        holder.appendChild(loader);

                        setTimeout(function() {
                            holder.classList.remove("uploadInProgress");
                            loader.remove();

                            var random = Math.random();
                            if (random < 0.9) {
                                wrapper.innerHTML +=
                                    '<div class="snackbar show" role="alert"><i class="fa fa-check-circle text-success"></i> Profile image updated successfully</div>';
                                triggerInput.value = "";
                                setTimeout(function() {
                                    wrapper.querySelector('[role="alert"]').remove();
                                }, 3000);
                            } else {
                                holder.querySelector(".pic").src = currentImg;
                                wrapper.innerHTML +=
                                    '<div class="snackbar show" role="alert"><i class="fa fa-times-circle text-danger"></i> There is an error while uploading! Please try again later.</div>';
                                triggerInput.value = "";
                                setTimeout(function() {
                                    wrapper.querySelector('[role="alert"]').remove();
                                }, 3000);
                            }
                        }, 1500);
                    };
                } else {
                    wrapper.innerHTML +=
                        '<div class="alert alert-danger d-inline-block p-2 small" role="alert">Please choose a valid image.</div>';
                    setTimeout(function() {
                        var invalidAlert = wrapper.querySelector('[role="alert"]');
                        if (invalidAlert) {
                            invalidAlert.remove();
                        }
                    }, 3000);
                }
            }
        });

        // Vanilla Script Version
        /* document.addEventListener("change", function(event) {
          if (event.target.classList.contains("uploadProfileInput")) {
            var triggerInput = event.target;
            var currentImg = triggerInput.closest(".pic-holder").querySelector(".pic").src;
            var holder = triggerInput.closest(".pic-holder");
            var wrapper = triggerInput.closest(".profile-pic-wrapper");
            
            var alerts = wrapper.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
              alert.remove();
            });

            triggerInput.blur();
            var files = triggerInput.files || [];
            if (!files.length || !window.FileReader) {
              return;
            }
            
            if (/^image/.test(files[0].type)) {
              var reader = new FileReader();
              reader.readAsDataURL(files[0]);

              reader.onloadend = function() {
                holder.classList.add("uploadInProgress");
                holder.querySelector(".pic").src = this.result;

                var loader = document.createElement("div");
                loader.classList.add("upload-loader");
                loader.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
                holder.appendChild(loader);

                setTimeout(function() {
                  holder.classList.remove("uploadInProgress");
                  loader.remove();

                  var random = Math.random();
                  if (random < 0.9) {
                    wrapper.innerHTML += '<div class="snackbar show" role="alert"><i class="fa fa-check-circle text-success"></i> Profile image updated successfully</div>';
                    triggerInput.value = "";
                    setTimeout(function() {
                      wrapper.querySelector('[role="alert"]').remove();
                    }, 3000);
                  } else {
                    holder.querySelector(".pic").src = currentImg;
                    wrapper.innerHTML += '<div class="snackbar show" role="alert"><i class="fa fa-times-circle text-danger"></i> There is an error while uploading! Please try again later.</div>';
                    triggerInput.value = "";
                    setTimeout(function() {
                      wrapper.querySelector('[role="alert"]').remove();
                    }, 3000);
                  }
                }, 1500);
              };
            } else {
              wrapper.innerHTML += '<div class="alert alert-danger d-inline-block p-2 small" role="alert">Please choose a valid image.</div>';
              setTimeout(function() {
                var invalidAlert = wrapper.querySelector('[role="alert"]');
                if (invalidAlert) {
                  invalidAlert.remove();
                }
              }, 3000);
            }
          }
        });
        */
    </script>
@endpush
