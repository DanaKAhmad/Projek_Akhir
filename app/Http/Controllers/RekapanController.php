<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Carbon\Carbon;

class RekapanController extends Controller
{
   public function index(Request $request)
{
    $bulan = $request->input('bulan', now()->format('Y-m'));
    $search = $request->input('search');

    $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
    $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();
    $today = Carbon::today();

    // Kalau bulan saat ini, tanggal akhir dibatasi sampai hari ini
    if ($tanggalAwal->isSameMonth($today)) {
        $tanggalAkhir = $today;
    }

    $tanggalList = collect();
    for ($tgl = $tanggalAwal; $tgl->lte($tanggalAkhir); $tgl->addDay()) {
        if (!$tgl->isSunday()) {
            $tanggalList->push($tgl->copy());
        }
    }

    $karyawans = Karyawan::query()
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->get();

    $absen = [];

    foreach ($tanggalList as $tgl) {
        foreach ($karyawans as $karyawan) {
            if (!$karyawan->tanggal_masuk || Carbon::parse($karyawan->tanggal_masuk)->gt($tgl)) {
                continue;
            }

            $absensis = $karyawan->absensi()->whereDate('created_at', $tgl->toDateString())->get();
            $masuk = $absensis->firstWhere('tipe', 'masuk');
            $pulang = $absensis->firstWhere('tipe', 'pulang');

            $izin = $karyawan->izin()
                ->where('status', 'disetujui')
                ->whereDate('tanggal_izin', '<=', $tgl->toDateString())
                ->whereDate('tanggal_berakhir_izin', '>=', $tgl->toDateString())
                ->first();

            if ($masuk && $pulang) {
                $status = 'Hadir';
            } elseif ($masuk && !$pulang) {
                $status = 'Belum Absen Pulang';
            } elseif ($izin) {
                $status = 'Hadir (Izin Disetujui)';
            } else {
                $status = 'Alpha';
            }

            $absen[] = [
                'tanggal'      => $tgl->toDateString(),
                'nama'         => $karyawan->name,
                'jam_masuk'    => $masuk->jam ?? '-',
                'foto_masuk'   => $masuk->foto ?? null,
                'jam_pulang'   => $pulang->jam ?? '-',
                'foto_pulang'  => $pulang->foto ?? null,
                'keterangan'   => $izin?->keterangan ?? '-',
                'lampiran'     => $izin?->lampiran ?? null,
                'status'       => $status,
            ];  
        }
    }

    return view('admin.rekapan', compact('absen', 'bulan', 'search'));
}
public function getStatistikBulanIni()
{
    $bulanIni = Carbon::now()->format('Y-m');
    $request = new Request(['bulan' => $bulanIni]);
    $this->index($request); // Gunakan fungsi index untuk proses logika

    // Hitung total Hadir, Izin, Alpha
    $absen = $this->absen ?? [];
    $stat = ['Hadir' => 0, 'Izin' => 0, 'Alpha' => 0];

    foreach ($absen as $item) {
        if (str_contains($item['status'], 'Hadir')) {
            $stat['Hadir']++;
        } elseif ($item['status'] === 'Alpha') {
            $stat['Alpha']++;
        } elseif (str_contains($item['status'], 'Izin')) {
            $stat['Izin']++;
        }
    }

    return $stat;
}

    }