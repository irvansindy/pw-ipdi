@extends('layouts.users.app', ['title' => 'Iuran Peserta'])

@section('content')
    @include('layouts.users.navbar')
    <section>
        <div class="container-fluid py-4">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="d-flex align-items-center">
                        <h6>Riwayat Iuran</h6>
                        <button type="button" class="btn bg-gradient-danger ms-auto" id="create_iuran">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" id="content_visi">
                    <div class="nav-wrapper position-relative px-2 end-0">
                        <table class="table table-hover" id="table_list_iuran" style="width: 100% !important">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Iuran</th>
                                    <th>Tahun</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- @include('pages.user.iuran.checkout') --}}
    @include('pages.user.iuran.detail')
    @include('pages.user.iuran.upload_file_iuran_transfer')
    @include('layouts.users.footer')
@endsection
@push('js')
    @include('pages.user.iuran.iuran_js')
@endpush
