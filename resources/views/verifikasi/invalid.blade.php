@extends('theme.default')
@section('content')
<div class="min-h-screen flex items-center justify-center p-8">
    <div class="bg-white rounded-3xl shadow-lg border border-rose-100 p-10 max-w-md w-full text-center">
        <div class="w-20 h-20 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-times-circle text-4xl text-rose-500"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-800 mb-2">Dokumen Tidak Valid</h1>
        <p class="text-slate-400 text-sm">Dokumen ini tidak ditemukan atau tidak aktif dalam sistem BUMDes Patimban.</p>
        <p class="text-xs text-slate-300 mt-6">BUMDes Putra Samudra Patimban &bull; Sistem Verifikasi Digital</p>
    </div>
</div>
@endsection
