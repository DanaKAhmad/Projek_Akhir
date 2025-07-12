@extends('layouts.templatekaryawan')

@section('content')
<div class="card">
    <div class="todaypresences">
        <div class="row">
            <!-- Absen Masuk -->
            <div class="col-md-6 mb-3">
                <div class="card shadow border-0 bg-success text-white">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-sign-in-alt fa-3x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4>Masuk</h4>
                            <p class="mb-1"><strong id="jamMasuk">--:--</strong></p>
                            <button class="btn btn-light btn-sm mt-2" id="btnAbsenMasuk" onclick="capturePhoto('masuk')">
                                <i class="fas fa-camera"></i> Absen Masuk
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absen Pulang -->
            <div class="col-md-6 mb-3">
                <div class="card shadow border-0 bg-danger text-white">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-sign-out-alt fa-3x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4>Pulang</h4>
                            <p class="mb-1"><strong id="jamPulang">--:--</strong></p>
                            <button class="btn btn-light btn-sm mt-2" id="btnAbsenPulang" onclick="capturePhoto('pulang')">
                                <i class="fas fa-camera"></i> Absen Pulang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Kamera -->
        <div class="text-center my-4">
            <video id="video" width="320" height="240" autoplay class="rounded shadow"></video>
            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
            <img id="previewFoto" src="" width="320" height="240" style="display: none; margin-top: 10px;" class="rounded shadow border border-light" />
            <br>
            <button type="button" id="btnAmbilFoto" onclick="ambilFoto()" style="display: none;" class="btn btn-primary mt-3">
                <i class="fas fa-camera-retro"></i> Ambil Foto
            </button>
        </div>

        <!-- Form Kirim Absen -->
        <form action="{{ route('karyawan.absensi.store') }}" method="POST" class="text-center">
            @csrf
            <input type="hidden" name="name" value="{{ Auth::user()->karyawan->name ?? Auth::user()->name }}">
            <input type="hidden" name="tipe" id="tipe">
            <input type="hidden" name="foto" id="foto">
            <input type="hidden" name="lokasi" id="lokasi">
            <input type="hidden" name="jam" id="jam">
            <button type="submit" id="btnSubmit" style="display: none;" class="btn btn-success mt-3">
                <i class="fas fa-paper-plane"></i> Kirim Absen
            </button>
        </form>

        <!-- SweetAlert Notifikasi -->
        @if(session('success'))
            <script>
                Swal.fire('Berhasil','{{ session('success') }}','success');
            </script>
        @endif

        @if(session('error'))
            <script>
                Swal.fire('Gagal','{{ session('error') }}','error');
            </script>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    let stream = null;
    let currentTipe = '';

    async function startCamera() {
        if (!stream) {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                document.getElementById('video').srcObject = stream;
            } catch (err) {
                Swal.fire('Gagal Akses Kamera', err.message, 'error');
            }
        }
    }

    function capturePhoto(tipe) {
        const hariIni = new Date().getDay(); // 1 = Senin
        if (hariIni === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Hari Libur',
                text: 'Hari ini adalah hari libur. Anda tidak bisa melakukan absensi.'
            });
            return;
        }

        currentTipe = tipe;
        startCamera();
        document.getElementById('btnAmbilFoto').style.display = 'inline-block';
        document.getElementById('btnSubmit').style.display = 'inline-block';
    }

    function ambilFoto() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/png');

        document.getElementById('foto').value = dataURL;
        document.getElementById('previewFoto').src = dataURL;
        document.getElementById('previewFoto').style.display = 'block';
        video.style.display = 'none';

        document.getElementById('tipe').value = currentTipe;

        navigator.geolocation.getCurrentPosition(function (position) {
            const lokasi = position.coords.latitude + ',' + position.coords.longitude;
            const jam = new Date().toLocaleTimeString('id-ID', { hour12: false });

            document.getElementById('lokasi').value = lokasi;
            document.getElementById('jam').value = jam;

            if (currentTipe === 'masuk') {
                document.getElementById('jamMasuk').innerText = jam;
            } else {
                document.getElementById('jamPulang').innerText = jam;
            }

        }, function (error) {
            Swal.fire('Gagal Lokasi', error.message, 'error');
        });
    }
</script>
    @endpush
