@extends('layouts.security')

@section('title', 'Security Command Center - T-Link Smart Security')

@section('content')
<!-- ================= VIEW: MONITOR DARURAT (DASHBOARD) ================= -->
<div id="view-dashboard" class="space-y-8">
    
    <!-- ACTIVE EMERGENCY ALERT (DYNAMIC) -->
    @if($active_emergency)
    <div id="security-emergency-banner" class="bg-rose-600 rounded-2xl p-5 sm:p-8 text-white flex flex-col lg:flex-row items-center justify-between gap-6 emergency-glow">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 w-full lg:w-auto">
            <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-white/20 flex items-center justify-center text-2xl sm:text-4xl animate-bounce shrink-0">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="text-center sm:text-left flex-1">
                <h2 class="text-xl sm:text-3xl font-black uppercase tracking-tighter leading-tight">Peringatan Bahaya!</h2>
                <p id="emergency-desc" class="text-sm sm:text-lg font-medium opacity-90 mt-1 leading-snug">
                    {{ $active_emergency->resident ? 'Blok ' . $active_emergency->resident->block->name . ' / ' . $active_emergency->resident->unit_no : $active_emergency->location }} memicu tombol panik.
                </p>
                <div class="flex flex-wrap gap-2 sm:gap-4 mt-3 justify-center sm:justify-start">
                    <span class="bg-black/20 px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold whitespace-nowrap"><i class="fa-solid fa-clock mr-1"></i> <span id="emergency-time">{{ $active_emergency->created_at->format('H:i') }}</span></span>
                    <span class="bg-black/20 px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold whitespace-nowrap"><i class="fa-solid fa-user mr-1"></i> <span id="emergency-name">{{ $active_emergency->resident->name ?? 'Unknown' }}</span></span>
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto shrink-0">
            <button onclick="alert('Kamera keamanan unit diarahkan ke lokasi...')" class="bg-white/10 hover:bg-white/20 px-6 py-3.5 sm:py-3 rounded-xl font-bold transition w-full lg:w-auto text-sm">LIHAT CCTV</button>
            <button onclick="resolveEmergency({{ $active_emergency->id }})" class="bg-white text-rose-600 px-8 py-4 sm:py-3 rounded-xl font-black shadow-xl hover:bg-slate-100 transition tracking-tighter w-full lg:w-auto text-lg sm:text-base uppercase">TANGANI SEKARANG</button>
        </div>
    </div>
    @else
    <!-- STANDBY STATUS (When no emergency) -->
    <div id="standby-status" class="bg-slate-800 border border-slate-700 p-12 rounded-3xl text-center">
        <div class="w-24 h-24 rounded-full bg-slate-700/50 flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-shield-check text-4xl text-emerald-500"></i>
        </div>
        <h2 class="text-2xl font-bold text-white italic tracking-tight">LINGKUNGAN AMAN & TERKENDALI</h2>
        <p class="text-slate-500 mt-2 max-w-md mx-auto">Tidak ada laporan Panic Button aktif saat ini. Seluruh sensor gerbang dan CCTV berfungsi normal.</p>
    </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Patroli Terakhir</p>
            <h3 class="text-xl font-bold text-white mt-2">Selesai (12:00)</h3>
            <p class="text-xs text-emerald-500 mt-1"><i class="fa-solid fa-check-double mr-1"></i> Area B & D Tercover</p>
        </div>
        <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kejadian Hari Ini</p>
            <h3 class="text-xl font-bold text-white mt-2">{{ $stats['today_incidents'] }} Laporan</h3>
            <p class="text-xs text-slate-400 mt-1">{{ $stats['resolved_today'] }} Terselesaikan</p>
        </div>
        <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Alert Aktif</p>
            <h3 class="text-xl font-bold text-white mt-2">{{ $stats['active_alerts'] }} Alert</h3>
            <p class="text-xs text-rose-500 mt-1 font-bold">Butuh Tindakan Segera</p>
        </div>
    </div>

    <!-- Recent Incidents -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
            <h3 class="font-bold text-white">Laporan Kejadian Terbaru</h3>
            <button class="text-xs font-bold text-rose-400 hover:underline">View All Logs</button>
        </div>
        <div class="divide-y divide-slate-700/50">
            @if($recent_incidents->count() > 0)
                @foreach($recent_incidents as $incident)
                <div class="p-4 flex items-center justify-between hover:bg-slate-700/30 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg {{ $incident->type == 'emergency' ? 'bg-rose-500/10 text-rose-500' : 'bg-sky-500/10 text-sky-500' }} flex items-center justify-center">
                            <i class="fa-solid {{ $incident->type == 'emergency' ? 'fa-triangle-exclamation' : 'fa-info-circle' }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">{{ $incident->title }}</p>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">
                                {{ $incident->resident ? 'Unit ' . $incident->resident->block->name . '/' . $incident->resident->unit_no : $incident->location }} • {{ $incident->created_at->translatedFormat('d M, H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="bg-emerald-500/20 text-emerald-400 text-[9px] font-black px-2 py-1 rounded uppercase tracking-widest border border-emerald-500/30">
                        {{ ucfirst($incident->status) }}
                    </span>
                </div>
                @endforeach
            @else
                <div class="p-8 text-center text-slate-500 italic">Belum ada riwayat kejadian.</div>
            @endif
        </div>
    </div>
</div>

<!-- ================= VIEW: HISTORY ================= -->
<div id="view-history" class="hidden">
    <h1 class="text-2xl font-bold text-white mb-6">Arsip Laporan Keamanan</h1>
    <div class="bg-slate-800 border border-slate-700 rounded-3xl p-12 text-center">
        <i class="fa-solid fa-folder-open text-4xl text-slate-600 mb-4"></i>
        <p class="text-slate-500 font-medium">Modul histori sedang dalam pemeliharaan data...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function resolveEmergency(id) {
        if (confirm('Apakah kejadian ini sudah ditangani dan ingin diselesaikan?')) {
            // Mock call to API
            alert('Laporan diselesaikan. Status diperbarui.');
            location.reload();
        }
    }
</script>
@endpush
