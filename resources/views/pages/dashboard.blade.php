@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title' => 'Dashboard'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard']);
    <div class="container-fluid py-4">
        <div class="row mt-2">
            <div class="col-lg-4 col-md-4 col-sm-12 mb-lg-0 mb-4">
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <h6>Jumlah Peserta</h6>
                        <div class="chart" style="margin: auto;">
                            <canvas id="member-chart" class="chart-canvas" height="300px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 mb-lg-0 mb-4">
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Pembayaran Iuran</h6>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" id="yearFilter">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            @if ($year == date('Y')) selected @endif>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Dropdown filter tahun -->
                        <div class="chart" style="margin: auto;">
                            <canvas id="iuran-chart" class="chart-canvas" height="300px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 mb-lg-0 mb-4">
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>JNNS</h6>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" id="yearFilterJnns">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            @if ($year == date('Y')) selected @endif>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Dropdown filter tahun -->
                        <div class="chart" style="margin: auto;">
                            <canvas id="jnns-chart" class="chart-canvas" height="300px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#yearFilter').select2();
            $('#yearFilterJnns').select2();
            getMember()

            function getMember() {
                $.ajax({
                    url: '{{ route('fetch-member-for-dashboard') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Menyiapkan data untuk doughnut chart berdasarkan response API
                        const data = {
                            labels: response.data.labels, // Labels dari API
                            datasets: [{
                                data: response.data.member, // Data dari API
                                backgroundColor: ['#36a2eb', '#ff6384'],
                                borderColor: ['#ffffff'],
                                borderWidth: 1
                            }]
                        };

                        // Membuat doughnut chart
                        const ctx = $('#member-chart');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: data,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                const label = data.labels[tooltipItem
                                                    .dataIndex];
                                                const value = data.datasets[0].data[
                                                    tooltipItem.dataIndex];
                                                return `${label}: ${value} peserta`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function() {
                        alert('Gagal mengambil data dari API');
                    }
                });
            }

            // iuran start
            let chart_for_iuran; // Variable untuk menyimpan instance chart

            // Fungsi untuk membuat chart
            function createChartIuran(labels, data) {
                const chartIuran = $('#iuran-chart');

                // Hapus chart lama jika ada
                if (chart_for_iuran) chart_for_iuran.destroy();

                // Buat chart_for_iuran baru
                chart_for_iuran = new Chart(chartIuran, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: ['#36a2eb', '#ff6384'],
                            borderColor: ['#ffffff'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        const label = labels[tooltipItem.dataIndex];
                                        const value = data[tooltipItem.dataIndex];
                                        return `${label}: ${value} peserta`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Fungsi untuk mengambil data dari API dan memperbarui chart
            function fetchDataIuran(year) {
                $.ajax({
                    url: '{{ route('iuran-for-dashboard') }}',
                    method: 'GET',
                    data: {
                        year: year
                    },
                    dataType: 'json',
                    success: function(response) {
                        createChartIuran(response.data.labels, response.data.iuran);
                        $('#yearFilter').val(response.data.year); // Set dropdown ke tahun yang sesuai
                    },
                    error: function() {
                        alert('Gagal mengambil data dari API');
                    }
                });
            }

            // Ambil tahun sekarang sebagai default
            const currentYear = new Date().getFullYear();

            // Ambil data awal untuk tahun sekarang
            fetchDataIuran(currentYear);

            // Event listener untuk filter tahun
            $('#yearFilter').change(function() {
                const selectedYear = $(this).val();
                fetchDataIuran(selectedYear);
            });
            // iuran end

            // jnns start
            let chart_for_jnns;
            // Fungsi untuk membuat chart
            function createChartJnns(labels, data) {
                const chartJnns = $('#jnns-chart');

                // Hapus chart lama jika ada
                if (chart_for_jnns) {
                    chart_for_jnns.destroy();
                }

                // Buat chart_for_jnns baru
                chart_for_jnns = new Chart(chartJnns, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: ['#36a2eb', '#ff6384'],
                            borderColor: ['#ffffff'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        const label = labels[tooltipItem.dataIndex];
                                        const value = data[tooltipItem.dataIndex];
                                        return `${label}: ${value} peserta`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Fungsi untuk mengambil data dari API dan memperbarui chart
            function fetchDataJnns(year) {
                $.ajax({
                    url: '{{ route('jnns-for-dashboard') }}',
                    method: 'GET',
                    data: {
                        year: year
                    },
                    dataType: 'json',
                    success: function(response) {
                        let data = response.data;
                        // Jika data kosong (0,0), tampilkan chart dengan label tetap
                        if (data.jnns[0] == 0 && data.jnns[1] == 0) {
                            data = [1, 1]; // Tambahkan nilai dummy kecil agar chart tetap muncul
                        }
                        createChartJnns(response.data.labels, response.data.jnns);
                        $('#yearFilterJnns').val(response.data
                            .year); // Set dropdown ke tahun yang sesuai
                    },
                    error: function() {
                        alert('Gagal mengambil data dari API');
                    }
                });
            }

            // Ambil tahun sekarang sebagai default
            // const currentYear = new Date().getFullYear();

            // Ambil data awal untuk tahun sekarang
            fetchDataJnns(currentYear);

            // Event listener untuk filter tahun
            $('#yearFilterJnns').change(function() {
                const selectedYear = $(this).val();
                fetchDataJnns(selectedYear);
            });
            // jnns end
        });
    </script>
@endpush
