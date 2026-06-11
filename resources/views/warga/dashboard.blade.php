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

                <!-- Tamu Card -->
                <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <i class="fa-solid fa-user-clock text-lg"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Hari Ini</span>
                    </div>
                    <p class="text-sm text-slate-500">Tamu Terdaftar</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-1">0 Tamu</h3>
                    <p class="text-xs text-slate-400 mt-2">Belum ada tamu hari ini</p>
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

<!-- ================= VIEW: PROFIL SAYA ================= -->
<div id="view-profil" class="hidden space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Profil & ID Card</h1>
            <p class="text-sm text-slate-500 mt-1">Informasi identitas digital dan data kepemilikan unit Anda.</p>
        </div>
        <button onclick="openEditProfileModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square"></i> Edit Profil
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Utama (Kiri) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Navigation Tabs -->
            <div class="flex border-b border-slate-200 gap-8 mb-2 overflow-x-auto whitespace-nowrap">
                <button id="btn-tab-personal" onclick="switchProfileTab('personal')" class="pb-3 text-sm font-bold text-emerald-600 border-b-2 border-emerald-600 transition-all">
                    <i class="fa-solid fa-user-circle mr-2"></i>Informasi Data Diri
                </button>
                <button id="btn-tab-family" onclick="switchProfileTab('family')" class="pb-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">
                    <i class="fa-solid fa-users mr-2"></i>Anggota Keluarga
                </button>
                <button id="btn-tab-document" onclick="switchProfileTab('document')" class="pb-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">
                    <i class="fa-solid fa-file-invoice mr-2"></i>Dokumen
                </button>
                <button id="btn-tab-vehicle" onclick="switchProfileTab('vehicle')" class="pb-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">
                    <i class="fa-solid fa-car mr-2"></i>Kendaraan
                </button>
                <button id="btn-tab-idcard" onclick="switchProfileTab('idcard')" class="pb-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">
                    <i class="fa-solid fa-id-card mr-2"></i>ID Card
                </button>
            </div>

            <!-- TAB CONTENT: INFORMASI DATA DIRI -->
            <div id="tab-personal" class="space-y-6">
                <!-- Data Diri Card -->
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row items-center gap-6 mb-8">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->name ?? Auth::user()->name) }}&background=10b981&color=fff&size=100" class="w-24 h-24 rounded-2xl border-4 border-slate-50 shadow-sm">
                            <div class="text-center sm:text-left">
                                <h2 class="text-xl font-bold text-slate-800">{{ $resident->name ?? Auth::user()->name }}</h2>
                                <p class="text-sm text-slate-500 font-medium">
                                    @if($resident)
                                        Blok {{ $resident->block->name }} / No. {{ $resident->unit_no }}
                                    @else
                                        -
                                    @endif
                                </p>
                                <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-2">
                                    <span class="bg-emerald-50 text-emerald-600 text-[9px] font-bold px-2 py-0.5 rounded uppercase border border-emerald-100">{{ $resident->status ?? 'Warga' }}</span>
                                    <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-2 py-0.5 rounded uppercase border border-blue-100">{{ $resident->housing_status ?? 'Owner' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-50">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">WhatsApp</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $resident->contact ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email Aktif</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $resident->email ?? Auth::user()->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">NIK</p>
                                <p class="text-sm font-semibold text-slate-700">
                                    @if($resident && $resident->nik)
                                        {{ substr($resident->nik, 0, 4) }} * * * * * * * * {{ substr($resident->nik, -4) }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ID Telegram</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $resident->telegram_id ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Terdaftar Sejak</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $resident ? $resident->created_at->translatedFormat('d F Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB CONTENT: ANGGOTA KELUARGA -->
            <div id="tab-family" class="hidden space-y-6">
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 text-sm">Daftar Anggota Keluarga</h3>
                        <button onclick="alert('Membuka form tambah anggota keluarga...')" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-emerald-700 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-plus"></i> Tambah Anggota
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-6">Nama Anggota</th>
                                    <th class="py-3 px-6">Hubungan</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @if($resident && $resident->familyMembers->count() > 0)
                                    @foreach($resident->familyMembers as $member)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=0ea5e9&color=fff" class="w-8 h-8 rounded-full">
                                                <span class="font-medium text-slate-800">{{ $member->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-slate-600">{{ $member->relationship }}</td>
                                        <td class="py-4 px-6">
                                            <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Verifikasi</span>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <button class="text-slate-400 hover:text-sky-500"><i class="fa-solid fa-pen-to-square"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada data anggota keluarga.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: DOKUMEN -->
            <div id="tab-document" class="hidden space-y-6">
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 text-sm">Arsip Dokumen Resmi</h3>
                        <button class="text-emerald-500 hover:text-emerald-600"><i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                    <div class="p-4 space-y-3">
                        @if($resident && $resident->document)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100/50 group hover:border-emerald-200 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-rose-500 border border-slate-100 shadow-sm">
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">Dokumen Kependudukan</p>
                                        <p class="text-[10px] text-slate-500 font-medium">Format: PDF/Image</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $resident->document) }}" target="_blank" class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-emerald-500 border border-slate-100 shadow-sm transition-colors"><i class="fa-solid fa-eye text-xs"></i></a>
                                    <a href="{{ asset('storage/' . $resident->document) }}" download class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-emerald-500 border border-slate-100 shadow-sm transition-colors"><i class="fa-solid fa-download text-xs"></i></a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-xs text-slate-400">Belum ada dokumen yang diunggah.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: KENDARAAN -->
            <div id="tab-vehicle" class="hidden space-y-6">
                <!-- Kendaraan Card -->
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 text-sm">Kendaraan Anda</h3>
                        <button class="text-emerald-500 hover:text-emerald-600"><i class="fa-solid fa-circle-plus"></i></button>
                    </div>
                    <div class="p-4 space-y-3">
                        @if($resident && $resident->vehicles->count() > 0)
                            @foreach($resident->vehicles as $vehicle)
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-100/50">
                                <div class="relative group cursor-pointer w-12 h-12 shrink-0" onclick="openVehiclePhoto('{{ $vehicle->license_plate }}', '{{ $vehicle->model }} • {{ $vehicle->color }}', '{{ $vehicle->photo_path ? asset('storage/' . $vehicle->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($vehicle->license_plate) . '&background=f1f5f9&color=64748b' }}')">
                                    <img src="{{ $vehicle->photo_path ? asset('storage/' . $vehicle->photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($vehicle->license_plate) . '&background=f1f5f9&color=64748b' }}" class="w-12 h-12 rounded-lg object-cover border border-white shadow-sm transition-transform group-hover:scale-105">
                                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center transition-opacity">
                                        <i class="fa-solid fa-expand text-white text-[10px]"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-slate-800 uppercase">{{ $vehicle->license_plate }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ $vehicle->model }} • {{ $vehicle->color }}</p>
                                </div>
                                <div class="bg-emerald-100 text-emerald-600 text-[8px] font-bold px-1.5 py-0.5 rounded uppercase">Aktif</div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-6">
                                <p class="text-xs text-slate-400">Belum ada data kendaraan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: ID CARD -->
            <div id="tab-idcard" class="hidden space-y-6">
                <div class="flex flex-col items-center gap-6">
                    <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden shadow-xl border border-slate-800 flex flex-col justify-between" 
                         style="width: 85mm; height: 55mm; min-width: 85mm; min-height: 55mm;">
                        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div>
                                <p class="text-[8px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-1">Resident Digital ID</p>
                                <h4 class="text-sm font-bold tracking-tight leading-none">The Tamar Village</h4>
                            </div>
                            <div class="bg-emerald-500/20 text-emerald-400 text-[7px] font-bold px-1.5 py-0.5 rounded border border-emerald-500/30 uppercase">Active</div>
                        </div>
                        <div class="flex flex-col gap-4 relative z-10">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->name ?? Auth::user()->name) }}&background=10b981&color=fff" class="w-12 h-12 rounded-lg border border-white/10 shadow-lg">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold truncate leading-tight">{{ $resident->name ?? Auth::user()->name }}</h3>
                                    <p class="text-[9px] text-emerald-400 font-mono mt-0.5">Unit: 
                                        @if($resident)
                                            Blok {{ $resident->block->name }} / {{ $resident->unit_no }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-white p-1.5 rounded-lg shadow-lg shrink-0">
                                    <i class="fa-solid fa-qrcode text-slate-900 text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/5 relative z-10 flex justify-between items-end">
                            <p class="text-[7px] text-slate-500 uppercase font-medium">Valid until Dec 2026</p>
                            <button onclick="window.print()" class="text-emerald-400 text-[8px] font-bold hover:underline uppercase tracking-widest">
                                Download / Print
                            </button>
                        </div>
                    </div>
                    <div class="max-w-md text-center">
                        <p class="text-xs text-slate-500 leading-relaxed">Gunakan Digital ID ini untuk akses pintu gerbang otomatis dan verifikasi identitas di lingkungan perumahan.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kolom Sidebar (Kanan) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Butuh Bantuan / Banner -->
            <div class="bg-emerald-900 text-white p-6 rounded-2xl shadow-xl space-y-4">
                <div>
                    <p class="text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-2">Butuh Bantuan?</p>
                    <p class="text-[11px] leading-relaxed text-emerald-100">Jika ada kesalahan data diri atau nomor kendaraan, silakan hubungi pengurus RT melalui menu Lapor.</p>
                </div>
                <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ $resident ? $resident->block->name . ' / ' . $resident->unit_no : '-' }}', 'ASSISTANCE')" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-2.5 rounded-xl font-black text-xs transition-colors shadow-lg shadow-emerald-500/30 uppercase tracking-widest">
                    Hubungi Pengurus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: IURAN ================= -->
<div id="view-iuran" class="hidden space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tagihan & Iuran</h1>
            <p class="text-sm text-slate-500 mt-1">Seluruh pembayaran Iuran IPL wajib dilakukan melalui Virtual Account BNI 46.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Bulan Tagihan</th>
                            <th class="py-3 px-4">Jumlah</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @if($invoices->count() > 0)
                            @foreach($invoices as $invoice)
                            <tr>
                                <td class="py-4 px-4 font-medium">{{ Carbon\Carbon::parse($invoice->period . '-01')->translatedFormat('F Y') }}</td>
                                <td class="py-4 px-4 text-slate-700">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-4">
                                    @if($invoice->status == 'paid')
                                        <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Lunas</span>
                                    @elseif($invoice->status == 'unpaid')
                                        <span class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Belum Bayar</span>
                                    @else
                                        <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Pending</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @if($invoice->status == 'unpaid')
                                        <button onclick="switchWargaView('virtual-account')" class="text-emerald-600 font-bold hover:underline">Bayar via VA</button>
                                    @else
                                        <button class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-download"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada riwayat tagihan.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="bg-emerald-900 text-white p-6 rounded-2xl shadow-xl">
                <p class="text-emerald-400 text-[10px] font-bold uppercase tracking-widest mb-4">Metode Pembayaran Tunggal</p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded bg-white/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-emerald-300 uppercase">Transfer Virtual Account (BNI)</p>
                            <p class="font-mono text-sm font-bold">8823 0081 1223 3445</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-white/10">
                    <p class="text-[9px] text-emerald-400 leading-relaxed italic">Sistem hanya melayani pembayaran via Transfer VA. Pembayaran akan terverifikasi otomatis dalam 1 menit tanpa perlu konfirmasi manual.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: VIRTUAL ACCOUNT ================= -->
<div id="view-virtual-account" class="hidden space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Virtual Account Bendahara</h1>
        <p class="text-sm text-slate-500 mt-1">Gunakan nomor Virtual Account resmi milik Bendahara untuk seluruh pembayaran.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- BNI 46 VA Card -->
        <div class="bg-gradient-to-br from-emerald-800 to-emerald-600 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-start mb-10">
                <div>
                    <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-widest mb-1">Bendahara Perumahan</p>
                    <h3 class="text-xl font-bold">Virtual Account Terpadu</h3>
                </div>
                <div class="bg-white px-2 py-1 rounded text-[10px] text-emerald-700 font-black">BNI 46</div>
            </div>
            <div class="space-y-4 relative z-10">
                <div>
                    <p class="text-[9px] text-emerald-100 uppercase mb-1">Nomor VA Pembayaran (Iuran & Tabungan)</p>
                    <div class="flex items-center justify-between">
                        <p class="text-2xl font-mono font-bold tracking-wider">8823 0081 1223 3445</p>
                        <button onclick="alert('Nomor VA disalin!')" class="text-emerald-200 hover:text-white transition"><i class="fa-regular fa-copy text-xl"></i></button>
                    </div>
                </div>
                <div class="pt-4 border-t border-white/10">
                    <p class="text-[9px] text-emerald-100 uppercase mb-1">Nama Rekening Tujuan</p>
                    <p class="text-sm font-semibold uppercase">BENDAHARA THE TAMAR VILLAGE</p>
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h4 class="font-bold text-slate-800 mb-2">Panduan Pembayaran</h4>
                <ul class="text-xs text-slate-500 space-y-3">
                    <li class="flex gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Masukkan nomor VA di menu "Transfer Virtual Account" pada m-Banking Anda.</li>
                    <li class="flex gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Nominal akan muncul otomatis (sesuai tagihan aktif).</li>
                    <li class="flex gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Verifikasi pembayaran akan dilakukan secara otomatis oleh sistem.</li>
                </ul>
            </div>
            <div class="mt-6 flex gap-3">
                <button class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 py-2 rounded-lg text-xs font-bold transition">Lihat Riwayat</button>
                <button class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg text-xs font-bold transition shadow-lg shadow-emerald-500/30">Download PDF</button>
            </div>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl flex items-start gap-4">
        <i class="fa-solid fa-lightbulb text-amber-500 text-xl mt-1"></i>
        <div>
            <h5 class="text-sm font-bold text-amber-800">VA Untuk Tabungan?</h5>
            <p class="text-xs text-amber-700 leading-relaxed">Anda juga dapat menggunakan nomor VA di atas untuk setoran tabungan. Sistem akan mengidentifikasi setoran berdasarkan nominal yang Anda masukkan setelah tagihan IPL terlunasi.</p>
        </div>
    </div>
</div>

<!-- ================= VIEW: TABUNGAN ================= -->
<div id="view-tabungan" class="hidden space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Tabungan Warga</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau saldo dan setoran tabungan Anda via Virtual Account Bendahara.</p>
        </div>
        <div class="bg-amber-500 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-md flex items-center gap-2">
            <i class="fa-solid fa-coins"></i> VA Aktif: 8823 0081 1223 3445
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Saldo Summary -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-emerald-500 to-teal-700 p-6 rounded-2xl text-white shadow-lg shadow-emerald-500/20">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Total Saldo Tabungan</p>
                <h2 class="text-3xl font-bold mt-2">Rp {{ number_format($savings_balance, 0, ',', '.') }}</h2>
                <div class="mt-8 pt-6 border-t border-white/10 flex justify-between items-center">
                    <div class="text-xs">
                        <p class="text-emerald-200">Terakhir Setor</p>
                        <p class="font-bold">{{ $savings_transactions->where('status', 'success')->first() ? $savings_transactions->where('status', 'success')->first()->transaction_date->translatedFormat('d M Y') : '-' }}</p>
                    </div>
                    <button onclick="openAddSetoranModal()" class="bg-white/20 hover:bg-white/30 p-2 rounded-lg transition-colors">
                        <i class="fa-solid fa-plus-circle text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Panduan Setoran</h3>
                <div class="space-y-3">
                    <p class="text-[11px] text-slate-500 leading-relaxed">Seluruh setoran tabungan kini wajib melalui <b>Virtual Account Bendahara</b> untuk pendataan otomatis.</p>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <p class="text-[9px] text-slate-400 uppercase font-bold">No. VA Bendahara</p>
                        <p class="text-sm font-mono font-bold text-emerald-600">8823 0081 1223 3445</p>
                    </div>
                    <p class="text-[10px] text-amber-600 font-medium italic">* Gunakan nominal unik atau sertakan kode program saat transfer jika diperlukan.</p>
                </div>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat Setoran</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @if($savings_transactions->count() > 0)
                        @foreach($savings_transactions as $transaction)
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full {{ $transaction->type == 'deposit' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center">
                                    <i class="fa-solid {{ $transaction->type == 'deposit' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $transaction->program ? $transaction->program->name : 'Setoran Tabungan' }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $transaction->transaction_date->translatedFormat('d M Y') }} • {{ strtoupper($transaction->method) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $transaction->type == 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $transaction->type == 'deposit' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-[9px] font-bold {{ $transaction->status == 'success' ? 'text-emerald-500' : ($transaction->status == 'pending' ? 'text-amber-500' : 'text-rose-500') }} uppercase">
                                    {{ $transaction->status == 'success' ? 'Sukses' : ($transaction->status == 'pending' ? 'Pending' : 'Gagal') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat transaksi tabungan.</div>
                    @endif
                </div>
                <div class="p-4 bg-slate-50 text-center">
                    <button class="text-xs font-bold text-sky-500 hover:underline">Lihat Semua Riwayat</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: TAMU ================= -->
<div id="view-tamu" class="hidden space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pendaftaran Tamu & Kurir</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan tamu agar mendapatkan akses cepat di gerbang utama.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Pendaftaran (Kiri) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden sticky top-0">
                <div class="p-4 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Form Registrasi Tamu</h3>
                </div>
                <div class="p-5">
                    <form id="form-tamu" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nama Tamu / Kurir</label>
                            <input type="text" placeholder="Contoh: Shopee Express / Andi" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tipe Kedatangan</label>
                            <select class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                                <option value="guest">Tamu Personal</option>
                                <option value="courier">Kurir / Paket</option>
                                <option value="taxi">Ojek / Taksi Online</option>
                                <option value="service">Teknisi / Service</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nomor Polisi (Opsional)</label>
                            <input type="text" placeholder="Contoh: B 1234 ABC" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tanggal Kedatangan</label>
                            <input type="date" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <button type="button" onclick="alert('Pendaftaran tamu berhasil!')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl font-bold text-xs transition-all shadow-lg shadow-emerald-500/20 mt-2 uppercase tracking-widest">
                            Daftarkan Tamu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Tamu (Kanan) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat & Status Tamu</h3>
                    <div class="flex gap-2">
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span> Menunggu
                        </span>
                        <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Di Dalam
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Nama / Tipe</th>
                                <th class="py-3 px-4">Info Kendaraan</th>
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 italic">Belum ada riwayat kunjungan tamu.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: LAPORAN ================= -->
<div id="view-laporan" class="hidden space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Lapor & Darurat</h1>
            <p class="text-sm text-slate-500 mt-1">Sampaikan keluhan fasilitas atau gunakan tombol darurat untuk bantuan segera.</p>
        </div>
    </div>

    <!-- Emergency Section -->
    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/30">
                <i class="fa-solid fa-triangle-exclamation text-xl animate-pulse"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800 leading-tight">Pusat Bantuan Darurat</h3>
                <p class="text-xs text-slate-500">Gunakan tombol di bawah hanya untuk situasi mendesak.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ $resident ? $resident->block->name . ' / ' . $resident->unit_no : '-' }}', 'EMERGENCY')" class="flex items-center gap-4 p-4 bg-white hover:bg-rose-50 border border-slate-100 hover:border-rose-200 rounded-xl transition-all group">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tombol Panik</p>
                    <p class="text-sm font-bold text-slate-700">Bahaya Kritis</p>
                </div>
            </button>
            
            <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ $resident ? $resident->block->name . ' / ' . $resident->unit_no : '-' }}', 'ASSISTANCE')" class="flex items-center gap-4 p-4 bg-white hover:bg-amber-50 border border-slate-100 hover:border-amber-200 rounded-xl transition-all group">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bantuan Medis</p>
                    <p class="text-sm font-bold text-slate-700">Sakit / Cedera</p>
                </div>
            </button>

            <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ $resident ? $resident->block->name . ' / ' . $resident->unit_no : '-' }}', 'SUSPICIOUS')" class="flex items-center gap-4 p-4 bg-white hover:bg-indigo-50 border border-slate-100 hover:border-indigo-200 rounded-xl transition-all group">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-mask"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keamanan</p>
                    <p class="text-sm font-bold text-slate-700">Mencurigakan</p>
                </div>
            </button>

            <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ $resident ? $resident->block->name . ' / ' . $resident->unit_no : '-' }}', 'TECHNICAL')" class="flex items-center gap-4 p-4 bg-white hover:bg-sky-50 border border-slate-100 hover:border-sky-200 rounded-xl transition-all group">
                <div class="w-10 h-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-faucet-drip"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Teknis</p>
                    <p class="text-sm font-bold text-slate-700">Kebocoran / Listrik</p>
                </div>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Laporan (Kiri) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-sm">Buat Laporan Baru</h3>
                </div>
                <div class="p-5">
                    <form id="form-laporan" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kategori Laporan</label>
                            <select class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                                <option value="facility">Fasilitas Umum</option>
                                <option value="security">Keamanan / Ketertiban</option>
                                <option value="cleanliness">Kebersihan / Sampah</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Detail Laporan</label>
                            <textarea rows="4" placeholder="Jelaskan masalah secara detail..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all resize-none"></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Foto Bukti (Opsional)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:bg-slate-50 transition-colors cursor-pointer group">
                                <i class="fa-solid fa-cloud-arrow-up text-slate-300 group-hover:text-emerald-500 text-2xl transition-colors mb-2"></i>
                                <p class="text-[10px] text-slate-400 font-medium">Klik atau drop foto di sini</p>
                            </div>
                        </div>
                        <button type="button" onclick="alert('Laporan Anda telah terkirim!')" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded-xl font-bold text-xs transition-all shadow-lg mt-2 uppercase tracking-widest">
                            Kirim Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Riwayat Laporan (Kanan) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm">Status & Riwayat Laporan</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <div class="p-8 text-center text-slate-400 italic">Belum ada riwayat laporan.</div>
                </div>
                <div class="p-4 bg-slate-50 text-center">
                    <button class="text-xs font-bold text-sky-500 hover:underline">Muat Lebih Banyak</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: KEAMANAN ================= -->
<div id="view-keamanan" class="hidden space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Keamanan Akun</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola kata sandi dan pengaturan keamanan akses portal Anda.</p>
    </div>
    
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm max-w-lg">
        <form class="space-y-4">
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kata Sandi Lama</label>
                <input type="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kata Sandi Baru</label>
                <input type="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                <input type="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
            </div>
            <button type="button" onclick="alert('Kata sandi berhasil diubah!')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl font-bold text-xs transition-all shadow-lg mt-2 uppercase tracking-widest">
                Ubah Kata Sandi
            </button>
        </form>
    </div>
</div> 
@endsection

@section('modals')
<!-- ================= MODAL: EDIT PROFIL ================= -->
<div id="modal-edit-profil" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Edit Profil Saya</h2>
            <button onclick="closeEditProfileModal()" class="text-slate-400 hover:text-rose-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form id="form-edit-profil" action="{{ route('warga.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $resident->name ?? Auth::user()->name }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">NIK (16 Digit)</label>
                        <input type="text" name="nik" value="{{ $resident->nik ?? '' }}" maxlength="16" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">WhatsApp</label>
                        <input type="text" name="contact" value="{{ $resident->contact ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Email Aktif</label>
                        <input type="email" name="email" value="{{ $resident->email ?? Auth::user()->email ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ $resident->birth_place ?? '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $resident->birth_date ? \Carbon\Carbon::parse($resident->birth_date)->format('Y-m-d') : '' }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">ID Telegram</label>
                        <input type="text" name="telegram_id" value="{{ $resident->telegram_id ?? '' }}" placeholder="@username" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status Hunian</label>
                        <select name="housing_status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                            <option value="Owner" {{ ($resident->housing_status ?? '') == 'Owner' ? 'selected' : '' }}>Pemilik (Owner)</option>
                            <option value="Tenant" {{ ($resident->housing_status ?? '') == 'Tenant' ? 'selected' : '' }}>Penyewa (Tenant)</option>
                        </select>
                    </div>
                </div>
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 flex gap-3 mt-4">
                    <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
                    <p class="text-[11px] text-amber-800 leading-relaxed">Penting: Data NIK dan Status Hunian akan diverifikasi ulang oleh Admin setelah diperbarui.</p>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
            <button onclick="closeEditProfileModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
            <button type="submit" form="form-edit-profil" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium shadow-md hover:bg-emerald-700 transition flex items-center gap-2">Simpan Perubahan</button>
        </div>
    </div>
</div>

<!-- ================= MODAL: TAMBAH SETORAN ================= -->
<div id="modal-tambah-setoran" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800 nav-text">Tambah Setoran Tabungan</h2>
            <button onclick="closeAddSetoranModal()" class="text-slate-400 hover:text-rose-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form class="space-y-4">
                <div class="bg-emerald-50 border border-emerald-100 p-3 rounded-lg flex items-center justify-between">
                    <div>
                        <p class="text-[9px] text-emerald-600 font-bold uppercase">VA Bendahara (BNI)</p>
                        <p class="text-sm font-mono font-bold text-slate-700">8823 0081 1223 3445</p>
                    </div>
                    <button type="button" onclick="alert('Nomor VA disalin!')" class="text-emerald-600 hover:text-emerald-700"><i class="fa-regular fa-copy"></i></button>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
            <button onclick="closeAddSetoranModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
            <button onclick="alert('Setoran berhasil!'); closeAddSetoranModal();" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium shadow-sm hover:bg-emerald-700 transition flex items-center gap-2"><i class="fa-solid fa-paper-plane"></i> Kirim Setoran</button>
        </div>
    </div>
</div>

<!-- ================= MODAL: LIHAT FOTO KENDARAAN ================= -->
<div id="modal-foto-kendaraan" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 id="modal-foto-plat" class="font-bold text-slate-800 text-base uppercase">Plat Nomor</h3>
                <p id="modal-foto-desc" class="text-[10px] text-slate-500 font-medium">Info Kendaraan</p>
            </div>
            <button onclick="closeVehiclePhoto()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-400 hover:text-rose-500 shadow-sm border border-slate-100 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-4 sm:p-6 bg-white">
            <img id="modal-foto-img" src="https://ui-avatars.com/api/?name=V&background=f1f5f9&color=f1f5f9" class="w-full h-auto rounded-xl border border-slate-100 shadow-sm object-contain max-h-[60vh] bg-slate-50" alt="Pratinjau Kendaraan">
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button onclick="closeVehiclePhoto()" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold text-xs hover:bg-emerald-700 transition-all shadow-md shadow-emerald-600/20 uppercase tracking-widest">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openVehiclePhoto(plat, desc, imgUrl) {
        document.getElementById('modal-foto-plat').innerText = plat;
        document.getElementById('modal-foto-desc').innerText = desc;
        document.getElementById('modal-foto-img').src = imgUrl;
        document.getElementById('modal-foto-kendaraan').classList.remove('hidden');
    }

    function closeVehiclePhoto() {
        document.getElementById('modal-foto-kendaraan').classList.add('hidden');
    }

    // Close modal on click outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modal-foto-kendaraan');
        if (e.target === modal) closeVehiclePhoto();
    });

    function openEditProfileModal() {
        document.getElementById('modal-edit-profil').classList.remove('hidden');
    }

    function closeEditProfileModal() {
        document.getElementById('modal-edit-profil').classList.add('hidden');
    }

    function openAddSetoranModal() {
        document.getElementById('modal-tambah-setoran').classList.remove('hidden');
    }

    function closeAddSetoranModal() {
        document.getElementById('modal-tambah-setoran').classList.add('hidden');
    }
</script>
@endpush
