@extends('layouts.template')

@section('content')

<!-- Header -->
<div class="app-content-header py-3 mb-4 border-bottom">
    <div class="container-fluid">
        <h3 class="mb-0 fw-bold">Dashboard Admin</h3>
    </div>
</div>

<!-- Content -->
<div class="app-content">
    <div class="container-fluid">
        <div class="row g-4">

            <!-- Total Karyawan -->
            <div class="col-xl-3 col-md-6">
                <div class="card shadow rounded-4 text-white bg-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Total Karyawan</h5>
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                        <h2 class="fw-bold">{{ $jumlahKaryawan }}</h2>
                    </div>
                    <a href="{{ route('admin.dataKaryawan') }}" class="card-footer text-white d-flex justify-content-between align-items-center text-decoration-none">
                        <span>More Info</span>
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Pengajuan Izin -->
            <div class="col-xl-3 col-md-6">
                <div class="card shadow rounded-4 text-white bg-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Pengajuan Izin</h5>
                            <i class="fas fa-file-alt fa-2x opacity-75"></i>
                        </div>
                        <h2 class="fw-bold">{{ $jumlahIzinPending }}</h2>
                    </div>
                    <a href="{{ route('admin.aprovalizin') }}" class="card-footer text-white d-flex justify-content-between align-items-center text-decoration-none">
                        <span>More Info</span>
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Karyawan Baru -->
            <div class="col-xl-3 col-md-6">
                <div class="card shadow rounded-4 text-white bg-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Karyawan Baru</h5>
                            <i class="fas fa-user-plus fa-2x opacity-75"></i>
                        </div>
                        <h2 class="fw-bold">{{ $jumlahKaryawanBaru }}</h2>
                    </div>
                    <a href="{{ route('admin.dataKaryawan') }}" class="card-footer text-white d-flex justify-content-between align-items-center text-decoration-none">
                        <span>More Info</span>
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Kehadiran Hari Ini -->
            <div class="col-xl-3 col-md-6">
                <div class="card shadow rounded-4 text-white bg-secondary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Kehadiran Hari Ini</h5>
                            <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                        </div>
                        <div class="d-flex justify-content-around align-items-center text-center">
                            <div class="w-50">
                                <p class="mb-1 fw-semibold">Hadir</p>
                                <h3 class="fw-bold">{{ $jumlahHadirHariIni }}</h3>
                            </div>
                            <div class="border-start border-white px-3 w-50">
                                <p class="mb-1 fw-semibold">Alpha</p>
                                <h3 class="fw-bold">{{ $jumlahAlphaHariIni }}</h3>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.rekapan') }}" class="card-footer text-white d-flex justify-content-between align-items-center text-decoration-none">
                        <span>Lihat Rekapan</span>
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        </div> <!-- End row -->

        <!-- Diagram Statistik Kehadiran -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow rounded-4">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Statistik Kehadiran Bulan Ini</h5>
                        <canvas id="kehadiranChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- container-fluid -->
</div> <!-- app-content -->

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Bar Chart: Statistik Persentase -->
<script>
    const ctx = document.getElementById('kehadiranChart').getContext('2d');
    const kehadiranChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Hadir', 'Izin', 'Alpha'],
            datasets: [{
                label: 'Persentase',
                data: [
                    {{ round(($statistik['hadir'] / $jumlahKaryawan) * 100, 2) }},
                    {{ round(($statistik['izin'] / $jumlahKaryawan) * 100, 2) }},
                    {{ round(($statistik['alpha'] / $jumlahKaryawan) * 100, 2) }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(13, 202, 240, 0.7)',
                    'rgba(220, 53, 69, 0.7)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(13, 202, 240, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Persentase dari Total Karyawan'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + '%';
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
