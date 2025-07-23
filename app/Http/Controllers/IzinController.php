<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    //
public function index()
{
    $user = Auth::user();

    if (!$user->karyawan) {
        return redirect()->back()->with('error', 'Data karyawan tidak ditemukan untuk user ini.');
    }

    $karyawan = $user->karyawan;

    // Cegah akses jika belum punya tanggal_masuk atau belum aktif
    if (!$karyawan->tanggal_masuk || \Carbon\Carbon::parse($karyawan->tanggal_masuk)->gt(\Carbon\Carbon::today())) {
        return redirect()->route('karyawan.profil')->with('error', 'Akun Anda belum diaktifkan atau belum mulai bekerja.');
    }

    $izin = Izin::where('karyawan_id', $karyawan->id)->get();

    return view('karyawan.izin', compact('izin'));
}


    public function store(Request $request){
        //  $karyawanId = Auth::id();
        
    $user = Auth::user(); 
    $karyawan = $user->karyawan;

        $request->validate([
            'tanggal_pengajuan' => 'required|date',
            'tanggal_izin' => 'required|date',
            //'tanggal_berakhir_izin' => 'required|date',
            'keterangan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

        $lampiranPath = null;

    if ($request->hasFile('lampiran')) {
        $lampiranPath = $request->file('lampiran')->store('lampiran', 'public');
    }
        $tanggal_izin = Carbon::parse($request->tanggal_izin);
        $tanggal_berakhir_izin = $tanggal_izin->copy()->addDays(2); // 3 hari total: tgl_izin, +1, +2

        Izin::create([
        'karyawan_id' => $karyawan->id,
        'name' => $karyawan->name,
        'tanggal_pengajuan' => $request->tanggal_pengajuan,
        'tanggal_izin' => $request->tanggal_izin,
        'tanggal_berakhir_izin' => $tanggal_berakhir_izin->format('Y-m-d'), // otomatis
        'keterangan' => $request->keterangan,
        'status' => 'pending',
        'lampiran' => $lampiranPath,
    ]);

        return redirect()->back()->with('success','Data berhasil di simpan.');

    }
}
