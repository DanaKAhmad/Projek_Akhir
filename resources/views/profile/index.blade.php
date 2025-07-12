@extends('layouts.templatekaryawan')
@section('content')

            

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-bottom">
        <h3 class="mb-0 fw-bold">Profil Karyawan</h3>
    </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-id-badge"></i> Informasi Karyawan</h5>
                    
            </div><div class="mt-4 text-end">
            <a href="{{ route('karyawan.profil.edit') }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Profil
            </a>
        </div>




                <div class="card-body bg-light">

                    <div class="mb-3 row">
                        <label class="col-sm-4 fw-bold">Nama</label>
                        <div class="col-sm-8">{{ $user->name }}</div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 fw-bold">Email</label>
                        <div class="col-sm-8">{{ $user->email }}</div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 fw-bold">No Telepon</label>
                        <div class="col-sm-8">{{ $karyawan->no_telp }}</div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 fw-bold">Jenis Kelamin</label>
                        <div class="col-sm-8">{{ $karyawan->jenis_kelamin }}</div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 fw-bold">Tanggal Lahir</label>
                        <div class="col-sm-8">
                            {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 fw-bold">Alamat</label>
                        <div class="col-sm-8">{{ $karyawan->alamat }}</div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 fw-bold">Tanggal Masuk</label>
                        <div class="col-sm-8">
                            {{ \Carbon\Carbon::parse($karyawan->tanggal_masuk)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                   <div class="mb-3 row">
    <label class="col-sm-4 fw-bold">Status</label>
    <div class="col-sm-8">
        <span class="badge 
            {{ 
                $karyawan->status === 'aktif' ? 'bg-success' : 
                ($karyawan->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') 
            }}">
            {{ ucfirst($karyawan->status) }}
        </span>
    </div>
</div>


                </div>
            </div>

        </div>
    </div>
</div>



@endsection