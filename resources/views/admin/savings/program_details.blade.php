@extends('layouts.admin')

@section('title', 'Rincian Program Tabungan | T-Link Admin')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.savings.programs') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition shadow-sm">
        <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Rincian Peserta Program</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $program->name }} (ID: {{ $program->program_id }})</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Info Program Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Agenda</p>
                <div class="flex items-center gap-2">
                    @if($program->status == 'active')
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-sm font-bold text-slate-700">Aktif (Menerima Setoran)</span>
                    @elseif($program->status == 'locked')
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-sm font-bold text-slate-700">Terkunci (Diverifikasi)</span>
                    @else
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-sm font-bold text-slate-700">Selesai (Sudah Cair)</span>
                    @endif
                </div>
            </div>

            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Progres Dana</p>
                <div class="flex justify-between text-xs font-bold mb-1.5">
                    <span class="text-slate-600">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                    <span class="text-amber-600">{{ $program->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ $program->progress_percentage }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-2">Target: Rp {{ number_format($program->target_amount, 0, ',', '.') }}</p>
            </div>

            <div class="pt-4 border-t border-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi</p>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $program->description ?? 'Tidak ada keterangan tambahan.' }}</p>
            </div>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Daftar Penabung Aktif</h3>
                <span class="text-[10px] font-black text-slate-400 uppercase bg-white px-2.5 py-1 rounded-lg border border-slate-200">{{ $participants->count() }} Orang</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-white text-slate-500 font-semibold border-b border-slate-100">
                            <th class="py-4 px-6">Warga / Unit</th>
                            <th class="py-4 px-6">Setoran Terakhir</th>
                            <th class="py-4 px-6">Total Tabungan</th>
                            <th class="py-4 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($participants as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($p->resident->name) }}&background=0ea5e9&color=fff" class="w-8 h-8 rounded-full shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-800 leading-none">{{ $p->resident->name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-black">Blok {{ $p->resident->block->name }} / {{ $p->resident->unit_no }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($p->last_deposit)->format('d M Y') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-black text-emerald-600">Rp {{ number_format($p->total_saved, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-lg border border-emerald-100">AKTIF</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center text-slate-400 italic">
                                Belum ada penabung di program ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
