<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
     public function index (): View
    {
        $user = Auth::user(); // ambil user yang sedang login
        $karyawan = $user->karyawan; // ambil dari relasi

        return view('profile.index', compact('user', 'karyawan'));    
    }

    public function edit(Request $request): View
    {
        /*
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
        */

        $user = Auth::user();
        $karyawan = $user->karyawan;

        return view('profile.edit', compact('user', 'karyawan'));
    
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        /*
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');

        */



         $user = Auth::user();
    $karyawan = $user->karyawan;

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'no_telp' => 'nullable|string|max:20',
        'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
        'tanggal_lahir' => 'nullable|date',
        'alamat' => 'nullable|string',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    $karyawan->update([
        'no_telp' => $request->no_telp,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tanggal_lahir' => $request->tanggal_lahir,
        'alamat' => $request->alamat,
    ]);

    return redirect()->route('karyawan.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
