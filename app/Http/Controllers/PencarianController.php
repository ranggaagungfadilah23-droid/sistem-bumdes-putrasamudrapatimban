<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PencarianController extends Controller
{
    public function index(Request $request)
    {
        $query = strtolower(trim($request->q));
        $role = Auth::user()->role;

        if (empty($query)) {
            return redirect()->back()->with('error', 'Masukkan kata kunci pencarian.');
        }

        // 1. DAFTAR SEMUA FITUR / MENU BERDASARKAN ROLE
        $semuaMenu = [];

        if ($role === 'admin') {
            $semuaMenu = [
                ['title' => 'Dashboard Admin', 'url' => route('admin.dashboard'), 'icon' => 'fa-chart-pie'],
                ['title' => 'Persetujuan Mitra Baru', 'url' => route('admin.pengajuan'), 'icon' => 'fa-user-check'],
                ['title' => 'Kelola Data Mitra', 'url' => route('admin.mitra.index'), 'icon' => 'fa-users'],
                ['title' => 'Bagi Hasil Keuangan', 'url' => route('admin.bagihasil'), 'icon' => 'fa-hand-holding-usd'],
                ['title' => 'Laporan Sistem', 'url' => route('admin.laporan'), 'icon' => 'fa-file-invoice-dollar'],

            ];
        } elseif ($role === 'kepala-bumdes') {
            $semuaMenu = [
                ['title' => 'Dashboard Kepala', 'url' => route('kepala-bumdes.dashboard'), 'icon' => 'fa-chart-line'],
                // Kata kunci disesuaikan agar mudah dicari (tambah beberapa variasi)
                ['title' => 'Persetujuan Akhir Mitra', 'url' => route('kepala-bumdes.pengajuan'), 'icon' => 'fa-file-signature', 'keywords' => ['persetujuan mitra', 'persetujuan akhir', 'pengajuan']],
                ['title' => 'Data Mitra Aktif', 'url' => route('kepala-bumdes.data-mitra'), 'icon' => 'fa-users', 'keywords' => ['data mitra', 'mitra aktif']],
            ];
        }

        // 2. CEK APAKAH ADA FITUR YANG SANGAT COCOK (DIRECT REDIRECT)
        foreach ($semuaMenu as $menu) {
            $judulKecil = strtolower($menu['title']);

            // Cek kecocokan persis dengan judul
            if ($judulKecil === $query || str_contains($judulKecil, $query)) {
             
                if (strlen($query) > 4) {
                    return redirect($menu['url']);
                }
            }

            // Cek kecocokan melalui keywords tambahan (jika ada)
            if (isset($menu['keywords'])) {
                foreach ($menu['keywords'] as $keyword) {
                    if (strtolower($keyword) === $query || str_contains(strtolower($keyword), $query)) {
                        return redirect($menu['url']);
                    }
                }
            }
        }

        // --- JIKA TIDAK ADA FITUR YANG COCOK, LANJUTKAN PENCARIAN BIASA ---

        // Saring menu yang sebagian mengandung kata kunci (untuk fallback)
        $fiturDitemukan = array_filter($semuaMenu, function($menu) use ($query) {
            return str_contains(strtolower($menu['title']), $query);
        });

        // 3. PENCARIAN DATA MITRA DI DATABASE
        $mitraDitemukan = collect();
        if (in_array($role, ['admin', 'kepala-bumdes'])) {
            $mitraDitemukan = User::with('mitra')
                ->where('role', 'mitra')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhereHas('mitra', function($sub) use ($query) {
                          $sub->where('nama_usaha', 'like', "%{$query}%");
                      });
                })->get();
        }

        // Jika hanya data mitra yang dicari
        return view('cari.index', compact('query', 'fiturDitemukan', 'mitraDitemukan'));
    }
}
