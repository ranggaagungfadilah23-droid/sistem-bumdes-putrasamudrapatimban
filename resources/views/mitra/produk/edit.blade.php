@extends('theme.default')
@section('title', 'Edit Produk')

@section('content')
<div class="container-fluid px-6 py-8">
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200 max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Edit Produk</h3>
            <a href="{{ route('mitra.kelola') }}" class="text-slate-400 hover:text-slate-600 font-medium text-sm"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>

        <form action="{{ route('mitra.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ $produk->harga }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Stok (Qty)</label>
                    <input type="number" name="jumlah" value="{{ $produk->jumlah }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none" required>{{ $produk->deskripsi }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Foto Baru (Opsional)</label>
                <input type="file" name="gambar" class="w-full text-xs text-slate-500 mb-2">
                @if($produk->gambar)
                    <p class="text-xs text-slate-400 italic">Biarkan kosong jika tidak ingin mengganti foto saat ini.</p>
                @endif
            </div>

            <button type="submit" class="w-full py-3 mt-4 rounded-xl bg-blue-600 text-white font-bold text-sm shadow-lg hover:bg-blue-700 transition">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
