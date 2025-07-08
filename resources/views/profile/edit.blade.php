{{--
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
--}}


   @section('scripts')
<script>
    window.onload = function () {
        @if(session('success'))
            Swal.fire('Berhasil', '{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            Swal.fire('Gagal', '{{ session('error') }}', 'error');
        @endif
    };
</script>
@endsection

@extends('layouts.templatekaryawan')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4"><i class="fas fa-user-edit"></i> Edit Profil Karyawan</h3>

    <form action="{{ route('karyawan.profil.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">No Telepon</label>
            <input type="text" name="no_telp" value="{{ old('no_telp', $karyawan->no_telp) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <option value="laki-laki" {{ $karyawan->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="perempuan" {{ $karyawan->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control">{{ old('alamat', $karyawan->alamat) }}</textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('karyawan.profil') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

