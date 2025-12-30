@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'List Peserta Hadir'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'List Peserta Hadir'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md">
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center">
                            <h6>List Peserta Hadir (Multi-Day Attendance)</h6>
                        </div>
                    </div>

                    <div class="card-body px-3 pt-3 pb-2">
                        <div class="row mb-3 px-2">
                            <div class="col-md-3 mr-3 mb-3">
                                <select id="category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mr-3 mb-3">
                                <select id="year" class="form-select year-filter">
                                    <option value="">Semua Tahun</option>
                                    @for ($i = 0; $i <= 5; $i++)
                                        <option value="{{ now()->year - $i }}">{{ now()->year - $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button id="exportExcel" class="btn btn-success">
                                    Export ke Excel
                                </button>
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
                                        <th>Total Kehadiran</th>
                                        <th>Terakhir Hadir</th>
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
        .year-filter {
            width: 100px !important;
        }

        #category.form-select {
            width: 200px !important;
            height: 45px;
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .year-filter {
            width: 150px !important;
            height: 45px;
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .badge-attendance {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .attendance-1 {
            background-color: #ffc107;
            color: #000;
        }

        .attendance-2 {
            background-color: #28a745;
            color: #fff;
        }

        .attendance-3plus {
            background-color: #007bff;
            color: #fff;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            const table = $('#attendanceTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '{{ route('fetch-data-is-attendance') }}',
                    data: function(d) {
                        d.category = $('#category').val();
                        d.year = $('#year').val();
                    }
                },
                columns: [
                    {
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
                    },
                    {
                        data: 'total_attendance',
                        name: 'total_attendance',
                        render: function(data, type, row) {
                            let badgeClass = 'attendance-1';
                            if (data >= 3) {
                                badgeClass = 'attendance-3plus';
                            } else if (data == 2) {
                                badgeClass = 'attendance-2';
                            }
                            return `<span class="badge-attendance ${badgeClass}">${data} hari</span>`;
                        }
                    },
                    {
                        data: 'transaction_detail',
                        name: 'transaction_detail',
                        render: function(data, type, row) {
                            if (data && data[0] && data[0].last_attendance_at) {
                                return moment(data[0].last_attendance_at).format('DD-MM-YYYY HH:mm');
                            }
                            return '-';
                        }
                    }
                ],
                order: [[7, 'desc']] // Sort by last attendance
            });

            $('#category, #year').change(function() {
                table.ajax.reload();
            });

            $('#exportExcel').click(function () {
                const category = $('#category').val();
                const year = $('#year').val();

                let url = '{{ route('export-attendance') }}?';
                if (category) url += `category=${category}&`;
                if (year) url += `year=${year}`;

                window.location.href = url;
            });
        });
    </script>
@endpush