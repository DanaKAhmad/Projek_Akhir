<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RekapanController;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $user = Auth::user();

    if (!$user->karyawan) {
        return back()->with('error', 'Data karyawan tidak ditemukan.');
    }

    $karyawan = $user->karyawan;
    $izin = $karyawan->izin()->where('status', 'pending')->latest()->get();
    $statusKaryawan = $karyawan->status;

    // Ambil bulan dari form, default ke bulan ini
    $bulan = $request->input('bulan', now()->format('Y-m'));

    $kalender = RekapanController::getKalenderKaryawan($karyawan->id, $bulan);
    $statistik = RekapanController::getStatistikKaryawan($karyawan->id, $bulan);

    return view('karyawan.index', compact('izin', 'statusKaryawan', 'kalender', 'statistik', 'bulan'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
