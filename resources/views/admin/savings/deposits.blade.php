@extends('layouts.admin')

@section('title', 'Setoran Tabungan Warga | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Setoran Tabungan Warga</h1>
        <p class="text-sm text-slate-500 mt-1">Catat dan kelola setoran tabungan tunai maupun transfer dari warga.</p>
    </div>
    <button onclick="toggleModal('modal-tambah-setoran', true)" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Input Setoran Manual
    </button>
</div>

<!-- Histori Transaksi Tabungan -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h3 class="font-bold text-slate-800">Daftar Transaksi Masuk</h3>
        <div class="flex gap-2">
            <input type="text" placeholder="Cari nama/unit..." class="bg-white border border-slate-200 rounded-lg px-4 py-2 text-xs outline-none focus:border-amber-500 transition-all">
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                    <th class="py-4 px-6">Warga / Unit</th>
                    <th class="py-4 px-6">Program Tabungan</th>
                    <th class="py-4 px-6">Nominal</th>
                    <th class="py-4 px-6">Metode & Tgl</th>
                    <th class="py-4 px-6 text-center">Status</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $trx)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($trx->resident->name) }}&background=0ea5e9&color=fff" class="w-8 h-8 rounded-full shadow-sm">
                            <div>
                                <p class="font-bold text-slate-800 leading-none">{{ $trx->resident->name }}</p>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase font-black">Blok {{ $trx->resident->block->name }} / No. {{ $trx->resident->unit_no }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">{{ $trx->program->name }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-black text-emerald-600">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-black uppercase {{ $trx->method == 'Cash' ? 'text-amber-600 bg-amber-50 border-amber-100' : 'text-blue-600 bg-blue-50 border-blue-100' }} px-2 py-0.5 rounded border">
                                {{ $trx->method }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium">{{ $trx->transaction_date->format('d M Y') }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-flex items-center gap-1.5 text-emerald-600 text-xs font-black uppercase">
                            <i class="fa-solid fa-circle text-[6px]"></i> Selesai
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <form action="{{ route('admin.savings.deposits.destroy', $trx->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini? Saldo program akan berkurang otomatis.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-20 text-center text-slate-400 italic">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-receipt text-4xl mb-3 opacity-20"></i>
                            <p>Belum ada riwayat setoran masuk.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MODAL: INPUT SETORAN MANUAL ================= -->
<div id="modal-tambah-setoran" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="relative h-32 bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 flex items-center px-8 overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="relative z-10">
                <h3 class="font-black text-white text-2xl">Input Setoran Manual</h3>
                <p class="text-emerald-300 text-sm font-medium">Pencatatan tabungan langsung dari pengurus.</p>
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
                    <select name="resident_id" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all cursor-pointer">
                        <option value="">-- Pilih Nama Warga / Unit --</option>
                        @foreach($residents as $res)
                        <option value="{{ $res->id }}">{{ $res->name }} (Blok {{ $res->block->name }} / {{ $res->unit_no }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Pilih Program Tabungan</label>
                    <select name="savings_program_id" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all cursor-pointer">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $prog)
                        <option value="{{ $prog->id }}">{{ $prog->name }}</option>
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
                            <input type="radio" name="method" value="Cash" checked class="hidden peer">
                            <div class="p-4 border-2 border-slate-100 rounded-2xl text-center font-bold text-slate-400 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition-all">
                                <i class="fa-solid fa-money-bill-wave mb-1 block"></i> Tunai / Cash
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="method" value="Transfer" class="hidden peer">
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
</script>
@endpush
@endsection
