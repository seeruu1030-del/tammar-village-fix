@extends('layouts.admin')

@section('title', 'Daftar Iuran Warga | T-Link Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Daftar Iuran Warga</h1>
        <p class="text-sm text-slate-500 mt-1">Data warga yang telah melakukan pelunasan iuran bulanan.</p>
    </div>
    <div class="flex gap-3">
        <form action="{{ route('admin.finance.generate-mass') }}" method="POST">
            @csrf
            <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-invoice"></i> Generate Tagihan Masal
            </button>
        </form>
        <a href="{{ route('admin.finance.verification') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left"></i> Verifikasi Pembayaran
        </a>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" placeholder="Cari nama warga atau blok..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition-all">
        </div>
        <div class="flex gap-2">
            <select class="bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 outline-none focus:border-sky-500">
                <option>Semua Bulan</option>
                <option>{{ now()->translatedFormat('F Y') }}</option>
            </select>
            <button class="bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Export
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <th class="py-4 px-6">Warga / Unit</th>
                    <th class="py-4 px-6">Bulan Tagihan</th>
                    <th class="py-4 px-6">Total Bayar</th>
                    <th class="py-4 px-6">Tanggal Lunas</th>
                    <th class="py-4 px-6 text-center">Status</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($paidInvoices as $invoice)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($invoice->resident->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $invoice->resident->name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium">Blok {{ $invoice->resident->block->name }} / {{ $invoice->resident->unit_no }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-bold text-slate-700">{{ Carbon\Carbon::parse($invoice->period . '-01')->translatedFormat('F Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">{{ $invoice->description }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-bold text-slate-800">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                    </td>
                    <td class="py-4 px-6 text-slate-500">
                        {{ $invoice->updated_at->translatedFormat('d M Y, H:i') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                            Lunas
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <button class="text-slate-300 hover:text-sky-500 transition-colors">
                            <i class="fa-solid fa-file-pdf"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400 italic">Belum ada data pembayaran lunas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
