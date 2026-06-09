@extends('layouts.admin')

@section('title', 'Dashboard | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Ringkasan Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">Pantau aktivitas The Tamar Village hari ini, {{ date('d F Y') }}.</p>
    </div>
    <div class="flex gap-2">
        <button class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-plus"></i> Tambah Pengumuman
        </button>
        <button onclick="alert('Mengunduh laporan Dashboard...')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
            <i class="fa-solid fa-download"></i> Unduh Laporan
        </button>
    </div>
</div> 

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Warga Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">248</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-sky-500 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-house-user text-lg"></i>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-emerald-500 font-medium flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up text-xs"></i> +4</span>
            <span class="text-slate-400 ml-2">Bulan ini</span>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Tamu Terjadwal</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">12</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-qrcode text-lg"></i>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-slate-500">8 Masuk, 4 Menunggu</span>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Kas Perumahan</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp 45.2M</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-wallet text-lg"></i>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-emerald-500 font-medium flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up text-xs"></i> 92%</span>
            <span class="text-slate-400 ml-2">Warga lunas IPL</span>
        </div>
    </div>
    <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-slate-500">Keluhan Terbuka</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">5</h3>
            </div>
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-clipboard-list text-lg"></i>
            </div>
        </div>
        <div class="flex items-center text-sm">
            <span class="text-rose-500 font-medium">2 Menunggu Teknisi</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 lg:col-span-2">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800">Arus Kas & Tabungan (6 Bulan Terakhir)</h3>
        </div>
        <div id="cashFlowChart" class="w-full h-[300px]"></div>
    </div>

    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-0 overflow-hidden flex flex-col">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Aktivitas Terkini</h3>
            <a href="#" class="text-xs text-sky-500 font-medium hover:underline">Lihat Semua</a>
        </div>
        <div class="p-5 flex-1 overflow-y-auto">
            <div class="space-y-4">
                <!-- Static for now -->
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 mt-0.5"><i class="fa-solid fa-check text-xs"></i></div>
                    <div>
                        <p class="text-sm font-medium text-slate-800">Pembayaran IPL Terverifikasi</p>
                        <p class="text-xs text-slate-500 mt-0.5">Blok C No. 04 (Rp 350.000)</p>
                        <p class="text-[10px] text-slate-400 mt-1">10 menit yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
