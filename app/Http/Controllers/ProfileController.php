<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import class, request, dan facades yang dibutuhkan
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman form edit profil pengguna.
     */
    public function edit(Request $request): View
    {
        // Tampilkan view edit profil dengan mengirimkan data user yang sedang login
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Mengisi model user dengan data yang telah lolos validasi
        $request->user()->fill($request->validated());

        // Jika email diubah, atur ulang verifikasi email menjadi null
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Simpan perubahan ke database
        $request->user()->save();

        // Redirect kembali ke form profil dengan status sukses
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi kata sandi saat ini sebelum menghapus akun
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Ambil instance user yang sedang login
        $user = $request->user();

        // Logout pengguna dari sistem
        Auth::logout();

        // Hapus data pengguna dari database
        $user->delete();

        // Invalidasi sesi yang berjalan
        $request->session()->invalidate();
        // Regenerasi token CSRF untuk keamanan
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return Redirect::to('/');
    }
}