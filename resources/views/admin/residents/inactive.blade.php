@extends('layouts.admin')

@section('title', 'Warga Non Aktif | T-Link Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Direktori Warga Non Aktif</h1>
    <p class="text-sm text-slate-500 mt-1">Daftar warga yang sudah pindah, meninggal, atau tidak aktif lagi.</p>
</div>

<div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
                    <th class="py-3 px-4">Profil Warga</th>
                    <th class="py-3 px-4">Unit Terakhir</th>
                    <th class="py-3 px-4">Tgl Keluar</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Alasan</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($residents as $resident)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-3 px-4 flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->name) }}&background=64748b&color=fff" class="w-9 h-9 rounded-full grayscale">
                        <div>
                            <p class="font-medium text-slate-800">{{ $resident->name }}</p>
                            <p class="text-[11px] text-slate-400">NIK: {{ $resident->nik }}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 font-medium">
                        Blok {{ $resident->block->name }} / No. {{ $resident->unit_no }}
                    </td>
                    <td class="py-3 px-4 text-slate-600">
                        {{ $resident->exit_date ? $resident->exit_date->format('d M Y') : '-' }}
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold 
                            {{ $resident->exit_status == 'Meninggal' ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-600' }}">
                            {{ $resident->exit_status ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-slate-500 text-xs italic max-w-xs truncate">
                        {{ $resident->exit_reason ?? '-' }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        <form action="{{ route('admin.residents.destroy', $resident->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus permanen data warga ini?')" class="text-slate-400 hover:text-rose-500 p-1 transition" title="Hapus Permanen">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-slate-400 italic">Tidak ada data warga non aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
