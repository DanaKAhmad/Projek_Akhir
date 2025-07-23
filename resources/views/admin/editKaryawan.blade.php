@extends('layouts.template')

@section('content')


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


    


<div class="card">
    <div class="card-header">Edit Data Karyawan</div>

    <div class="card-body">
        
        {{-- Form submit ke route update --}}
        <form action="{{ route('admin.dataKaryawan.update', $karyawan->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Laravel mengubah method POST menjadi PUT --}}

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama:</label>
                    <input type="text" name="name" value="{{ $karyawan->name }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" value="{{ $karyawan->user->email }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No Telp:</label>
                    <input type="text" name="no_telp" value="{{ $karyawan->user->no_telp }}" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin:</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="Laki-laki" {{ $karyawan->user->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $karyawan->user->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                @php
                    use Carbon\Carbon;
                    $today = Carbon::now();
                    $minTanggalLahir = $today->copy()->subYears(30)->format('Y-m-d'); // Maksimal 30 tahun
                    $maxTanggalLahir = $today->copy()->subYears(17)->format('Y-m-d'); // Minimal 17 tahun
                @endphp

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir:</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                        min="{{ $minTanggalLahir }}" max="{{ $maxTanggalLahir }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Masuk:</label>
                    <input type="date" name="tanggal_masuk" value="{{ $karyawan->tanggal_masuk }}" class="form-control" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat:</label>
                    <textarea name="alamat" class="form-control" rows="2" required>{{ $karyawan->user->alamat }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.dataKaryawan') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
