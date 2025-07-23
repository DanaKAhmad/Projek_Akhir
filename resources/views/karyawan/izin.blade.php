@extends('layouts.templatekaryawan')

@section('content')
<div class="container-fluid">
    {{-- Card Form Izin --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Form Pengajuan Izin</h5>
        </div>
        <div class="card-body">

            {{-- Tombol toggle form --}}
            <button class="btn btn-sm btn-primary mb-3" onclick="formDataIzin()">
                <i class="fas fa-plus-circle me-1"></i> Tambah Data
            </button>

            {{-- Form Izin --}}
            <div id="formIzin" style="display: none;">
                <form method="post" action="{{ route('karyawan.izin') }}" enctype="multipart/form-data">
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
                            <input type="date" class="form-control" name="tanggal_berakhir_izin" id="tanggal_berakhir_izin" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="lampiran">Lampiran (Surat Keterangan Dokter)</label>
                            <input type="file" class="form-control" name="lampiran" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success" type="submit"><i class="fas fa-paper-plane me-1"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Card Tabel Riwayat --}}
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
                            <th>Lampiran</th>
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
                                    @if ($data->lampiran)
                                        <a href="{{ asset('storage/' . $data->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
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
                                <td colspan="7" class="text-center">Belum ada data pengajuan izin.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script Toggle Form dan Sinkronisasi Tanggal --}}
@push('scripts')
<script>
    function formDataIzin() {
        const form = document.getElementById('formIzin');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const inputPengajuan = document.querySelector('input[name="tanggal_pengajuan"]');
        const inputIzin = document.querySelector('input[name="tanggal_izin"]');
        const inputBerakhir = document.querySelector('input[name="tanggal_berakhir_izin"]');
        const inputLampiran = document.querySelector('input[name="lampiran"]');
        const btnSubmit = document.querySelector('#formIzin button[type="submit"]');

        // Otomatis tanggal izin dan berakhir izin
        inputPengajuan.addEventListener('change', function () {
            inputIzin.value = inputPengajuan.value;

            const tgl = new Date(inputPengajuan.value);
            tgl.setDate(tgl.getDate() + 2);
            inputBerakhir.value = tgl.toISOString().split('T')[0];
        });

        // Validasi lampiran wajib sebelum submit
        btnSubmit.addEventListener('click', function (e) {
            if (!inputLampiran.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Lampiran Wajib',
                    text: 'Silakan unggah file lampiran terlebih dahulu.'
                });
            }
        });
    });
</script>
@push('scripts')
    {{-- SweetAlert Notifikasi --}}
    @if(session('success'))
    <script>
        Swal.fire('Berhasil', '{{ session('success') }}', 'success');
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire('Gagal', '{{ session('error') }}', 'error');
    </script>
    @endif

    {{-- Script Toggle & Validasi --}}
    <script>
        function formDataIzin() {
            const form = document.getElementById('formIzin');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const inputPengajuan = document.querySelector('input[name="tanggal_pengajuan"]');
            const inputIzin = document.querySelector('input[name="tanggal_izin"]');
            const inputBerakhir = document.querySelector('input[name="tanggal_berakhir_izin"]');
            const inputLampiran = document.querySelector('input[name="lampiran"]');
            const btnSubmit = document.querySelector('#formIzin button[type="submit"]');

            // Otomatis tanggal izin & tanggal berakhir = 3 hari total
            inputPengajuan.addEventListener('change', function () {
                inputIzin.value = inputPengajuan.value;

                const tgl = new Date(inputPengajuan.value);
                tgl.setDate(tgl.getDate() + 2); // 3 hari total: tgl_pengajuan, +1, +2
                inputBerakhir.value = tgl.toISOString().split('T')[0];
            });

            // Validasi lampiran wajib
            btnSubmit.addEventListener('click', function (e) {
                if (!inputLampiran.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lampiran Wajib',
                        text: 'Silakan unggah file lampiran terlebih dahulu.'
                    });
                }
            });
        });
    </script>
@endpush


@endpush



@endsection
