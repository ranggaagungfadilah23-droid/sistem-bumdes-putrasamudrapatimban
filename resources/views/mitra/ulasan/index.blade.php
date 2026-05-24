@extends('theme.default')

@section('title', 'Ulasan Masuk')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-black">⭐ Ulasan & Keluhan</h1>
        <p class="text-slate-400 text-sm mt-1">Ulasan dari customer kamu</p>
    </div>

    {{-- Rata-rata bintang --}}
    @if($rataRata)
    <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-5 mb-6 flex items-center gap-5">
        <div class="text-5xl font-extrabold text-yellow-400">{{ number_format($rataRata, 1) }}</div>
        <div>
            <div class="flex gap-0.5 text-yellow-400 text-xl mb-1">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($rataRata))
                        <i class="fas fa-star"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
            <p class="text-slate-400 text-sm">Rata-rata dari {{ $ulasan->total() }} ulasan</p>
        </div>
    </div>
    @endif

    {{-- List Ulasan --}}
    <div class="space-y-4">
        @forelse($ulasan as $u)
        <div class="bg-slate-800/60 border {{ $u->balasan_mitra ? 'border-slate-700/50' : 'border-yellow-500/30' }} rounded-2xl p-5">

            {{-- Top: nama customer + bintang + tanggal --}}
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-700 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($u->customer->name ?? 'C', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">{{ $u->customer->name ?? 'Customer' }}</p>
                        <p class="text-white-500 text-xs">#{{ $u->invoice_number }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex gap-0.5 text-yellow-400 text-sm justify-end">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $u->bintang ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-white-500 text-xs mt-0.5">{{ $u->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Label bintang --}}
            <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full mb-2
                {{ $u->bintang >= 4 ? 'bg-green-500/20 text-green-400' : ($u->bintang == 3 ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                {{ $u->label_bintang }}
            </span>

            {{-- Pesan ulasan --}}
            @if($u->pesan)
            <p class="text-slate-300 text-sm bg-slate-900/40 rounded-xl px-4 py-3 mb-3">
                "{{ $u->pesan }}"
            </p>
            @else
            <p class="text-slate-500 text-sm italic mb-3">(Tidak ada komentar)</p>
            @endif

            {{-- Balasan mitra --}}
            @if($u->balasan_mitra)
                <div class="bg-blue-600/10 border border-blue-500/20 rounded-xl px-4 py-3 mt-2">
                    <p class="text-xs text-blue-400 font-bold mb-1">💬 Balasan kamu · {{ $u->dibalas_at?->diffForHumans() }}</p>
                    <p class="text-slate-300 text-sm">{{ $u->balasan_mitra }}</p>
                </div>
            @else
                {{-- Form balas --}}
                <form action="{{ route('mitra.pendapatan.ulasan.balas', $u->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="flex gap-2">
                        <input
                            type="text"
                            name="balasan_mitra"
                            placeholder="Tulis balasan kamu..."
                            required
                            class="flex-1 bg-slate-900/60 border border-slate-600 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition"
                        >
                        <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition">
                            Balas
                        </button>
                    </div>
                </form>
                <p class="text-yellow-400 text-xs mt-1.5">⚠ Belum dibalas</p>
            @endif

        </div>
        @empty
        <div class="text-center py-16 text-slate-500">
            <i class="fas fa-star text-4xl mb-3 opacity-30"></i>
            <p class="font-semibold">Belum ada ulasan masuk</p>
            <p class="text-sm mt-1">Ulasan dari customer akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($ulasan->hasPages())
    <div class="mt-6">
        {{ $ulasan->links() }}
    </div>
    @endif

</div>
@endsection
