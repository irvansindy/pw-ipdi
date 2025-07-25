@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'List Peserta Hadir'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'List Peserta Hadir'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>List Peserta Hadir</h6>
                        </div>
                    </div>

                    <div class="card-body px-3 pt-3 pb-2">
                        <div class="row mb-3 px-2">
                            <div class="col-md-3">
                                <label for="category" class="form-label">Filter Kategori Event</label>
                                <select id="category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive p-2">
                            <table class="table align-items-center mb-0" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Transaksi</th>
                                        <th>Token</th>
                                        <th>Nama</th>
                                        <th>Event</th>
                                        <th>Kategori</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        /* Tetap batasi dropdown lain */
        .year-filter {
            width: 100px !important;
        }

        /* Khusus untuk dropdown kategori */
        #category.form-select {
            width: 250px !important;
            height: 45px;
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

    </style>
@endpush
@push('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const table = $('#attendanceTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{{ route('fetch-data-is-attendance') }}', // ganti dengan nama route kamu
                    data: function(d) {
                        d.category = $('#category').val();
                    }
                },
                columns: [{
                        data: null,
                        name: 'no',
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'number',
                        name: 'number',
                        render: data => data ? data : 'Not set'
                    },
                    {
                        data: 'token_register',
                        name: 'token_register',
                        render: data => data ? data : 'Not set'
                    },
                    {
                        data: 'user.username',
                        name: 'user.username',
                        render: data => data ? data : 'Not set'
                    },
                    {
                        data: 'event.title',
                        name: 'event.title',
                        render: data => data ? data : 'Not set'
                    },
                    {
                        data: 'event.category.name',
                        name: 'event.category.name',
                        render: data => data ? data : 'Not set'
                    }
                ],

            });

            $('#category').change(function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
