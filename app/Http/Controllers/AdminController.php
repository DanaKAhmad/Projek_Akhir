<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Absensi;
use Carbon\Carbon;

class AdminController extends Controller
{
public function index()
{
    $jumlahIzinPending = Izin::where('status', 'pending')->count();
    $jumlahKaryawanBaru = Karyawan::where('status', 'pending')->count();
    $jumlahKaryawan = Karyawan::where('status', 'aktif')->count();

    $today = Carbon::now()->toDateString();
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();

    // Ambil semua karyawan aktif
    $karyawanAktif = Karyawan::where('status', 'aktif')->get();

    $jumlahHadirHariIni = 0;
    $jumlahAlphaHariIni = 0;

    foreach ($karyawanAktif as $karyawan) {
        // Ambil absensi masuk dan pulang hari ini
        $masuk = $karyawan->absensi()
            ->where('tipe', 'masuk')
            ->whereDate('created_at', $today)
            ->first();

        $pulang = $karyawan->absensi()
            ->where('tipe', 'pulang')
            ->whereDate('created_at', $today)
            ->first();

        if ($masuk && $pulang) {
            $jumlahHadirHariIni++;
        } else {
            $jumlahAlphaHariIni++;
        }
    }

    // Data grafik bulan ini
    $labels = [];
    $dataHadir = [];
    $dataAlpha = [];
    $dataIzin = [];

    

    for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
        $tanggal = $date->format('Y-m-d');
        $labels[] = $date->format('d');

        $hadirHarian = 0;
        $izinHarian = 0;

        foreach ($karyawanAktif as $karyawan) {
            $masuk = $karyawan->absensi()->where('tipe', 'masuk')->whereDate('created_at', $tanggal)->first();
            $pulang = $karyawan->absensi()->where('tipe', 'pulang')->whereDate('created_at', $tanggal)->first();

            $izin = $karyawan->izin()
                ->where('status', 'disetujui')
                ->whereDate('tanggal_izin', '<=', $tanggal)
                ->whereDate('tanggal_berakhir_izin', '>=', $tanggal)
                ->first();

            if ($masuk && $pulang) {
                $hadirHarian++;
            } elseif ($izin) {
                $izinHarian++;
            }
        }

        $alphaHarian = $jumlahKaryawan - ($hadirHarian + $izinHarian);
        $dataHadir[] = $hadirHarian;
        $dataIzin[] = $izinHarian;
        $dataAlpha[] = $alphaHarian;
    }

    // Tambahkan ini:
$statistik = [
    'hadir' => array_sum($dataHadir),
    'izin' => array_sum($dataIzin),
    'alpha' => array_sum($dataAlpha),
];

    return view('admin.index', compact(
        'jumlahIzinPending',
        'jumlahKaryawanBaru',
        'jumlahKaryawan',
        'jumlahHadirHariIni',
        'jumlahAlphaHariIni',
        'labels',
        'dataHadir',
        'dataIzin',
        'dataAlpha',
        'statistik'
    ));
}

    public function create() { /* ... */ }
    public function store(Request $request) { /* ... */ }
    public function show(string $id) { /* ... */ }
    public function edit(string $id) { /* ... */ }
    public function update(Request $request, string $id) { /* ... */ }
    public function destroy(string $id) { /* ... */ }
}
