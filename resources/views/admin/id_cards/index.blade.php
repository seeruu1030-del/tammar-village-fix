@extends('layouts.admin')

@section('title', 'ID Card Warga | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">ID Card Digital Warga</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola dan cetak kartu identitas digital secara kolektif atau individu.</p>
    </div>
    <div class="flex gap-2">
        <button onclick="window.print()" class="bg-slate-800 hover:bg-black text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg transition-all flex items-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Massal (Table View)
        </button>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
                    <th class="py-3 px-4">Nama Warga</th>
                    <th class="py-3 px-4">Unit Rumah</th>
                    <th class="py-3 px-4">Status Hunian</th>
                    <th class="py-3 px-4 text-center">Aksi ID Card</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($residents as $resident)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-3 px-4 flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->name) }}&background=0ea5e9&color=fff" class="w-9 h-9 rounded-full">
                        <div class="font-medium text-slate-800">{{ $resident->name }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-medium text-slate-700">Blok {{ $resident->block->name }} / No. {{ $resident->unit_no }}</span>
                    </td>
                    <td class="py-3 px-4 text-xs font-bold text-slate-500">
                        {{ $resident->housing_status }}
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="previewIDCard({{ json_encode($resident) }}, '{{ $resident->block->name }}')" class="bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all flex items-center gap-1.5 border border-sky-100">
                                <i class="fa-solid fa-eye"></i> PREVIEW
                            </button>
                            <button onclick="printSingleCard({{ $resident->id }})" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all flex items-center gap-1.5 border border-emerald-100">
                                <i class="fa-solid fa-file-pdf"></i> CETAK PDF
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-10 text-center text-slate-400 font-medium">Belum ada warga aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Preview ID Card -->
<div id="modal-preview-idcard" class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-[2px] px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-lg transform scale-95 transition-all duration-200 flex flex-col items-center">
        <!-- THE CARD COMPONENT (to be printed) -->
        <div id="id-card-to-print" class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden shadow-2xl border border-slate-800 flex flex-col justify-between" style="width: 85mm; height: 55mm;">
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[8px] font-bold text-sky-400 uppercase tracking-[0.2em] mb-1">Resident Digital ID</p>
                    <h4 class="text-sm font-bold tracking-tight">The Tamar Village</h4>
                </div>
                <div class="bg-emerald-500/20 text-emerald-400 text-[7px] font-bold px-1.5 py-0.5 rounded border border-emerald-500/30 uppercase">Active</div>
            </div>
            <div class="flex items-center gap-4 relative z-10 mt-2">
                <img id="prev-avatar" src="" class="w-16 h-16 rounded-xl border-2 border-slate-700 shadow-xl object-cover">
                <div class="flex-1">
                    <h3 id="prev-name" class="text-lg font-bold leading-tight truncate"></h3>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center gap-2 text-[10px] text-slate-400"><i class="fa-solid fa-house text-sky-500 w-3"></i><span id="prev-unit"></span></div>
                        <div class="flex items-center gap-2 text-[10px] text-slate-400"><i class="fa-solid fa-user-tag text-sky-500 w-3"></i><span id="prev-status"></span></div>
                    </div>
                </div>
                <div class="bg-white p-1.5 rounded-lg shadow-lg">
                    <i class="fa-solid fa-qrcode text-slate-900 text-3xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/5 relative z-10 flex justify-between items-end">
                <p class="text-[7px] text-slate-500 uppercase font-medium">Valid until {{ date('M Y', strtotime('+1 year')) }}</p>
                <p class="text-[8px] font-bold text-sky-400">ADMIN CERTIFIED</p>
            </div>
        </div>

        <div class="mt-8 flex gap-3 w-full">
            <button onclick="closePreview()" class="flex-1 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition">Tutup</button>
            <button onclick="window.print()" class="flex-1 py-2.5 bg-sky-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-sky-600/20 hover:bg-sky-700 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak PDF
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewIDCard(resident, blockName) {
        document.getElementById('prev-name').innerText = resident.name;
        document.getElementById('prev-unit').innerText = `Blok ${blockName} / No. ${resident.unit_no}`;
        document.getElementById('prev-status').innerText = resident.housing_status;
        document.getElementById('prev-avatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(resident.name)}&background=0ea5e9&color=fff&size=200`;
        
        const modal = document.getElementById('modal-preview-idcard');
        const box = modal.querySelector('div');
        modal.classList.remove('invisible', 'opacity-0');
        box.classList.remove('scale-95');
    }

    function closePreview() {
        const modal = document.getElementById('modal-preview-idcard');
        const box = modal.querySelector('div');
        box.classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('invisible'), 200);
    }

    function printSingleCard(id) {
        // Find resident in table or fetch, then show preview and trigger print
        // For simplicity, we can trigger print from the already populated preview modal
        // or open a dedicated print window
        alert('Memicu dialog cetak PDF untuk warga ID: ' + id);
        window.print();
    }

    // Optimization: Click outside to close
    document.getElementById('modal-preview-idcard').onclick = function(e) {
        if (e.target === this) closePreview();
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; height: 0; padding: 0; margin: 0; }
        #id-card-to-print, #id-card-to-print * { visibility: visible; height: auto; }
        #id-card-to-print { 
            position: fixed; 
            left: 50%; 
            top: 50%; 
            transform: translate(-50%, -50%) scale(1.5); 
            box-shadow: none !important;
            border: 1px solid #e2e8f0 !important;
        }
        @page { size: auto; margin: 0; }
    }
</style>
@endpush
@endsection
