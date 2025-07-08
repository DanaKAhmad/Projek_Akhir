@extends('layouts.template')

@section('content')
<div class="app-content-header py-3 mb-4 border-bottom">
    <div class="container-fluid">
        <h3 class="mb-0 fw-bold">Data Karyawan</h3>
    </div>
</div>

@push('scripts')
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
@endpush

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
       
        <button class="btn btn-success" onclick="toggleForm()">Tambah Karyawan</button>
    </div>

    <div class="card-body" id="form-karyawan" style="display: none;">
        <form action="{{ route('admin.dataKaryawan') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password:</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor Telepon:</label>
                    <input type="text" name="no_telp" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir:</label>
                    <input type="date" name="tanggal_lahir" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Masuk Kerja:</label>
                    <input type="date" name="tanggal_masuk" class="form-control" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat:</label>
                    <textarea name="alamat" class="form-control" rows="2" required></textarea>
                </div>
               
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

{{-- Tabel Daftar Karyawan --}}
<div class="card">
    <div class="card-header">Daftar Karyawan</div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Telp</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawans as $karyawan)
                    <tr>
                        <td>{{ $karyawan->name }}</td>
                        <td>{{ $karyawan->user->email ?? '-' }}</td>
                        <td>{{ $karyawan->user->no_telp ?? '-' }}</td>
                        <td>{{ $karyawan->user->jenis_kelamin ?? '-' }}</td>
                        <td>{{ $karyawan->user->tanggal_lahir ?? '-' }}</td>
                        <td>{{ $karyawan->user->alamat ?? '-' }}</td>
                        <td>{{ $karyawan->tanggal_masuk }}</td>
                        <td>{{ $karyawan->status }}</td>

                        <td>
                            <a href="{{ route('admin.dataKaryawan.edit', $karyawan->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('admin.dataKaryawan.destroy', $karyawan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Script untuk toggle form --}}
<script>
    function toggleForm() {
        const form = document.getElementById('form-karyawan');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
