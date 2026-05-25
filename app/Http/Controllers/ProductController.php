<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // =============================================
    // TAMPIL DAFTAR PRODUK MILIK MITRA
    // =============================================
    public function index()
{
    // Kamu mengambil data menggunakan model Produk
    $produks = Produk::where('user_id', Auth::id())->latest()->get();

    // Lalu mengirimkannya ke view dengan compact('produks')
    return view('mitra.produk.index', compact('produks'));
}


    // =============================================
    // TAMPIL FORM TAMBAH PRODUK
    // =============================================
    public function create()
    {
        return view('mitra.produk.create');
    }

    // =============================================
    // SIMPAN PRODUK BARU
    // =============================================
    public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'nama_produk' => 'required|string|max:255',
        'harga'       => 'required|numeric|min:0',
        'jumlah'      => 'required|integer|min:0',
        'deskripsi'   => 'required|string',
        'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // 2. Upload gambar
    $path = null;
    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('produk', 's3');

        // Cek jika upload gagal
        if (!$path) {
            return redirect()->back()->withErrors(['gambar' => 'Gagal upload ke Supabase.']);
        }
    }

    // 3. Simpan ke database (HANYA SEKALI!)
    Produk::create([
        'user_id'     => Auth::id(),
        'nama_produk' => $request->nama_produk,
        'harga'       => $request->harga,
        'jumlah'      => $request->jumlah,
        'deskripsi'   => $request->deskripsi,
        'gambar'      => $path,
        'status'      => 'tersedia',
    ]);

    // 4. Redirect
    return redirect()->route('mitra.kelola')->with('success', 'Produk berhasil ditambahkan!');
}

    // =============================================
    // TAMPIL DETAIL PRODUK (CUSTOMER)
    // =============================================
    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('customer.produk.show', compact('produk'));
    }

    // =============================================
    // TAMPIL FORM EDIT PRODUK
    // =============================================
    public function edit($id)
    {
        $produk = Produk::where('user_id', Auth::id())->findOrFail($id);
        return view('mitra.produk.edit', compact('produk'));
    }

    // =============================================
    // UPDATE PRODUK
    // =============================================
    public function update(Request $request, $id)
    {
        $produk = Produk::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:0',
            'deskripsi'   => 'required|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $produk->gambar;
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
           if ($produk->gambar) {
  Storage::disk('s3')->delete($produk->gambar);
}
$path = $request->file('gambar')->store('produk', 's3');
        }

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'jumlah'      => $request->jumlah,
            'deskripsi'   => $request->deskripsi,
            'gambar'      => $path,
        ]);

        return redirect()->route('mitra.kelola')->with('success', 'Produk berhasil diperbarui!');
    }

    // =============================================
    // HAPUS PRODUK
    // =============================================
    public function destroy($id)
    {
        $produk = Produk::where('user_id', Auth::id())->findOrFail($id);

        if ($produk->gambar) {
          Storage::disk('s3')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }


}
