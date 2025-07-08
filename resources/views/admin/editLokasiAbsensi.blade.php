@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Lokasi Absensi</h3>
    </div>
    <div class="card-body">

       @if(session('success'))
    <script>
        Swal.fire('Berhasil','{{ session('success') }}','success');
    </script>
    @endif

        <form action="{{ route('admin.lokasiabsensi.update', $lokasi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label>Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko', $lokasi->nama_toko) }}" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Latitude</label>
                <input type="text" name="latitude" value="{{ old('latitude', $lokasi->latitude) }}" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Longitude</label>
                <input type="text" name="longitude" value="{{ old('longitude', $lokasi->longitude) }}" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <label>Radius (Meter)</label>
                <input type="text" name="radius" value="{{ old('radius', $lokasi->radius) }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

            <a href="{{ route('admin.lokasiabsensi') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection