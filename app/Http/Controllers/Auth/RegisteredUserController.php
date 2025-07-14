<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'no_telp' => ['required', 'string'],
        'jenis_kelamin' => ['required'],
        'tanggal_lahir' => ['required', 'date'],
        'alamat' => ['required', 'string'],
        // 'tanggal_masuk' tidak diperlukan karena admin yang input nanti
    ]);

    // Blokir jika pakai email admin
    if ($request->email === 'tokoh@gmail.com') {
        return back()->withErrors(['email' => 'Email ini tidak bisa digunakan untuk registrasi.']);
    }

    // Simpan ke tabel users
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'no_telp' => $request->no_telp,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tanggal_lahir' => $request->tanggal_lahir,
        'alamat' => $request->alamat,
        'tanggal_masuk' => null, // karena admin yang isi nanti
        'role' => 'karyawan'
    ]);

    // Simpan ke tabel karyawans dan hubungkan dengan user_id
    Karyawan::create([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'no_telp' => $user->no_telp,
        'jenis_kelamin' => $user->jenis_kelamin,
        'tanggal_lahir' => $user->tanggal_lahir,
        'alamat' => $user->alamat,
        'tanggal_masuk' => null // nanti diisi oleh admin
    ]);

    event(new Registered($user));
    //Auth::login($user);

    return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
}

}

