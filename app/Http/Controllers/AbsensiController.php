<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Jamkerja;
use App\Models\LokasiAbsensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
         $user = Auth::user();
    $karyawan = $user->karyawan;

    // Jika karyawan belum disetujui atau belum memiliki tanggal_masuk
    if (!$karyawan || !$karyawan->tanggal_masuk || Carbon::parse($karyawan->tanggal_masuk)->gt(Carbon::today())) {
        return redirect()->route('karyawan.profil')->with('error', 'Anda belum mulai bekerja atau belum disetujui oleh admin.');
    }
        $absenMasuk = session('absenMasuk');
        $absenPulang = session('absenPulang');

        $karyawanId = Auth::id();
        $absensi_terakhir = Absensi::where('karyawan_id', $karyawanId)
         ->orderBy('created_at', 'desc')
            ->get();

         $sudahAbsenMasuk = Absensi::where('karyawan_id', $karyawan->id)
        ->whereDate('created_at', Carbon::today())
        ->where('tipe', 'masuk')
        ->exists();

        $sudahAbsenPulang = Absensi::where('karyawan_id', $karyawan->id)
        ->whereDate('created_at', Carbon::today())
        ->where('tipe', 'pulang')
        ->exists();

        return view('karyawan.absensi', compact('absenMasuk', 'absenPulang', 'absensi_terakhir','sudahAbsenMasuk','sudahAbsenPulang'));
    }
    //baru di tmba
    function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {   
        $earthRadius = 6371000; // meter
        $latFrom = deg2rad( $lat1);
        $lonFrom = deg2rad( $lon1);
        $latTo = deg2rad( $lat2);
        $lonTo = deg2rad( $lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
    }

         public function store(Request $request) {
        $request->validate([
        'tipe' => 'required|in:masuk,pulang',        
        'foto' => 'required', // Base64 image      
        'lokasi' => 'required',                    
        'jam' => 'required',  
       // 'status' => 'required',  
    ]);

    $user = Auth::user(); 
    $karyawan = $user->karyawan;

   // $karyawan = Auth::karyawan();
    if (!$karyawan) {
        return redirect()->route('karyawan.absensi')->with('error', 'Karyawan tidak ditemukan.');
    }

    //cek lokasi dari data absen  tmb
    $lokasiToko = LokasiAbsensi::first();
    if (!$lokasiToko){
        return redirect()->route('karyawan.absensi')->with('error','lokasi tokoh belum  di atur oleh admin');
    }

    //bru di tmba
    list($latKaryawan, $lonKaryawan) = explode(',', $request->lokasi);

    // dari db
    $latToko = $lokasiToko->latitude;
    $lonToko = $lokasiToko->longitude;
    $radiusToko = $lokasiToko->radius;

    // Konversi ke float & bulatkan untuk akurasi
    $latKaryawan = round( $latKaryawan, 7);
    $lonKaryawan = round( $lonKaryawan, 7);
    $latToko = round( $latToko, 7);
    $lonToko = round( $lonToko, 7);

    // hitung jarak
    $jarak = $this->hitungJarak($latKaryawan, $lonKaryawan, $latToko, $lonToko);

    // Logging jarak dan koordinat
        Log::info("Lat karyawan: $latKaryawan");
        Log::info("Lon karyawan: $lonKaryawan");
        Log::info("Lat toko: $latToko");
        Log::info("Lon toko: $lonToko");
        Log::info("Jarak dihitung: $jarak, Radius toko: $radiusToko");

    if ($jarak > $radiusToko){
        // return redirect()->route('account.absensi')->with('error', 'Anda berada di luar area absensi (' . round($jarak) . ' meter dari toko).');
         return redirect()->route('karyawan.riwayat')->with('error', 'Anda berada di luar area absensi (' . round($jarak) . ' meter dari kantor).');
    }
 // BARU DI TAMBA
    $tanggalMasuk = Carbon::parse($karyawan->tanggal_masuk);
    $tanggalHariIni = Carbon::today();

// Cek jika karyawan mencoba absen sebelum tanggal mulai kerja
if ($tanggalHariIni->lt($tanggalMasuk)) {
    return redirect()->route('karyawan.riwayat')->with('error', 'Anda belum mulai bekerja. Tanggal mulai kerja Anda adalah ' . $tanggalMasuk->format('Y-m-d'));
}
// Cek apakah hari ini adalah hari libur (Minggu)
if (Carbon::now()->isSunday()) {
    return redirect()->route('karyawan.riwayat')->with('error', 'Hari ini adalah hari libur. Absensi tidak diperbolehkan.');
}

    $jamkerja = Jamkerja::first(); 
    //$jamSekarang = Carbon::now();
    $jamSekarang = Carbon::parse($request->jam); // ← GANTI INI
    $tipe = $request->input('tipe');

    $sudahAbsenMasuk = Absensi::where('karyawan_id', $karyawan->id)
        ->whereDate('created_at', Carbon::today())
        ->where('tipe', 'masuk')
        ->exists();

        if (!$jamkerja) {
    return redirect()->route('karyawan.riwayat')->with('error', 'Jam kerja belum diatur oleh admin.');
    }

    if ($tipe === 'masuk') {
        $batasTerlambat = Carbon::parse($jamkerja->batas_terlambat);

        if ($jamSekarang->gt($batasTerlambat)) {
            return redirect()->route('karyawan.riwayat')
                ->with('error', 'Absen masuk ditolak. Anda terlambat (lebih dari jam ' . $batasTerlambat->format('H:i') . ').');
        }

        if ($sudahAbsenMasuk) {
            return redirect()->route('karyawan.riwayat')
                ->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }
    }

    if ($tipe === 'pulang') {
        if (!$sudahAbsenMasuk) {
            return redirect()->route('karyawan.riwayat')
                ->with('error', 'Tidak bisa absen pulang sebelum absen masuk.');
        }

        $sudahAbsenPulang = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('created_at', Carbon::today())
            ->where('tipe', 'pulang')
            ->exists();

        if ($sudahAbsenPulang) {
            return redirect()->route('karyawan.riwayat')
                ->with('error', 'Anda sudah absen pulang hari ini.');
        }

        $jamPulangMinimal = Carbon::parse($jamkerja->jam_pulang);
        if ($jamSekarang->lt($jamPulangMinimal)) {
            return redirect()->route('karyawan.riwayat')
                ->with('error', 'Belum saatnya absen pulang. Minimal jam ' . $jamPulangMinimal->format('H:i'));
        }
    }
    // Proses penyimpanan foto
    $folderPath = public_path('uploads/foto_absen');
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0775, true);
    }

    // Simpan foto dari base64
    $image_parts = explode("base64,", $request->foto);
    $image_base64 = base64_decode($image_parts[1]); 
    $filename = uniqid() . '.png';
    $filePath = $folderPath . '/' . $filename;
    file_put_contents($filePath, $image_base64);
    $publicPath = 'uploads/foto_absen/' . $filename;

    Absensi::create([
        'karyawan_id' => $karyawan->id,
        'name' => $karyawan->name,
        'tipe' => $request->tipe,
        'foto' => $publicPath,
        'lokasi' => $request->lokasi,
        'jam' => $request->jam,
        //'status' => $request->tipe === 'masuk' ? 'hadir' : null,
       'status' => $request->status ?? 'hadir' //bru tmb
        
    ]);

     $absensData = [
        'name' => $karyawan->name,
        'tipe' => $request->tipe,
        'foto' => asset('uploads/foto_absen/' . $filename),
        'lokasi' => $request->lokasi,
        'jam' => $request->jam,
        //'status' => $request->tipe === 'masuk' ? 'hadir' : null,
       'status' => $request->status ?? 'hadir' //tmb
    ];

    //session(['absen' => $absensData]);
    session(['absen' . ucfirst($request->tipe) => $absensData]);
    return redirect()->route('karyawan.riwayat')->with('success', 'Absensi berhasil disimpan!');
    }
    public function riwayat()
    {
        $loggedInId = Auth::id();
    //dd('Auth::id() = '.$loggedInId);

    //$karyawanId = Auth::id(); // bnr
    $karyawanId = Auth::user()->karyawan->id; //baru ditambahkan

   $absensi_terakhir = Absensi::where('karyawan_id', $karyawanId) 
              ->orderBy('created_at', 'desc')
          // ->paginate(10);
            ->get();

           // dd($absensi_terakhir);
        return view ('karyawan.riwayat', compact('absensi_terakhir'));

}

}