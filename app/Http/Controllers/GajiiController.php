<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class GajiiController extends Controller
{
    public function gaji(Request $request)
{
    $bulan = $request->input('bulan') ?? Carbon::now()->format('Y-m'); // format: '2025-07'

    $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
    $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

    $karyawans = Karyawan::all();
    $gajiData = [];

    foreach ($karyawans as $karyawan) {
        $gaji = $this->hitungGaji($karyawan, $tanggalAwal, $tanggalAkhir);
        if (!empty($gaji)) {
            $gaji['id'] = $karyawan->id;
            $gajiData[] = $gaji;
        }
    }

    return view('admin.gaji', compact('gajiData', 'karyawans', 'bulan'));
}

   public function cetakPDF()
{
    $karyawans = Karyawan::all();
    $tanggalAwal = Carbon::now()->startOfMonth();
    $tanggalAkhir = Carbon::today();

    $gajiData = [];
    foreach ($karyawans as $karyawan) {
        $gajiData[] = $this->hitungGaji($karyawan, $tanggalAwal, $tanggalAkhir);
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.gaji_pdf', compact('gajiData'))
              ->setPaper('A4', 'landscape');

    return $pdf->download('laporan_gaji_karyawan.pdf');
}


    public function cetakPerKaryawan($id)
{
    $karyawan = Karyawan::findOrFail($id);
    $tanggalAwal = Carbon::now()->startOfMonth();
    $tanggalAkhir = Carbon::today();

    $gajiData = [$this->hitungGaji($karyawan, $tanggalAwal, $tanggalAkhir)];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.gaji_pdf', compact('gajiData'))
              ->setPaper('A4', 'portrait');

    $namaFile = 'slip_gaji_' . strtolower(str_replace(' ', '_', $karyawan->name)) . '.pdf';
    return $pdf->download($namaFile);
}


    private function hitungGaji($karyawan, $tanggalAwal, $tanggalAkhir)
    {
        if (!$karyawan->tanggal_masuk) return [];

        $hadir = 0;
        $alpha = 0;

        try {
            $tanggalMulaiKerja = Carbon::parse($karyawan->tanggal_masuk);
        } catch (\Exception $e) {
            return [];
        }

        $tanggalMulaiKerja = Carbon::parse($karyawan->tanggal_masuk);

        // Ambil awal & akhir bulan yang dipilih
        $awalBulanIni = $tanggalAwal->copy()->startOfMonth();
        $akhirBulanIni = $tanggalAwal->copy()->endOfMonth();

        // Logika awal hitung
        if ($tanggalMulaiKerja->between($awalBulanIni, $akhirBulanIni)) {
        // Masuk bulan ini → hitung dari tanggal masuk
        $awalHitung = $tanggalMulaiKerja;
        } elseif ($tanggalMulaiKerja->lessThan($awalBulanIni)) {
        // Masuk sebelum bulan ini → hitung dari tanggal 1
        $awalHitung = $awalBulanIni;
        } else {
        // Masuk bulan depan → tidak dihitung
        return [];
        }

        $tanggalList = collect();
        $today = Carbon::today();

        // Jika bulan yang dipilih adalah bulan sekarang, batasi hanya sampai hari ini
        if ($tanggalAkhir->isCurrentMonth()) {
        $tanggalAkhir = $today;
        }

        for ($tgl = $awalHitung->copy(); $tgl->lte($tanggalAkhir); $tgl->addDay()) {
            if (!$tgl->isSunday()) {
                $tanggalList->push($tgl->copy());
            }
        }

        $hariKerja = $tanggalList->count();

        foreach ($tanggalList as $tgl) {
            $absensis = $karyawan->absensi()
                ->whereDate('created_at', $tgl->toDateString())
                ->get();

            $absenMasuk = $absensis->firstWhere('tipe', 'masuk');
            $absenPulang = $absensis->firstWhere('tipe', 'pulang');

            $izinDisetujui = $karyawan->izin()
                ->where('status', 'disetujui')
                ->whereDate('tanggal_izin', '<=', $tgl->toDateString())
                ->whereDate('tanggal_berakhir_izin', '>=', $tgl->toDateString())
                ->exists();

            if (($absenMasuk && $absenPulang) || $izinDisetujui) {
                $hadir++;
            } elseif ($absenMasuk && !$absenPulang) {
                $alpha++;
            } else {
                $alpha++;
            }
        }

        $gajiPokok = 2000000;
        $gajiHarian = 80000;
        $gajiKotor = $hadir * $gajiHarian;
        $potongan = $alpha * 80000;
        $gajiBersih = $gajiKotor - $potongan;

        return [
            'nama' => $karyawan->name,
            'tanggal_masuk' => $karyawan->tanggal_masuk,
            'hadir' => $hadir,
            'alpha' => $alpha,
            'hari_kerja' => $hariKerja,
            'gaji_pokok' => $gajiPokok,
            'gaji_harian' => $gajiHarian,
            'potongan' => $potongan,
            'gaji_bersih' => $gajiBersih,
        ];
    }
}
