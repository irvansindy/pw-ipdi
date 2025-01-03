{{-- @extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100']) --}}
@extends('layouts.users.app', ['title' => 'Profile'])

@section('content')
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
                                {{-- <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- <p class="text-uppercase text-sm">Data Peserta</p> --}}
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="avatar avatar-xxl position-relative">
                                            {{-- <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" alt="profile_image" class="w-100 profile_image border-radius-lg shadow-sm"> --}}
                                            <img src="{{ auth()->user()->profile != null ? asset('storage/' . auth()->user()->profile) : 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png' }}"
                                                alt="profile_image" class="w-100 profile_image border-radius-lg shadow-sm">
                                        </div>
                                        <p class="text-danger" style="font-size: 12px">file harus berjenis: jpeg/jpg/png,
                                            max size: 128kb</p>
                                        <h6>Upload a different photo...</h6>
                                        <input type="file" class="text-center center-block file-upload"
                                            name="photo_profile">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <label for="sertifikat_start_date"
                                                        class="form-control-label">Tanggal Dikeluarkan</label>
                                                    <input class="form-control" type="date"
                                                        name="sertifikat_start_date" id="sertifikat_start_date"
                                                        value="{{ old('sertifikat_start_date', auth()->user()->sertifikat_start_date) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <label for="sertifikat_end_date" class="form-control-label">Tanggal
                                                        Kadaluarsa</label>
                                                    <input class="form-control" type="date"
                                                        name="sertifikat_end_date" id="sertifikat_end_date"
                                                        value="{{ old('sertifikat_end_date', auth()->user()->sertifikat_end_date) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <label for="sertifikat_file"
                                                        class="form-control-label">Sertifikat</label>
                                                    <input class="form-control" type="file" name="sertifikat_file"
                                                        id="sertifikat_file"
                                                        value="{{ old('sertifikat_file', auth()->user()->sertifikat_file) }}">
                                                        <a href="{{ asset(auth()->user()->sertifikat_file == null ? '#' : auth()->user()->sertifikat_file) }}">
                                                            <span class="text-info" style="font-size: 14px; cursor:pointer;">Link untuk melihat sertifikat</span>
                                                        </a>
                                                </div>
                                            </div>
                                            <p class="text-info" style="font-size: 18px !important;" id="countdown-display"></p>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="profile-pic-wrapper">
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
                                    <p class="text-danger text-center small">file photo harus berjenis: jpeg/jpg/png, max
                                        size: 1024kb</p>
                                </div> --}}
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
                                        <label for="jenis_kelamin" class="form-control-label">Jenis Kelamin</label>
                                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                            <option value="">Pilih Salah Satu</option>
                                            <option value="L" {{ auth()->user()->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="P" {{ auth()->user()->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nik" class="form-control-label">NIK</label>
                                        <input class="form-control" type="text" name="nik" id="nik"
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
                                        <input type="hidden" name="pw_ipdi_provinsi_current"
                                            id="pw_ipdi_provinsi_current" value="{{ auth()->user()->pw_ipdi_provinsi }}">
                                        <select class="form-control" name="pw_ipdi_provinsi" id="pw_ipdi_provinsi">
                                            @forelse ($masterProvinsi as $provinsi)
                                                <option value="{{ $provinsi->id }}"
                                                    {{ $provinsi->id == auth()->user()->pw_ipdi_provinsi ? 'selected' : '' }}>
                                                    {{ $provinsi->name }}</option>
                                            @empty
                                                <option value="hidden">Select One</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nama Perusahaan</label>
                                        <input class="form-control" type="text" name="nama_perusahaan"
                                            value="{{ old('nama_perusahaan', auth()->user()->nama_perusahaan) }}">
                                    </div>
                                </div>
                                <div class="col-md-6" id="select_kabupaten">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">PW IPDI Kota</label>
                                        <select class="form-control" name="pw_ipdi_kabupaten" id="pw_ipdi_kabupaten">
                                            @if (auth()->user()->pw_ipdi_kabupaten == null)
                                                <option value="hidden">Select One</option>
                                            @endif
                                            @forelse ($masterKabupaten as $kabupaten)
                                                <option value="{{ $kabupaten->id }}"
                                                    {{ $kabupaten->id == auth()->user()->pw_ipdi_kabupaten ? 'selected' : '' }}>
                                                    {{ $kabupaten->name }}</option>
                                            @empty
                                                <option value="hidden">Select One</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Center Dialisis</label>
                                        <select class="form-control" name="center_dialisis" id="center_dialisis">
                                            @if (auth()->user()->center_dialisis == null)
                                                <option value="hidden">Select One</option>
                                            @endif
                                            @forelse ($masterDialisis as $dialisis)
                                                <option value="{{ $dialisis->id }}"
                                                    {{ $dialisis->id == auth()->user()->center_dialisis ? 'selected' : '' }}>
                                                    {{ $dialisis->name }}</option>
                                            @empty
                                                <option value="hidden">Select One</option>
                                            @endforelse
                                        </select>
                                        {{-- <input class="form-control" type="text" name="center_dialisis" value="{{ old('center_dialisis', auth()->user()->center_dialisis) }}"> --}}
                                            {{-- @php
                                                dd($masterDialisis);
                                            @endphp --}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Alamat Center
                                            Dialisis/Perusahaan</label>
                                        <input class="form-control" type="text"
                                            name="alamat_center_dialisis_perusahaan"
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
                                        <label for="example-text-input" class="form-control-label">Pendidikan
                                            Terakhir</label>
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

                            <div class="d-flex align-items-center mt-4">
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#pw_ipdi_provinsi').select2()
        $('#pw_ipdi_kabupaten').select2()
        $('#center_dialisis').select2()
        
        $(document).ready(function() {

            if ($('#pw_ipdi_provinsi_current').val() == 17) {
                $('#select_kabupaten').show()
            } else {
                $('#select_kabupaten').hide()
            }
            $(document).on('change', '#pw_ipdi_provinsi', function() {
                if ($(this).val() == 17) {
                    $('#select_kabupaten').show()
                } else {
                    $('#select_kabupaten').hide()
                    $('#pw_ipdi_kabupaten').val('')
                }
            })
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.profile_image').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(".file-upload").on('change', function() {
                readURL(this);
            });

            function calculateCountdown(startDate, endDate) {
            // Pastikan tanggal ada
            if (!startDate || !endDate) return;

            const start = new Date(startDate);
            const end = new Date(endDate);
            const now = new Date();

            // Hitung selisih hari
            const timeDiff = end - now;
            if (timeDiff <= 0) {
                $("#countdown-display").text("Tanggal kadaluarsa telah lewat.");
                return;
            }

            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

            $("#countdown-display").text(`Masa Berlaku Sertifikat: ${days} hari, ${hours} jam, ${minutes} menit, ${seconds} detik.`);
        }

        // Update countdown setiap detik
        setInterval(function () {
            const startDate = $("#sertifikat_start_date").val();
            const endDate = $("#sertifikat_end_date").val();
            calculateCountdown(startDate, endDate);
        }, 1000);

        // Trigger manual ketika ada perubahan tanggal
        $("#sertifikat_start_date, #sertifikat_end_date").on("change", function () {
            const startDate = $("#sertifikat_start_date").val();
            const endDate = $("#sertifikat_end_date").val();
            calculateCountdown(startDate, endDate);
        });
        })
    </script>
@endpush
