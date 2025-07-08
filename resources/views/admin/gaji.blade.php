@extends('layouts.template')

@section('content')
<div class="app-content-header py-3 mb-4 border-bottom">
    <div class="container-fluid">
        <div class="row align-items-end">
            <div class="col-md-6">
                <h3 class="mb-0 fw-bold">Gaji Karyawan</h3>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('admin.gaji') }}">
                    <div class="row align-items-end justify-content-start ps-md-3"> <!-- Tambah padding kiri -->
                        <div class="col-auto">
                            <label for="bulan" class="form-label fw-semibold">Pilih Bulan:</label>
                        </div>
                        <div class="col-auto">
                            <input type="month" name="bulan" id="bulan" class="form-control"
                                value="{{ $bulan ?? now()->format('Y-m') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Tanggal Masuk</th>
                        <th>Hadir</th>
                        <th>Alpha</th>
                        <th>Hari Kerja</th>
                        <th>Gaji Harian</th>
                        <th>Gaji Pokok</th>
                        <th>Potongan</th>
                        <th>Gaji Bersih</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gajiData as $data)
                        <tr>
                            <td>{{ $data['nama'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $data['hadir'] }}</td>
                            <td class="text-center">{{ $data['alpha'] }}</td>
                            <td class="text-center">{{ $data['hari_kerja'] }}</td>
                            <td class="text-end">Rp {{ number_format($data['gaji_harian']) }}</td>
                            <td class="text-end">Rp {{ number_format($data['gaji_pokok']) }}</td>
                            <td class="text-end">Rp {{ number_format($data['potongan']) }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($data['gaji_bersih']) }}</td>
                            <td class="text-center">
                                @php $karyawanId = $data['id'] ?? null; @endphp
                                @if($karyawanId)
                                    <a href="{{ route('admin.gaji.cetak.karyawan', $karyawanId) }}" class="btn btn-sm btn-primary" target="_blank">
                                        Cetak
                                    </a>
                                @else
                                    <span class="text-danger">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Data gaji belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
