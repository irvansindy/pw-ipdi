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
                            <div class="col-md-5 mr-3 mb-3">
                                <div class="date-range-filter">
                                    <div class="date-filter-field">
                                        <label for="start_date" class="date-filter-label">Tanggal Mulai</label>
                                        <input type="date" id="start_date" class="form-control date-filter">
                                    </div>
                                    <span class="date-range-separator">s/d</span>
                                    <div class="date-filter-field">
                                        <label for="end_date" class="date-filter-label">Tanggal Akhir</label>
                                        <input type="date" id="end_date" class="form-control date-filter">
                                    </div>
                                    <button type="button" id="resetDateRange" class="btn btn-outline-secondary">
                                        Reset
                                    </button>
                                </div>
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
        #category.form-select {
            width: 200px !important;
            height: 45px;
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .date-range-filter {
            display: flex;
            align-items: end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .date-filter-field {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .date-filter-label {
            color: #67748e;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .date-filter {
            width: 160px !important;
            height: 45px;
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .date-range-separator {
            color: #67748e;
            font-weight: 600;
            margin-bottom: 12px;
            white-space: nowrap;
        }

        #resetDateRange {
            height: 45px;
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
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
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
                        data: 'latest_attendance_at',
                        name: 'latest_attendance_at',
                        render: function(data, type, row) {
                            if (data) {
                                return moment(data).format('DD-MM-YYYY HH:mm');
                            }

                            if (row.transaction_detail && row.transaction_detail[0] && row.transaction_detail[0].last_attendance_at) {
                                return moment(row.transaction_detail[0].last_attendance_at).format('DD-MM-YYYY HH:mm');
                            }

                            return '-';
                        }
                    }
                ],
                order: [[7, 'desc']] // Sort by last attendance
            });

            $('#start_date').change(function() {
                $('#end_date').attr('min', $(this).val());
            });

            $('#end_date').change(function() {
                $('#start_date').attr('max', $(this).val());
            });

            $('#category, #start_date, #end_date').change(function() {
                table.ajax.reload();
            });

            $('#resetDateRange').click(function() {
                $('#start_date, #end_date').val('').removeAttr('min max');
                table.ajax.reload();
            });

            $('#exportExcel').click(function () {
                const category = $('#category').val();
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const params = new URLSearchParams();

                if (category) params.append('category', category);
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                window.location.href = '{{ route('export-attendance') }}?' + params.toString();
            });
        });
    </script>
@endpush
