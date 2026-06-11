@extends('layouts.admin')

@section('title', 'Program Tabungan Warga | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Program Tabungan Warga</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar agenda tabungan bersama yang sedang berjalan.</p>
    </div>
    <button onclick="toggleModal('modal-tambah-program', true)" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Program Baru
    </button>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 shadow-lg shadow-amber-500/20 text-white relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <p class="text-amber-100 text-xs font-black uppercase tracking-widest mb-1">Total Dana Tabungan</p>
        <h3 class="text-3xl font-black">Rp {{ number_format($stats['total_dana'], 0, ',', '.') }}</h3>
        <p class="text-[10px] text-amber-100/80 mt-4 font-bold"><i class="fa-solid fa-circle-nodes mr-1"></i> Tersebar di {{ $programs->count() }} Program Aktif</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-slate-50 rounded-full blur-xl"></div>
        <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Penabung Aktif</p>
        <h3 class="text-3xl font-black text-slate-800">{{ $stats['penabung_aktif'] }} Warga</h3>
        <p class="text-[10px] text-emerald-500 mt-4 font-bold"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Update Real-time</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-slate-50 rounded-full blur-xl"></div>
        <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Rata-rata Setoran</p>
        <h3 class="text-3xl font-black text-slate-800">Rp {{ number_format($stats['rata_setoran'], 0, ',', '.') }}</h3>
        <p class="text-[10px] text-slate-400 mt-4 font-bold">Per KK setiap bulannya</p>
    </div>
</div>

<!-- Program Tabungan Berjalan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($programs as $program)
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
        <div>
            <div class="flex justify-between items-start mb-3">
                <span class="bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-lg">ID: {{ $program->program_id }}</span>
                @if($program->status == 'active')
                    <span class="bg-sky-50 text-sky-600 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-sky-100">AKTIF</span>
                @elseif($program->status == 'locked')
                    <span class="bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-amber-100">TERKUNCI</span>
                @else
                    <span class="bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-emerald-100">SELESAI</span>
                @endif
            </div>
            <h3 class="font-black text-slate-800 text-lg group-hover:text-amber-600 transition-colors">{{ $program->name }}</h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $program->description ?? 'Tidak ada deskripsi program.' }}</p>
            
            <div class="my-6 py-4 bg-slate-50 rounded-xl border border-slate-100 flex flex-col items-center justify-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Dana Terkumpul</p>
                <h4 class="text-xl font-black text-emerald-600">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="border-t border-slate-50 pt-4 flex justify-between items-center">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <i class="fa-solid fa-users mr-1 text-slate-300"></i> 
                {{ $program->participants_count }} Penabung
            </span>
            <div class="flex gap-2">
                <button onclick="openEditProgramModal({{ $program->id }})" class="w-8 h-8 rounded-lg text-slate-400 hover:text-sky-500 hover:bg-sky-50 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                </button>
                <a href="{{ route('admin.savings.programs.details', $program->id) }}" class="text-amber-600 hover:text-amber-700 text-xs font-black uppercase tracking-widest flex items-center gap-1.5 group/link">
                    Rincian <i class="fa-solid fa-chevron-right text-[8px] group-hover/link:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-slate-200 flex flex-col items-center justify-center text-center px-4">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
            <i class="fa-solid fa-piggy-bank text-3xl text-slate-200"></i>
        </div>
        <h4 class="font-bold text-slate-400">Belum ada program tabungan.</h4>
        <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Program Baru" untuk memulai agenda tabungan warga.</p>
    </div>
    @endforelse
</div>

<!-- ================= MODAL: TAMBAH/EDIT PROGRAM ================= -->
<div id="modal-tambah-program" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="relative h-32 bg-gradient-to-br from-slate-900 via-slate-800 to-amber-900 flex items-center px-8 overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="relative z-10">
                <h3 class="font-black text-white text-2xl" id="modal-title">Program Tabungan</h3>
                <p class="text-amber-300 text-sm font-medium">Atur agenda dan target tabungan bersama warga.</p>
            </div>
            <button onclick="toggleModal('modal-tambah-program', false)" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-2xl bg-white/10 text-white hover:bg-rose-500 transition-all backdrop-blur-md">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-program" action="{{ route('admin.savings.programs.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div id="method-field"></div>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-amber-500 transition-colors">ID Program</label>
                        <input type="text" name="program_id" id="prog-id" required placeholder="PROG-01" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-amber-500 transition-colors">Status</label>
                        <select name="status" id="prog-status" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 outline-none transition-all cursor-pointer">
                            <option value="active">Aktif</option>
                            <option value="locked">Terkunci</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-amber-500 transition-colors">Nama Program Tabungan</label>
                    <input type="text" name="name" id="prog-name" required placeholder="Contoh: Tabungan Kurban 2027" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-amber-500 transition-colors">Target Dana (Rp)</label>
                        <input type="number" name="target_amount" id="prog-target" required min="0" placeholder="50000000" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-amber-500 transition-colors">Batas Waktu</label>
                        <input type="date" name="end_date" id="prog-end-date" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-amber-500 transition-colors">Deskripsi Program</label>
                    <textarea name="description" id="prog-desc" rows="3" placeholder="Jelaskan tujuan atau ketentuan program tabungan ini..." class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-medium text-slate-800 focus:border-amber-500 focus:bg-white focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"></textarea>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-amber-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:shadow-amber-500/30 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                    <i class="fa-solid fa-save"></i> <span id="btn-submit-text">Simpan Program Tabungan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleModal(id, show) {
        const modal = document.getElementById(id);
        const box = modal.querySelector('div');
        if (show) {
            modal.classList.remove('invisible', 'opacity-0');
            box.classList.remove('scale-95');
        } else {
            box.classList.add('scale-95');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('invisible'), 200);
        }
    }

    function openEditProgramModal(id) {
        fetch(`/admin/savings-programs/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('modal-title').innerText = 'Edit Program Tabungan';
                document.getElementById('btn-submit-text').innerText = 'Update Perubahan Program';
                document.getElementById('prog-id').value = data.program_id;
                document.getElementById('prog-name').value = data.name;
                document.getElementById('prog-status').value = data.status;
                document.getElementById('prog-target').value = Math.round(data.target_amount);
                document.getElementById('prog-end-date').value = data.end_date || '';
                document.getElementById('prog-desc').value = data.description || '';
                document.getElementById('form-program').action = `/admin/savings-programs/${id}`;
                document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                toggleModal('modal-tambah-program', true);
            });
    }

    // Reset modal on add
    window.addEventListener('click', (e) => {
        if(e.target.innerText && e.target.innerText.includes('Tambah Program Baru')) {
            document.getElementById('modal-title').innerText = 'Program Tabungan';
            document.getElementById('btn-submit-text').innerText = 'Simpan Program Tabungan';
            document.getElementById('prog-id').value = '';
            document.getElementById('prog-name').value = '';
            document.getElementById('prog-status').value = 'active';
            document.getElementById('prog-target').value = '';
            document.getElementById('prog-end-date').value = '';
            document.getElementById('prog-desc').value = '';
            document.getElementById('form-program').action = "{{ route('admin.savings.programs.store') }}";
            document.getElementById('method-field').innerHTML = '';
        }
    });
</script>
@endpush
@endsection
