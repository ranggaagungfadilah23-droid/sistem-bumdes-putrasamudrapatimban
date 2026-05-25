<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use App\Models\Produk; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JasaController extends Controller
{
    // 1. Untuk Halaman Kelola Jasa (Mitra)
    public function index()
    {
        $jasas = Jasa::where('user_id', Auth::id())->latest()->get();
        return view('mitra.jasa.index', compact('jasas'));
    }

    // 2. Untuk Halaman Publik (Landing Page)
    public function landingPage()
    {
        $jasas = Jas::latest()->take(3)->get();
        $produks = Produk::latest()->take(3)->get();

        return view('index', compact('jasas', 'produks'));
    }

    // Di dalam Controller kamu
public function dashboard()
{
    // 1. Ambil data dari database
    $jasas = \App\Models\Jasa::all();

    // 2. Kirim data ke view dengan nama 'Jasas' (harus sama persis dengan di blade)
    return view('customer.dashboard', ['Jasas' => $jasas]);

   
}

    // =============================================
    // TAMPIL FORM TAMBAH JASA
    // =============================================
    public function create()
    {
        return view('mitra.jasa.create');
    }

    // =============================================
    // SIMPAN JASA BARU
    // =============================================
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'satuan'    => 'required|in:Layanan,Jam,Hari',
            'deskripsi' => 'required|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_jasa.required' => 'Nama layanan wajib diisi.',
            'harga.required'     => 'Harga wajib diisi.',
            'harga.numeric'      => 'Harga harus berupa angka.',
            'satuan.required'    => 'Satuan wajib dipilih.',
            'satuan.in'          => 'Satuan tidak valid.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'gambar.image'       => 'File harus berupa gambar.',
            'gambar.max'         => 'Ukuran gambar maksimal 2MB.',
        ]);

        // 2. Upload gambar jika ada
        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('jasa', 'public');
        }

        // 3. Simpan ke database
        Jasa::create([
            'user_id'   => Auth::id(),
            'nama_jasa' => $request->nama_jasa,
            'harga'     => $request->harga,
            'satuan'    => $request->satuan,
            'deskripsi' => $request->deskripsi,
            'gambar'    => $path,
            'status'    => 'aktif',
        ]);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('mitra.kelola')->with('success', 'Layanan jasa berhasil ditambahkan!');
    }


     public function show($id)
    {
        $jasa = Jasa::findOrFail($id);
        return view('customer.jasa.show', compact('jasa'));
    }


    // =============================================
    // TAMPIL FORM EDIT JASA
    // =============================================
    public function edit($id)
    {
        $jasa = Jasa::where('user_id', Auth::id())->findOrFail($id);
        return view('mitra.jasa.edit', compact('jasa'));
    }

    // =============================================
    // UPDATE JASA
    // =============================================
    public function update(Request $request, $id)
    {
        $jasa = Jasa::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'satuan'    => 'required|in:Layanan,Jam,Hari',
            'deskripsi' => 'required|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $jasa->gambar;
        if ($request->hasFile('gambar')) {
            if ($jasa->gambar) {
                Storage::disk('public')->delete($jasa->gambar);
            }
            $path = $request->file('gambar')->store('jasa', 'public');
        }

        $jasa->update([
            'nama_jasa' => $request->nama_jasa,
            'harga'     => $request->harga,
            'satuan'    => $request->satuan,
            'deskripsi' => $request->deskripsi,
            'gambar'    => $path,
        ]);

        return redirect()->route('mitra.kelola')->with('success', 'Layanan jasa berhasil diperbarui!');
    }

    // =============================================
    // HAPUS JASA
    // =============================================
    public function destroy($id)
    {
        $jasa = Jasa::where('user_id', Auth::id())->findOrFail($id);

        if ($jasa->gambar) {
            Storage::disk('public')->delete($jasa->gambar);
        }

        $jasa->delete();

        return redirect()->back()->with('success', 'Layanan jasa berhasil dihapus!');
    }
}
