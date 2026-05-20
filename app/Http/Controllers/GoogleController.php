<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mitra; // Tambahkan Model Mitra
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth, Log, DB}; // Tambahkan DB untuk transaksi
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

  public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();
        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            Auth::login($existingUser);
            $role = trim($existingUser->role);

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'kepala-bumdes') {
                return redirect()->route('kepala-bumdes.dashboard');
            } elseif ($role === 'mitra') {
                if ($existingUser->status === 'aktif') {
                    return redirect()->route('mitra.dashboard');
                } else {
                    return redirect()->route('mitra.menunggu');
                }
            } elseif ($role === 'customer') {
                return redirect()->route('customer.dashboard');
            }
            return redirect()->route('index');

        } else {
            // JIKA USER BARU, SIMPAN DATA KE SESSION DULU SEBELUM REGISTRASI
            session([
                'google_email' => $googleUser->getEmail(),
                'google_name'  => $googleUser->getName(),
                'google_id'    => $googleUser->getId()
            ]);

            // Arahkan ke rute registrasi (pastikan rute ini ada di web.php)
            return redirect()->route('register.pelanggan');
        }
    } catch (\Exception $e) {
        Log::error('Google Login Error: ' . $e->getMessage());
        return redirect()->route('login')->with('error', 'Gagal login Google.');
    }
}

    public function storeMitra(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'sku' => 'required|file|mimes:jpg,png,pdf',
            'syarat' => 'accepted',
            'jenis_usaha' => 'required|in:Jasa,Produk'
        ]);

        // Gunakan Database Transaction agar jika satu gagal, semua batal (aman)
        return DB::transaction(function () use ($request) {

            // 1. Simpan ke tabel USERS (Hanya untuk Login)
            $user = User::create([
                'name' => $request->nama_pemilik,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mitra',
                'status' => 'pending', // Akun belum aktif sampai di ACC
            ]);

            // 2. Simpan Detail ke tabel MITRAS
            Mitra::create([
                'user_id' => $user->id, // Hubungkan dengan id di tabel users
                'nama_usaha' => $request->nama_usaha,
                'nama_pemilik' => $request->nama_pemilik,
                'jenis_usaha' => $request->jenis_usaha,
                'nik' => $request->nik,
                'alamat_usaha' => $request->alamat_usaha,
                'no_hp' => $request->no_hp,
                'dusun' => $request->dusun,
                'sku' => $request->file('sku')->store('dokumen_mitra/sku', 'public'),
                'status' => 'pending', // Detail usaha juga statusnya pending
            ]);

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('mitra.menunggu')->with('success', 'Pendaftaran berhasil!');
        });
    }
}
