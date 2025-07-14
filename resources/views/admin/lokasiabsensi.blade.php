@extends('layouts.template')

@section('content')
<div class="app-content-header py-3 mb-4 border-bottom">
    <div class="container-fluid">
        <h3 class="mb-0 fw-bold">Lokasi Absensi</h3>
    </div>
</div>
        <div class="container">


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

            {{-- Form tambah lokasi --}}
            <form action="{{ route('admin.lokasiabsensi') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Toko</label>
                    <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Radius (Meter)</label>
                    <input type="text" name="radius" value="{{ old('radius') }}" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Lokasi</button>
            </form>
        </div>

        <br>

        {{-- Tabel lokasi --}}
        @if($lokasis->count())
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Nama Toko</th>
                    <th scope="col">Latitude</th>
                    <th scope="col">Longitude</th>
                    <th scope="col">Radius</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lokasis as $lokasi)
                <tr>
                    <td>{{ $lokasi->nama_toko }}</td>
                    <td>{{ $lokasi->latitude }}</td>
                    <td>{{ $lokasi->longitude }}</td>
                    <td>{{ $lokasi->radius }}</td>
                    <td>
                        <a href="{{ route('admin.lokasiabsensi.edit', $lokasi->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.lokasiabsensi.destroy', $lokasi->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus lokasi ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-muted">Belum ada data lokasi absensi.</p>
        @endif
    </div>
</div>
@endsection
