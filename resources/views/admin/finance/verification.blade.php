@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran | T-Link Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Verifikasi Pembayaran</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar bukti transfer yang menunggu validasi admin.</p>
    </div>
    <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 rounded-xl border border-amber-100">
        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
        <span class="text-xs font-bold text-amber-700">{{ $pendingInvoices->count() }} Menunggu Verifikasi</span>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <th class="py-4 px-6">Warga / Unit</th>
                    <th class="py-4 px-6">Bulan Tagihan</th>
                    <th class="py-4 px-6">Total Bayar</th>
                    <th class="py-4 px-6 text-center">Bukti Transfer</th>
                    <th class="py-4 px-6">Tgl Unggah</th>
                    <th class="py-4 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pendingInvoices as $invoice)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                {{ substr($invoice->resident->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 line-clamp-1">{{ $invoice->resident->name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium">Blok {{ $invoice->resident->block->name }} / {{ $invoice->resident->unit_no }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-bold text-slate-700 text-xs">{{ Carbon\Carbon::parse($invoice->period . '-01')->translatedFormat('F Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-medium line-clamp-1" title="{{ $invoice->description }}">{{ $invoice->description }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-black text-emerald-600">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($invoice->payment_method == 'midtrans')
                            <div class="flex flex-col items-center gap-1">
                                <span class="bg-sky-50 text-sky-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-sky-100">Midtrans</span>
                                <span class="text-[9px] text-slate-400 font-mono">{{ $invoice->midtrans_order_id }}</span>
                            </div>
                        @elseif($invoice->proof_path)
                            <button onclick="openProofModal('{{ asset('storage/' . $invoice->proof_path) }}')" class="relative group inline-block">
                                <img src="{{ asset('storage/' . $invoice->proof_path) }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm group-hover:opacity-75 transition-opacity">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-expand text-white text-xs drop-shadow-md"></i>
                                </div>
                            </button>
                        @else
                            <span class="text-[10px] text-slate-400 italic">Tidak ada bukti</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-[10px] font-bold text-slate-500 leading-tight">
                            {{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->translatedFormat('d M Y') : $invoice->updated_at->translatedFormat('d M Y') }}<br>
                            <span class="font-medium opacity-70">{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->translatedFormat('H:i') : $invoice->updated_at->translatedFormat('H:i') }} WIB</span>
                        </p>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex justify-end gap-2">
                            <form action="{{ route('admin.finance.approve', $invoice->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white w-9 h-9 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center active:scale-95" title="Setujui">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.finance.reject', $invoice->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Tolak bukti transfer ini?')" class="bg-white hover:bg-rose-50 text-rose-500 border border-slate-200 w-9 h-9 rounded-xl transition-all flex items-center justify-center active:scale-95" title="Tolak">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-20 text-center text-slate-400 italic">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-2xl mx-auto mb-4">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <p class="font-bold text-slate-800 not-italic">Antrian Verifikasi Kosong</p>
                        <p class="text-xs mt-1">Semua bukti transfer telah diproses.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="proof-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/90 backdrop-blur-md p-4" onclick="closeProofModal()">
    <img id="modal-img" class="max-w-full max-h-full rounded-2xl shadow-2xl transform scale-95 transition-transform duration-300">
    <button class="absolute top-8 right-8 text-white text-3xl hover:text-rose-500 transition-colors">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>

@push('scripts')
<script>
    function openProofModal(src) {
        const modal = document.getElementById('proof-modal');
        const img = document.getElementById('modal-img');
        img.src = src;
        modal.classList.remove('hidden');
        setTimeout(() => img.classList.remove('scale-95'), 10);
    }

    function closeProofModal() {
        const modal = document.getElementById('proof-modal');
        const img = document.getElementById('modal-img');
        img.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
</script>
@endpush
@endsection
