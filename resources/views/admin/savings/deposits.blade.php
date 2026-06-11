@extends('layouts.admin')

@section('title', 'Setoran Tabungan Warga | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Setoran Tabungan Warga</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola setoran tabungan warga secara kolektif per program.</p>
    </div>
    <div class="flex gap-2">
        <form action="{{ route('admin.savings.deposits') }}" method="GET" id="filter-form" class="flex gap-2">
            <select name="program_id" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg px-4 py-2 text-sm font-bold text-slate-700 outline-none focus:border-amber-500 transition-all">
                <option value="">-- Pilih Program Tabungan --</option>
                @foreach($programs as $prog)
                <option value="{{ $prog->id }}" {{ $selected_program_id == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                @endforeach
            </select>
        </form>
        <button onclick="toggleModal('modal-tambah-setoran', true)" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Input Setoran Manual
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- List Warga & Saldo -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Daftar Warga & Saldo {{ $selected_program_id ? 'Program' : 'Total' }}</h3>
                <input type="text" id="search-warga" placeholder="Cari warga..." class="bg-white border border-slate-200 rounded-lg px-4 py-1.5 text-xs outline-none focus:border-amber-500 transition-all">
            </div>
            <div class="overflow-y-auto max-h-[600px]">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100 sticky top-0 z-10">
                            <th class="py-4 px-6">Warga / Unit</th>
                            <th class="py-4 px-6 text-right">Saldo Saat Ini</th>
                            @if($selected_program)
                            <th class="py-4 px-6 text-center w-48">Progress Tabungan</th>
                            @endif
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="warga-table-body">
                        @foreach($residents as $res)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($res->name) }}&background=0ea5e9&color=fff" class="w-8 h-8 rounded-full shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-800 leading-none">{{ $res->name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1 uppercase font-black">Blok {{ $res->block->name }} / {{ $res->unit_no }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <p class="font-black text-slate-700">Rp {{ number_format($res->current_balance, 0, ',', '.') }}</p>
                            </td>
                            @if($selected_program)
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $res->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-600 w-8">{{ $res->progress_percentage }}%</span>
                                </div>
                                <p class="text-[9px] text-slate-400 mt-1 font-bold text-center">Target: Rp {{ number_format($selected_program->target_amount, 0, ',', '.') }}</p>
                            </td>
                            @endif
                            <td class="py-4 px-6 text-center">
                                <button onclick="openDepositModal({{ $res->id }}, '{{ $res->name }}')" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                    Setor
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Verifikasi Setoran Masuk -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Verifikasi Setoran Tabungan</h3>
                <span class="text-[9px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-black uppercase tracking-tighter">Butuh Cek</span>
            </div>
            <div class="p-5 space-y-4 max-h-[600px] overflow-y-auto">
                @forelse($transactions as $trx)
                <div class="flex items-start gap-3 border-b border-slate-50 pb-4 last:border-0 last:pb-0">
                    <div class="w-10 h-10 rounded-xl {{ $trx->status == 'success' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $trx->status == 'success' ? 'fa-check-double' : 'fa-clock-rotate-left' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $trx->resident->name }}</p>
                            <span class="text-[10px] font-black {{ $trx->status == 'success' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $trx->status == 'success' ? '+' : '' }}{{ number_format($trx->amount/1000, 0) }}k
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $trx->program->name }}</p>
                        <p class="text-[9px] text-slate-400 mt-1">{{ $trx->transaction_date->translatedFormat('d M Y, H:i') }}</p>
                        
                        @if($trx->status == 'pending_verification')
                        <div class="mt-2 flex gap-2">
                            <form action="{{ route('admin.savings.deposits.approve', $trx->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-emerald-500 text-white px-3 py-1 rounded text-[9px] font-black uppercase tracking-tighter hover:bg-emerald-600 transition-all shadow-sm shadow-emerald-500/20">Setujui</button>
                            </form>
                            @if($trx->proof_path)
                            <button onclick="openDocumentPreview('{{ asset('storage/' . $trx->proof_path) }}')" class="bg-slate-100 text-slate-600 px-3 py-1 rounded text-[9px] font-black uppercase tracking-tighter hover:bg-slate-200 transition-all">Lihat Bukti</button>
                            @endif
                        </div>
                        @else
                        <p class="text-[9px] text-emerald-500 font-black uppercase mt-1 tracking-widest"><i class="fa-solid fa-circle-check mr-1"></i> Terverifikasi</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <i class="fa-solid fa-history text-3xl text-slate-100 mb-2"></i>
                    <p class="text-xs text-slate-400">Belum ada aktivitas.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: INPUT SETORAN MANUAL ================= -->
<div id="modal-tambah-setoran" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="relative h-32 bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 flex items-center px-8 overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="relative z-10">
                <h3 class="font-black text-white text-2xl" id="modal-title">Input Setoran Manual</h3>
                <p class="text-emerald-300 text-sm font-medium">Pencatatan tabungan warga.</p>
            </div>
            <button onclick="toggleModal('modal-tambah-setoran', false)" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-2xl bg-white/10 text-white hover:bg-rose-500 transition-all backdrop-blur-md">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.savings.deposits.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div class="space-y-4">
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Pilih Warga Penabung</label>
                    <select name="resident_id" id="modal-resident-id" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all cursor-pointer">
                        <option value="">-- Pilih Nama Warga / Unit --</option>
                        @foreach($residents as $res)
                        <option value="{{ $res->id }}">{{ $res->name }} (Blok {{ $res->block->name }} / {{ $res->unit_no }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Pilih Program Tabungan</label>
                    <select name="savings_program_id" id="modal-program-id" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all cursor-pointer">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $prog)
                        <option value="{{ $prog->id }}" {{ $selected_program_id == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Nominal Setoran (Rp)</label>
                        <input type="number" name="amount" required min="1" placeholder="500000" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Tanggal Setoran</label>
                        <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Metode Pembayaran</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="payment_method" value="Cash" checked class="hidden peer">
                            <div class="p-4 border-2 border-slate-100 rounded-2xl text-center font-bold text-slate-400 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition-all">
                                <i class="fa-solid fa-money-bill-wave mb-1 block"></i> Tunai / Cash
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="payment_method" value="Transfer" class="hidden peer">
                            <div class="p-4 border-2 border-slate-100 rounded-2xl text-center font-bold text-slate-400 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                <i class="fa-solid fa-university mb-1 block"></i> Transfer Bank
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-emerald-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:shadow-emerald-500/30 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                    <i class="fa-solid fa-paper-plane"></i> Catat Setoran Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL: PREVIEW DOKUMEN ================= -->
<div id="modal-preview-dokumen" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl overflow-hidden transform scale-95 transition-all duration-200">
        <div class="flex justify-between items-center px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-black text-slate-800 text-base uppercase">Pratinjau Bukti Transfer</h3>
            <button onclick="closeDocumentPreview()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-slate-400 hover:text-rose-500 shadow-sm border border-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 bg-slate-100">
            <iframe id="preview-doc-frame" src="" class="w-full h-[60vh] rounded-xl border border-slate-200 shadow-inner bg-white"></iframe>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDocumentPreview(url) {
        document.getElementById('preview-doc-frame').src = url;
        const modal = document.getElementById('modal-preview-dokumen');
        const box = modal.querySelector('div');
        modal.classList.remove('invisible', 'opacity-0');
        box.classList.remove('scale-95');
    }

    function closeDocumentPreview() {
        const modal = document.getElementById('modal-preview-dokumen');
        const box = modal.querySelector('div');
        box.classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('invisible');
            document.getElementById('preview-doc-frame').src = '';
        }, 200);
    }

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

    function openDepositModal(residentId, residentName) {
        document.getElementById('modal-title').innerText = 'Setoran: ' + residentName;
        document.getElementById('modal-resident-id').value = residentId;
        toggleModal('modal-tambah-setoran', true);
    }

    // Live search warga
    document.getElementById('search-warga').addEventListener('input', function(e) {
        const text = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#warga-table-body tr');
        rows.forEach(row => {
            const name = row.querySelector('p.font-bold').innerText.toLowerCase();
            const unit = row.querySelector('p.text-slate-400').innerText.toLowerCase();
            if (name.includes(text) || unit.includes(text)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection
