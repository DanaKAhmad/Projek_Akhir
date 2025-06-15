@extends('layouts.templatekaryawan')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Form Pengajuan Izin</h5>
        </div>
        <div class="card-body">
            {{--}}
            {{-- Notifikasi
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}

            <script>
            window.onload = function () {
                    @if(session('success'))
                        Swal.fire('Berhasil', '{{ session('success') }}', 'success');
                    @endif
                    @if(session('error'))
                        Swal.fire('Gagal', '{{ session('error') }}', 'error');
                    @endif
                }
            </script>

            {{-- Tombol toggle form --}}
            <button class="btn btn-sm btn-primary mb-3" onclick="formDataIzin()">
                <i class="fas fa-plus-circle me-1"></i> Tambah Data
            </button>

            {{-- Form Izin --}}
            <div id="formIzin" style="display: none;">
                <form method="post" action="{{ route('karyawan.izin') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_pengajuan">Tanggal Pengajuan</label>
                            <input type="date" class="form-control" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_izin">Tanggal Izin</label>
                            <input type="date" class="form-control" name="tanggal_izin" value="{{ old('tanggal_izin') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_berakhir_izin">Tanggal Berakhir Izin</label>
                            <input type="date" class="form-control" name="tanggal_berakhir_izin" value="{{ old('tanggal_berakhir_izin') }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success" type="submit"><i class="fas fa-paper-plane me-1"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Izin --}}
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Pengajuan Izin</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Tanggal Izin</th>
                            <th>Tanggal Berakhir</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($izin as $key => $data)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_izin)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_berakhir_izin)->format('d-m-Y') }}</td>
                                <td>{{ $data->keterangan }}</td>
                                <td>
                                    @if($data->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($data->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($izin->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data pengajuan izin.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Toggle Form --}}
<script>
    function formDataIzin() {
        const form = document.getElementById('formIzin');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
