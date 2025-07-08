@extends('layouts.template')

@section('content')
<div class="app-content-header py-3 mb-4 border-bottom">
    <div class="container-fluid">
        <h3 class="mb-0 fw-bold">Rekap Pengajuan Izin</h3>
    </div>
</div>
    <div class="card-body">

        {{-- SweetAlert Notification --}}
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

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Izin</th>
                        <th>Tanggal Berakhir</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($izin as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->tanggal_pengajuan }}</td>
                            <td>{{ $data->tanggal_izin }}</td>
                            <td>{{ $data->tanggal_berakhir_izin }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>
                                @if ($data->lampiran)
                                    <a href="{{ asset('storage/' . $data->lampiran) }}" target="_blank" class="btn btn-sm btn-primary">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($data->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($data->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif ($data->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($data->status == 'pending')
                                    <form action="{{ route('admin.aprovalizin.update', ['id' => $data->id, 'status' => 'disetujui']) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-success btn-sm mb-1" onclick="return confirm('Setujui izin ini?')">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.aprovalizin.update', ['id' => $data->id, 'status' => 'ditolak']) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak izin ini?')">Tolak</button>
                                    </form>
                                @else
                                    <em class="text-muted">Tindakan selesai</em>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">Belum ada pengajuan izin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
