@extends('layouts.admin')

@section('title', 'Persetujuan Warga | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Menunggu Persetujuan</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar warga baru yang baru ditambahkan dan belum diaktifkan.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.residents.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Direktori
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
                    <th class="py-3 px-4">Nama Warga</th>
                    <th class="py-3 px-4">NIK</th>
                    <th class="py-3 px-4">Kontak</th>
                    <th class="py-3 px-4">Blok / Unit</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($residents as $resident)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-3 px-4 font-medium text-slate-800">{{ $resident->name }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $resident->nik }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $resident->contact }}</td>
                    <td class="py-3 px-4">Blok {{ $resident->block->name }} / {{ $resident->unit_no }}</td>
                    <td class="py-3 px-4 text-center">
                        <form id="approve-form-{{ $resident->id }}" action="{{ route('admin.residents.approve', $resident->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="button" onclick="confirmApprove('approve-form-{{ $resident->id }}')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest shadow-md transition-all">Setujui (Approve)</button>
                        </form>
                        <form id="delete-form-{{ $resident->id }}" action="{{ route('admin.residents.destroy', $resident->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('delete-form-{{ $resident->id }}')" class="text-slate-400 hover:text-rose-500 p-1.5 ml-2 transition"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-slate-400">Tidak ada data warga yang menunggu persetujuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Konfirmasi Approve -->
<div id="modal-confirm-approve" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-all duration-200">
        <div class="p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-user-check text-2xl text-emerald-600"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Setujui Warga Baru?</h3>
            <p class="text-sm text-slate-500">Dengan menyetujui, warga ini akan resmi masuk ke direktori aktif perumahan dan akun login mereka akan langsung dapat digunakan.</p>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-center gap-3">
            <button onclick="closeApproveModal()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-100 transition-colors">Batal</button>
            <button id="btn-confirm-approve" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 transition-colors flex items-center gap-2">
                Ya, Setujui Sekarang
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentApproveFormId = null;

    function confirmApprove(formId) {
        currentApproveFormId = formId;
        const modal = document.getElementById('modal-confirm-approve');
        const box = modal.querySelector('div');
        modal.classList.remove('invisible', 'opacity-0');
        box.classList.remove('scale-95');
    }

    function closeApproveModal() {
        const modal = document.getElementById('modal-confirm-approve');
        const box = modal.querySelector('div');
        box.classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('invisible'), 200);
        currentApproveFormId = null;
    }

    document.getElementById('btn-confirm-approve').addEventListener('click', function() {
        if (currentApproveFormId) {
            document.getElementById(currentApproveFormId).submit();
        }
    });
</script>
@endpush
@endsection
