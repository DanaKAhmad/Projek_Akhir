@extends('layouts.template')

@section('content')
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-bottom">
        <h3 class="mb-0 fw-bold">Rekapan Absensi</h5>
    </div>
    <div class="card-body">

        <!-- Filter -->
        <form method="GET" action="{{ route('admin.rekapan') }}" class="row g-2 align-items-end mb-4">
            <div class="col-md-3">
                <label for="bulan" class="form-label fw-semibold">Pilih Bulan</label>
                <input type="month" name="bulan" id="bulan" value="{{ request('bulan', now()->format('Y-m')) }}" class="form-control">
            </div>
            <div class="col-md-5">
                <label for="search" class="form-label fw-semibold">Cari Karyawan</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama karyawan">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">Cari</button>

            </div>
        </form>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle table-hover">
                <thead class="table-light fw-semibold">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Karyawan</th>
                        <th>Foto Masuk</th>
                        <th>Jam Masuk</th>
                        <th>Foto Pulang</th>
                        <th>Jam Pulang</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($absen as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td>
                            @if (!empty($item['foto_masuk']))
                                <img src="{{ asset($item['foto_masuk']) }}" width="70" class="img-thumbnail shadow-sm" alt="Foto Masuk">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item['jam_masuk'] ?? '-' }}</td>
                        <td>
                            @if (!empty($item['foto_pulang']))
                                <img src="{{ asset($item['foto_pulang']) }}" width="70" class="img-thumbnail shadow-sm" alt="Foto Pulang">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item['jam_pulang'] ?? '-' }}</td>
                        <td>{{ $item['keterangan'] ?? '-' }}</td>
                        <td>
                            @if (!empty($item['lampiran']))
                                <a href="{{ asset('storage/' . $item['lampiran']) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                       <td>
                            @php
                        $status = strtolower($item['status'] ?? '-');
                        if (str_contains($status, 'izin')) {
                            $statusClass = 'bg-info';
                            $statusText = 'izin';
                        } elseif ($status === 'hadir') {
                            $statusClass = 'bg-success';
                            $statusText = 'hadir';
                        } elseif ($status === 'alpha') {
                            $statusClass = 'bg-danger';
                            $statusText = 'alpha';
                        } else {
                            $statusClass = 'bg-secondary';
                            $statusText = $status;
                        }
                    @endphp
                    <span class="badge {{ $statusClass }} fs-7 fw-semibold text-capitalize py-2 px-3">
                        {{ $statusText }}
                    </span>
                </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-muted">Tidak ada data absensi untuk bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
