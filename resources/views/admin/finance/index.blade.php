@extends('layouts.admin')

@section('title', 'Dashboard Keuangan | T-Link Admin')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4">
    <div class="relative mb-8">
        <div class="w-32 h-32 bg-emerald-100 rounded-full flex items-center justify-center animate-pulse">
            <i class="fa-solid fa-wallet text-5xl text-emerald-600"></i>
        </div>
        <div class="absolute -right-2 -top-2 w-12 h-12 bg-white rounded-2xl shadow-lg border border-slate-100 flex items-center justify-center text-amber-500 animate-bounce">
            <i class="fa-solid fa-screwdriver-wrench text-xl"></i>
        </div>
    </div>
    
    <h1 class="text-3xl font-black text-slate-800 mb-2">Fitur Segera Hadir!</h1>
    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">
        Halaman <span class="font-bold text-emerald-600">Dashboard Keuangan</span> sedang dalam proses pengembangan intensif untuk memberikan pengalaman manajemen finansial yang lebih baik.
    </p>
    
    <div class="mt-8 flex flex-col sm:flex-row gap-3">
        <div class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold shadow-lg shadow-slate-900/20 flex items-center gap-2">
            <i class="fa-solid fa-code"></i> In Progress
        </div>
        <a href="{{ url('/admin') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition flex items-center gap-2">
             Kembali ke Dashboard
        </a>
    </div>

    <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 w-full max-w-2xl opacity-40 grayscale">
        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-slate-100 mb-3 mx-auto"></div>
            <div class="h-2 w-16 bg-slate-100 rounded-full mx-auto mb-1"></div>
            <div class="h-2 w-10 bg-slate-50 rounded-full mx-auto"></div>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-slate-100 mb-3 mx-auto"></div>
            <div class="h-2 w-16 bg-slate-100 rounded-full mx-auto mb-1"></div>
            <div class="h-2 w-10 bg-slate-50 rounded-full mx-auto"></div>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-slate-100 mb-3 mx-auto"></div>
            <div class="h-2 w-16 bg-slate-100 rounded-full mx-auto mb-1"></div>
            <div class="h-2 w-10 bg-slate-50 rounded-full mx-auto"></div>
        </div>
    </div>
</div>
@endsection
