@extends('layouts.warga')

@section('title', 'Dashboard Portal Warga - The Tamar Village')

@section('content')
<!-- ================= VIEW: DASHBOARD ================= -->
<div id="view-dashboard" class="space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Dashboard Portal Warga</h1>
        <p class="text-sm text-slate-500 mt-1">Selamat datang kembali! Pantau tagihan dan aktivitas rumah Anda di sini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Dashboard Utama (Kiri) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg shadow-emerald-500/20">
                <h2 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}!</h2>
                <p class="text-emerald-50 mt-1">Pantau tagihan dan aktivitas rumah Anda dengan mudah melalui portal T-Link.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Iuran Card -->
                <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ now()->translatedFormat('F Y') }}</span>
                    </div>
                    <p class="text-sm text-slate-500">Status Iuran IPL</p>
                    @php
                        $currentInvoice = $invoices->where('period', now()->format('Y-m'))->first();
                    @endphp
                    <h3 class="text-xl font-bold text-slate-800 mt-1">
                        {{ $currentInvoice ? ($currentInvoice->status == 'paid' ? 'LUNAS' : ($currentInvoice->status == 'unpaid' ? 'BELUM BAYAR' : 'PENDING')) : 'TIDAK ADA TAGIHAN' }}
                    </h3>
                    @if($currentInvoice && $currentInvoice->status == 'paid')
                        <p class="text-xs text-emerald-500 mt-2 font-medium"><i class="fa-solid fa-circle-check mr-1"></i> Terverifikasi {{ $currentInvoice->updated_at->translatedFormat('d M') }}</p>
                    @elseif($currentInvoice && $currentInvoice->status == 'unpaid')
                        <p class="text-xs text-rose-500 mt-2 font-medium"><i class="fa-solid fa-circle-xmark mr-1"></i> Segera lakukan pembayaran</p>
                    @endif
                </div>

                <!-- Tabungan Card -->
                <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-piggy-bank text-lg"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Saldo</span>
                    </div>
                    <p class="text-sm text-slate-500">Total Tabungan</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-1">Rp {{ number_format($savings_balance, 0, ',', '.') }}</h3>
                    <button onclick="switchWargaView('tabungan')" class="text-xs text-sky-500 mt-2 font-bold hover:underline">Tambah Setoran <i class="fa-solid fa-chevron-right text-[8px] ml-1"></i></button>
                </div>
            </div>

            <!-- Security Voice Alert Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-microphone-lines text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Kirim Pesan Suara ke Security</h3>
                        <p class="text-[10px] text-slate-500">Gunakan untuk situasi mendesak atau laporan cepat.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ Auth::user()->resident ? Auth::user()->resident->block->name . ' / ' . Auth::user()->resident->unit_no : '-' }}', 'EMERGENCY')" class="flex flex-col items-center justify-center gap-2 p-4 bg-rose-600 hover:bg-rose-700 text-white rounded-xl shadow-lg shadow-rose-600/20 transition-all group">
                        <i class="fa-solid fa-triangle-exclamation text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[11px] font-black uppercase tracking-wider text-center">Panic Button</span>
                    </button>
                    
                    <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ Auth::user()->resident ? Auth::user()->resident->block->name . ' / ' . Auth::user()->resident->unit_no : '-' }}', 'ASSISTANCE')" class="flex flex-col items-center justify-center gap-2 p-4 bg-amber-500 hover:bg-amber-600 text-white rounded-xl shadow-lg shadow-amber-500/20 transition-all group">
                        <i class="fa-solid fa-hand-holding-heart text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[11px] font-black uppercase tracking-wider text-center">Butuh Bantuan</span>
                    </button>
                    
                    <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ Auth::user()->resident ? Auth::user()->resident->block->name . ' / ' . Auth::user()->resident->unit_no : '-' }}', 'SUSPICIOUS')" class="flex flex-col items-center justify-center gap-2 p-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-lg shadow-orange-500/20 transition-all group">
                        <i class="fa-solid fa-mask text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[11px] font-black uppercase tracking-wider text-center">Orang Mencurigakan</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Dashboard (Kanan) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Pengumuman Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm h-full min-h-[300px]">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-bullhorn text-lg"></i>
                    </div>
                    <button onclick="switchWargaView('dashboard')" class="text-[10px] font-bold text-sky-500 hover:underline">Lihat Semua</button>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter mb-2">PENGUMUMAN TERAKHIR</p>
                @if($announcements->count() > 0)
                    @foreach($announcements as $announcement)
                        <div class="mb-4 last:mb-0">
                            <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $announcement->title }}</h3>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ Str::limit(strip_tags($announcement->content), 100) }}</p>
                            <p class="text-[9px] text-slate-400 mt-1">{{ $announcement->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    @endforeach
                @else
                    <p class="text-[11px] text-slate-500 mt-3 leading-relaxed">Belum ada pengumuman terbaru.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: PROFIL SAYA (REMOVED FOR BREVITY, SAME AS BEFORE) ================= -->
<div id="view-profil" class="hidden space-y-6">
    {{-- (Isi profil tetap sama) --}}
    <div class="text-center py-10 bg-white rounded-2xl">
        <p class="text-slate-500">Gunakan sidebar untuk navigasi profil lengkap.</p>
        <button onclick="switchProfileTab('personal')" class="mt-4 bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold">Buka Tab Profil</button>
    </div>
</div>

<!-- ================= VIEW: IURAN ================= -->
<div id="view-iuran" class="hidden space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tagihan & Iuran</h1>
            <p class="text-sm text-slate-500 mt-1">Lakukan pembayaran Iuran IPL tepat waktu untuk kenyamanan bersama.</p>
        </div>
        <button id="btn-pay-now" onclick="openPaymentModal()" class="hidden bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar"></i> Bayar Tagihan Terpilih (<span id="selected-count">0</span>)
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-bold p-4 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4 w-10">
                                <input type="checkbox" id="select-all-invoices" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="py-3 px-4">Bulan Tagihan</th>
                            <th class="py-3 px-4">Jumlah</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @if($invoices->count() > 0)
                            @foreach($invoices as $invoice)
                            <tr class="{{ $invoice->status == 'unpaid' ? 'hover:bg-slate-50' : 'bg-slate-50/30' }} transition-colors">
                                <td class="py-4 px-4">
                                    @if($invoice->status == 'unpaid')
                                        <input type="checkbox" name="invoice_check" value="{{ $invoice->id }}" data-amount="{{ $invoice->amount }}" class="invoice-checkbox rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    @else
                                        <i class="fa-solid fa-lock text-slate-200 text-[10px] ml-1"></i>
                                    @endif
                                </td>
                                <td class="py-4 px-4 font-medium">
                                    <div class="font-bold text-slate-800">{{ Carbon\Carbon::parse($invoice->period . '-01')->translatedFormat('F Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $invoice->description }}</div>
                                </td>
                                <td class="py-4 px-4 text-slate-700">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-4">
                                    @if($invoice->status == 'paid')
                                        <span class="bg-emerald-50 text-emerald-600 text-[9px] font-black px-2 py-0.5 rounded uppercase border border-emerald-100">Lunas</span>
                                    @elseif($invoice->status == 'unpaid')
                                        <span class="bg-amber-50 text-amber-600 text-[9px] font-black px-2 py-0.5 rounded uppercase border border-amber-100">Belum Bayar</span>
                                    @else
                                        <span class="bg-blue-50 text-blue-600 text-[9px] font-black px-2 py-0.5 rounded uppercase border border-blue-100">Verifikasi</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @if($invoice->status == 'paid')
                                        <button class="text-slate-400 hover:text-sky-500 transition-colors"><i class="fa-solid fa-file-pdf"></i></button>
                                    @elseif($invoice->status == 'pending_verification')
                                        @if($invoice->payment_method == 'midtrans')
                                            <a href="{{ route('warga.payment.check', $invoice->midtrans_order_id) }}" class="bg-sky-500 hover:bg-sky-600 text-white px-2 py-1 rounded text-[10px] font-bold transition-all shadow-sm">Cek Status</a>
                                        @else
                                            <button onclick="openDocumentPreview('{{ asset('storage/' . $invoice->proof_path) }}')" class="text-blue-500 hover:text-blue-600 text-xs font-bold">Lihat Bukti</button>
                                        @endif
                                    @else
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Pilih Tagihan</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada riwayat tagihan.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="space-y-4">
            <div id="selection-summary" class="hidden bg-white p-6 rounded-2xl border border-emerald-100 shadow-sm space-y-4 animate-in fade-in slide-in-from-right-4 duration-300">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-emerald-600"></i> Ringkasan Pembayaran
                </h3>
                <div class="space-y-2 border-y border-slate-50 py-4">
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-slate-500">Jumlah Bulan</span>
                        <span id="summary-count" class="text-slate-800 font-bold">0 Bulan</span>
                    </div>
                    <div class="flex justify-between text-sm font-black">
                        <span class="text-slate-500">Total Bayar</span>
                        <span id="summary-total" class="text-emerald-600">Rp 0</span>
                    </div>
                </div>
                <button onclick="openPaymentModal()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-bold text-xs shadow-lg shadow-emerald-500/20 transition-all uppercase tracking-widest">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: TABUNGAN ================= -->
<div id="view-tabungan" class="hidden space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Tabungan Warga</h1>
            <p class="text-sm text-slate-500 mt-1">Simpan dana masa depan Anda melalui program tabungan bersama.</p>
        </div>
        <button onclick="openAddSetoranModal()" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus-circle"></i> Tambah Setoran Baru
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Saldo Summary -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-2xl text-white shadow-lg shadow-amber-500/20">
                <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Total Saldo Tabungan</p>
                <h2 class="text-3xl font-bold mt-2">Rp {{ number_format($savings_balance, 0, ',', '.') }}</h2>
                <div class="mt-8 pt-6 border-t border-white/10 flex justify-between items-center">
                    <div class="text-xs">
                        <p class="text-amber-200">Aktivitas Terakhir</p>
                        <p class="font-bold">{{ $savings_transactions->first() ? $savings_transactions->first()->transaction_date->translatedFormat('d M Y') : '-' }}</p>
                    </div>
                    <i class="fa-solid fa-piggy-bank text-4xl opacity-20"></i>
                </div>
            </div>

            <!-- Program Aktif List -->
            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Program Tabungan Aktif</h3>
                <div class="space-y-4">
                    @foreach($active_programs as $prog)
                    <div class="group cursor-pointer p-3 bg-slate-50 hover:bg-amber-50 rounded-xl border border-slate-100 hover:border-amber-200 transition-all" onclick="openAddSetoranModal({{ $prog->id }}, '{{ $prog->name }}')">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-xs font-bold text-slate-800 group-hover:text-amber-600">{{ $prog->name }}</h4>
                            <span class="text-[9px] font-black text-amber-500 bg-white px-1.5 py-0.5 rounded border border-amber-100">PILIH</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-amber-500 h-full transition-all" style="width: {{ $prog->progress_percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat Setoran Tabungan</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @if($savings_transactions->count() > 0)
                        @foreach($savings_transactions as $transaction)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl {{ $transaction->status == 'success' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center border border-slate-100">
                                    <i class="fa-solid {{ $transaction->status == 'success' ? 'fa-check' : 'fa-clock' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $transaction->program->name ?? 'Tabungan Umum' }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ $transaction->transaction_date->translatedFormat('d M Y, H:i') }} • {{ strtoupper($transaction->payment_method) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-slate-800">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                <span class="text-[9px] font-black uppercase tracking-widest {{ $transaction->status == 'success' ? 'text-emerald-500' : ($transaction->status == 'pending_verification' ? 'text-blue-500' : 'text-amber-500') }}">
                                    {{ $transaction->status == 'success' ? 'Berhasil' : ($transaction->status == 'pending_verification' ? 'Verifikasi' : 'Pending') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-12 text-center text-slate-400 italic">
                            <i class="fa-solid fa-receipt text-4xl mb-3 opacity-20"></i>
                            <p>Belum ada riwayat setoran.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- (VIEW-VIEW LAIN TETAP SAMA) -->

@endsection

@section('modals')
<!-- (MODAL PROFIL, KELUARGA, KENDARAAN, DOKUMEN TETAP SAMA) -->

<!-- ================= MODAL: KONFIRMASI PEMBAYARAN IURAN ================= -->
<div id="modal-bayar-iuran" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="flex justify-between items-center px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h2 class="text-xl font-black text-slate-800">Konfirmasi Pembayaran</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Selesaikan iuran IPL Anda.</p>
            </div>
            <button onclick="closePaymentModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-slate-400 hover:text-rose-500 shadow-sm border border-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form id="form-bayar-iuran" action="{{ route('warga.payment.submit') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <div id="selected-invoices-container"></div>
            
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Total Tagihan</span>
                    <span id="modal-total-count" class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold uppercase">0 Bulan</span>
                </div>
                <p id="modal-total-amount" class="text-2xl font-black text-emerald-700">Rp 0</p>
            </div>

            <div class="space-y-4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center gap-3 p-4 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                        <input type="radio" name="payment_method" value="manual" checked class="w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500" onchange="togglePaymentFields(this.value, 'iuran')">
                        <div><p class="text-xs font-bold text-slate-800">Transfer Manual</p></div>
                    </label>
                    <label class="relative flex items-center gap-3 p-4 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-emerald-500 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                        <input type="radio" name="payment_method" value="midtrans" class="w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500" onchange="togglePaymentFields(this.value, 'iuran')">
                        <div><p class="text-xs font-bold text-slate-800">Midtrans</p></div>
                    </label>
                </div>
            </div>

            <div id="manual-fields-iuran" class="space-y-2">
                <input type="file" name="payment_proof" id="proof-iuran" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>

            <div class="pt-4">
                <button type="submit" id="btn-submit-iuran" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all">Bayar Sekarang</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL: SETOR TABUNGAN ================= -->
<div id="modal-tambah-setoran" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="flex justify-between items-center px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h2 class="text-xl font-black text-slate-800">Setoran Tabungan</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Pilih program dan tentukan nominal.</p>
            </div>
            <button onclick="closeAddSetoranModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-slate-400 hover:text-rose-500 shadow-sm border border-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <form id="form-tabungan" action="{{ route('warga.savings.submit') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Program Tabungan</label>
                <select name="savings_program_id" id="savings-prog-id" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-5 py-3 text-sm font-bold text-slate-800 focus:border-amber-500 outline-none transition-all">
                    @foreach($active_programs as $prog)
                    <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="group">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nominal Setoran (Rp)</label>
                <input type="number" name="amount" required min="1000" placeholder="Contoh: 100000" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-5 py-3 text-sm font-bold text-slate-800 focus:border-amber-500 outline-none transition-all">
            </div>

            <div class="space-y-4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center gap-3 p-4 border-2 border-slate-100 rounded-2xl cursor-pointer has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/50">
                        <input type="radio" name="payment_method" value="manual" checked class="w-4 h-4 text-amber-600" onchange="togglePaymentFields(this.value, 'tabungan')">
                        <span class="text-xs font-bold text-slate-800">Transfer Manual</span>
                    </label>
                    <label class="relative flex items-center gap-3 p-4 border-2 border-slate-100 rounded-2xl cursor-pointer has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/50">
                        <input type="radio" name="payment_method" value="midtrans" class="w-4 h-4 text-amber-600" onchange="togglePaymentFields(this.value, 'tabungan')">
                        <span class="text-xs font-bold text-slate-800">Midtrans</span>
                    </label>
                </div>
            </div>

            <div id="manual-fields-tabungan" class="space-y-2">
                <input type="file" name="payment_proof" id="proof-tabungan" accept="image/*" class="w-full text-xs text-slate-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-50 file:text-amber-700">
            </div>

            <div class="pt-4">
                <button type="submit" id="btn-submit-tabungan" class="w-full bg-slate-900 hover:bg-amber-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-xl shadow-slate-900/20">Kirim Setoran</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    function openAddSetoranModal(progId = null, progName = null) {
        if (progId) document.getElementById('savings-prog-id').value = progId;
        document.getElementById('modal-tambah-setoran').classList.remove('hidden');
    }

    function closeAddSetoranModal() {
        document.getElementById('modal-tambah-setoran').classList.add('hidden');
    }

    function togglePaymentFields(method, type) {
        const manualFields = document.getElementById(`manual-fields-${type}`);
        const proofInput = document.getElementById(`proof-${type}`);
        if (method === 'manual') {
            manualFields.classList.remove('hidden');
            if (proofInput) proofInput.setAttribute('required', 'required');
        } else {
            manualFields.classList.add('hidden');
            if (proofInput) proofInput.removeAttribute('required');
        }
    }

    // Reuse common payment handler logic for both forms
    function handlePaymentForm(formId, btnId, tabName) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const method = form.querySelector('input[name="payment_method"]:checked').value;
            if (method === 'midtrans') {
                e.preventDefault();
                const btn = document.getElementById(btnId);
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        snap.pay(data.snap_token, {
                            onSuccess: () => { window.location.href = `{{ route('warga.dashboard') }}#view-${tabName}`; window.location.reload(); },
                            onPending: () => { window.location.href = `{{ route('warga.dashboard') }}#view-${tabName}`; window.location.reload(); },
                            onError: () => { alert("Gagal!"); btn.disabled = false; btn.innerHTML = originalText; },
                            onClose: () => { btn.disabled = false; btn.innerHTML = originalText; }
                        });
                    } else {
                        alert(data.message);
                        btn.disabled = false; btn.innerHTML = originalText;
                    }
                })
                .catch(() => { btn.disabled = false; btn.innerHTML = originalText; });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        handlePaymentForm('form-bayar-iuran', 'btn-submit-iuran', 'iuran');
        handlePaymentForm('form-tabungan', 'btn-submit-tabungan', 'tabungan');
    });
    
    // (JavaScript lainnya tetap sama untuk navigasi tab dll)
</script>
@endpush
