<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    //
      public function index(){
       // return view('izin');

   $izin = Izin::all();
   return view('karyawan.izin',compact('izin'));
    }

    public function store(Request $request){
        //  $karyawanId = Auth::id();
        
    $user = Auth::user(); 
    $karyawan = $user->karyawan;

        $request->validate([
            'tanggal_pengajuan' => 'required|date',
            'tanggal_izin' => 'required|date',
            'tanggal_berakhir_izin' => 'required|date',
            'keterangan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

             
        ]);

        $lampiranPath = null;

    if ($request->hasFile('lampiran')) {
        $lampiranPath = $request->file('lampiran')->store('lampiran', 'public');
    }


    
        Izin::create([
            'karyawan_id' => $karyawan->id,
            'name' => $karyawan->name,
            'tanggal_pengajuan' =>$request ->tanggal_pengajuan,
            'tanggal_izin' =>$request ->tanggal_izin,
            'tanggal_berakhir_izin' =>$request ->tanggal_berakhir_izin,
            'keterangan' =>$request ->keterangan,
               'status' => 'pending', //br
               'lampiran' => $lampiranPath,
            
        ]);
        return redirect()->back()->with('success','Data berhasil di simpan.');

    }
}
