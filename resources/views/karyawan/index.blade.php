@extends('layouts.templatekaryawan')

@section('content')
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-bottom">
        <h3 class="mb-0 fw-bold">Dashboard Karyawan</h3>
    </div>

    {{-- Selamat Datang --}}
    <div class="text-center mb-4 mt-3">
        <h4 class="fw-semibold">Selamat Datang, {{ Auth::user()->name }} 👋</h4>
        <p class="text-muted">Semoga harimu menyenangkan bersama Toko Dila!</p>
    </div>

    <div class="row g-3 mb-4 justify-content-center text-center">
    {{-- Kotak Status Akun --}}
    <div class="col-md-6 col-lg-4">

            <div class="small-box shadow-sm 
                {{ $statusKaryawan == 'aktif' ? 'bg-success' : ($statusKaryawan == 'pending' ? 'bg-warning' : 'bg-danger') }}" 
                style="border-radius: 12px; min-height: 130px;">
                <div class="inner">
                    <h5 class="fw-bold text-white mb-2">Status Akun</h5>
                    @if ($statusKaryawan === 'aktif')
                        <p class="text-white mb-1">Akun sudah <strong>aktif</strong>. Silakan gunakan semua fitur.</p>
                    @elseif ($statusKaryawan === 'pending')
                        <p class="text-white mb-1">Akun masih <strong>menunggu persetujuan</strong> dari admin.</p>
                    @elseif ($statusKaryawan === 'ditolak')
                        <p class="text-white mb-1">Akun Anda <strong>ditolak</strong>. Hubungi admin.</p>
                    @endif
                </div>
                <div class="icon">
                    <i class="bi bi-person-badge" style="font-size: 32px; color: rgba(255,255,255,0.9);"></i>
                </div>
                <a href="{{ route('karyawan.profil') }}" class="small-box-footer text-white text-decoration-none">
                    Lihat Profil <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
{{-- Kotak Pengajuan Izin --}}
<div class="col-xl-4 col-md-6">
    <div class="small-box shadow-sm bg-primary" style="border-radius: 12px; min-height: 130px;">
        <div class="inner">
            <h5 class="fw-bold text-white mb-2">Pengajuan Izin</h5>
            @if($izin->count())
                <p class="text-white mb-1">Ada <strong>{{ $izin->count() }}</strong> izin pending.</p>
            @else
                <p class="text-white mb-1">Tidak ada pengajuan izin baru.</p>
            @endif
        </div>
        <div class="icon">
            <i class="bi bi-calendar-check" style="font-size: 32px; color: rgba(255,255,255,0.9);"></i>
        </div>
        <a href="{{ route('karyawan.izin') }}" class="small-box-footer text-white text-decoration-none">
            Lihat Detail <i class="bi bi-arrow-right-circle"></i>
        </a>
    </div>
</div>


    {{-- Kalender Kehadiran --}}
    <div class="bg-grey p-4 rounded-4 shadow mb-4">
        <form method="GET" action="{{ route('karyawan.dashboard') }}" class="mb-4 d-flex align-items-center gap-2">
    <label for="bulan" class="fw-semibold mb-0">Pilih Bulan:</label>
    <input type="month" name="bulan" id="bulan" value="{{ $bulan }}" class="form-control" style="max-width: 200px;">
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>
        <h5 class="text-lg fw-semibold mb-3">Kehadiran Bulan {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</h5>

        <div class="d-grid" style="grid-template-columns: repeat(7, 1fr); gap: 8px;">
            @php
                $daysInMonth = \Carbon\Carbon::parse($bulan)->daysInMonth;
                $startDay = \Carbon\Carbon::parse($bulan)->startOfMonth()->dayOfWeekIso;
            @endphp

            {{-- Kosong sebelum tanggal 1 --}}
            @for ($i = 1; $i < $startDay; $i++)
                <div></div>
            @endfor

            {{-- Tanggal dan status --}}
             @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $tanggalObj = \Carbon\Carbon::parse($bulan)->setDay($day);
                    $tanggal = $tanggalObj->format('Y-m-d');
                    $status = $kalender[$tanggal] ?? null;

                    $isSunday = $tanggalObj->isSunday();

                    $warna = match(true) {
                        $status === 'Hadir' => 'bg-success text-white',
                        $status === 'Alpha' => 'bg-danger text-white',
                        $isSunday => 'bg-secondary text-white', // warna untuk hari Minggu / libur
                        default => 'bg-light text-dark',
                    };
                @endphp

                <div class="rounded p-2 text-center {{ $warna }}">
                    {{ $day }}
                </div>
            @endfor

        </div>

        <div class="mt-4">
            <span class="badge bg-success me-2">Hadir: {{ $statistik['hadir'] }}</span>
            <span class="badge bg-danger">Alpha: {{ $statistik['alpha'] }}</span>
        </div>
    </div>

    {{-- Toko Dila 
    <div class="card mt-4 shadow-lg border-0">
        <img src="{{ asset('imgFoto/tokoDila.jpeg') }}" class="card-img-top rounded-top" alt="Foto Toko" style="max-height: 400px; object-fit: cover;">

        <div class="card-body">
            <h5 class="card-title fw-bold">Toko Dila Travel</h5>
            <p class="card-text">
                Selamat datang di dashboard karyawan Toko Dila. Ini adalah tampilan toko fisik kami. Gunakan menu di samping untuk fitur lainnya.
            </p>
            <p class="card-text"><small class="text-muted">Terakhir diperbarui: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small></p>

            <div class="mt-4">
                <h6 class="fw-semibold">Lokasi Toko:</h6>
                <div class="ratio ratio-16x9">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127492.51097309648!2d128.2880136!3d-3.6090083!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d6c93b483adc283%3A0x94cce109a2904713!2sDilla%20Travel!5e0!3m2!1sid!2sid!4v1717518909245!5m2!1sid!2sid"
                        width="600" height="450" style="border:0;" allowfullscreen loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
--}}
@endsection
