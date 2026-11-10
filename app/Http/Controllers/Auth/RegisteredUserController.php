<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input Form (Termasuk Data Pasien)
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            'nik'           => ['nullable', 'numeric', 'digits:16', 'unique:pasiens,nik'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date'],
            'no_telepon'    => ['nullable', 'string', 'max:15'],
            'alamat'        => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // 2. Buat User Baru dengan Role Pasien
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'pasien', // default role
            ]);

            // 3. Generate No RM Otomatis
            $latest = Pasien::orderBy('id', 'desc')->first();
            $number = $latest ? ((int) preg_replace('/[^0-9]/', '', $latest->no_rm)) + 1 : 1;
            $noRm   = 'RM' . str_pad($number, 5, '0', STR_PAD_LEFT);

            // 4. Buat Profile Pasien dari Input Form (atau gunakan fallback jika kosong)
            Pasien::create([
                'user_id'       => $user->id,
                'no_rm'         => $noRm,
                'nik'           => $request->nik ?? ('35' . date('Ymd') . sprintf('%04d', $user->id)),
                'nama'          => $user->name,
                'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
                'tanggal_lahir' => $request->tanggal_lahir ?? now()->format('Y-m-d'),
                'alamat'        => $request->alamat ?? '-',
                'no_telepon'    => $request->no_telepon ?? '08' . rand(100000000, 999999999),
            ]);

            DB::commit();

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mendaftar: ' . $e->getMessage());
        }
    }
}