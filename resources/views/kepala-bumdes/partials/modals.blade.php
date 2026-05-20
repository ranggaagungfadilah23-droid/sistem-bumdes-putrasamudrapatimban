{{-- MODAL APPROVE DENGAN PREVIEW & EDIT --}}
<div id="approveModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-3xl w-full max-w-2xl p-8 shadow-2xl transform scale-95 transition-transform duration-300 overflow-y-auto max-h-[90vh]" id="approveModalContent">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-slate-800">Preview & Sahkan Surat</h3>
                <p class="text-sm text-slate-500">Pastikan data benar sebelum TTD Digital diterbitkan.</p>
            </div>
            <button type="button" onclick="closeApproveModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="approveForm" method="POST" action="">
            @csrf
            @method('PATCH')
           {{-- Ganti bagian input di modal approve kamu dengan ini --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-100 mb-6 text-left">
    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Pemilik</label>
        {{-- PASTIKAN ADA name="name" --}}
        <input type="text" name="name" id="prev_name" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-600 outline-none" readonly>
    </div>
    <div>
        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Usaha</label>
        <input type="text" name="nama_usaha" id="prev_nama_usaha" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-600 outline-none" readonly>
    </div>
    <div>
        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Jenis Usaha</label>
        <input type="text" name="jenis_usaha" id="prev_jenis_usaha" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-600 outline-none" readonly>
    </div>
    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Alamat Lengkap</label>
        <input type="text" name="alamat_usaha" id="prev_alamat_usaha" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2 text-sm text-slate-600 outline-none" readonly>
    </div>
</div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeApproveModal()" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Batal</button>
                <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition">Sah & Kirim Sertifikat</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL REJECT --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-3xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="rejectModalContent">
        <h3 class="text-xl font-bold text-slate-800 mb-4">Tolak Pengajuan</h3>
        <form id="rejectForm" method="POST" action="">
            @csrf
            <textarea name="pesan_penolakan" rows="3" class="w-full border border-slate-200 rounded-xl p-3 text-sm mb-5 outline-none focus:ring-2 focus:ring-rose-500" placeholder="Alasan penolakan..."></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-rose-500">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>
