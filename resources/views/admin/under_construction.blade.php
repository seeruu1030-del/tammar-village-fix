@extends('layouts.admin')

@section('title', 'Fitur Sedang Dikembangkan | T-Link Admin')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4">
    <div class="relative mb-8">
        <div class="w-32 h-32 bg-slate-100 rounded-full flex items-center justify-center animate-pulse">
            <i class="fa-solid fa-person-digging text-5xl text-slate-400"></i>
        </div>
        <div class="absolute -right-2 -top-2 w-12 h-12 bg-white rounded-2xl shadow-lg border border-slate-100 flex items-center justify-center text-amber-500 animate-bounce">
            <i class="fa-solid fa-hammer text-xl"></i>
        </div>
    </div>
    
    <h1 class="text-3xl font-black text-slate-800 mb-2">Under Construction</h1>
    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">
        Mohon maaf, fitur ini <span class="font-bold text-slate-700">sedang dalam proses pengembangan</span>. Kami sedang bekerja keras untuk segera menghadirkannya bagi Anda.
    </p>
    
    <div class="mt-8 flex flex-col sm:flex-row gap-3">
        <div class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold shadow-lg shadow-slate-900/20 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left"></i> Segera Hadir
        </div>
        <a href="{{ url('/admin') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition flex items-center gap-2">
             Kembali ke Dashboard
        </a>
    </div>

    <div class="mt-12 opacity-20 select-none pointer-events-none">
        <div class="flex gap-4 items-center">
            <div class="h-1 w-24 bg-slate-200 rounded-full"></div>
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">T-Link Village Pro</p>
            <div class="h-1 w-24 bg-slate-200 rounded-full"></div>
        </div>
    </div>
</div>
@endsection
