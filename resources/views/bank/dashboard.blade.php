@extends('layouts.bank')

@section('title', 'BNI 46 - Portal Mitra T-Link')

@section('content')
<!-- ================= VIEW: DASHBOARD ================= -->
<div id="view-dashboard" class="space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Overview Partner</h1>
        <p class="text-sm text-slate-500 mt-1">Status dan aktivitas Virtual Account BNI 46 untuk perumahan The Tamar Village.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase">Total VA Terbit</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $total_va }}</h3>
            <p class="text-[10px] text-emerald-500 mt-2 font-bold flex items-center gap-1"><i class="fa-solid fa-caret-up"></i> 100% Request Terpenuhi</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase">Transaksi Hari Ini</p>
            <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $today_transactions }}</h3>
            <p class="text-[10px] text-slate-500 mt-2 font-bold flex items-center gap-1">Rp {{ number_format($today_volume, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase">Uptime Sinkronisasi</p>
            <h3 class="text-3xl font-bold text-emerald-600 mt-2">99.9%</h3>
            <p class="text-[10px] text-emerald-500 mt-2 font-bold flex items-center gap-1">Normal Operation</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-6 rounded-2xl shadow-lg text-white">
            <p class="text-[10px] font-bold text-emerald-100 uppercase tracking-widest">Saldo Bendahara</p>
            <h3 class="text-xl font-bold mt-2">Rp 1.450.250K</h3>
            <p class="text-[9px] text-emerald-200 mt-2 font-medium">Acc: 008112233445</p>
        </div>
    </div>

    <!-- Transaction Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-slate-800">Volume Transaksi Virtual Account</h3>
                <p class="text-xs text-slate-400">Statistik transaksi harian dalam 7 hari terakhir</p>
            </div>
            <select class="bg-slate-50 border border-slate-200 text-xs font-bold text-slate-600 rounded-lg px-3 py-1.5 outline-none focus:border-orange-500">
                <option>7 Hari Terakhir</option>
                <option>30 Hari Terakhir</option>
            </select>
        </div>
        <div class="h-80 w-full">
            <canvas id="transactionChart"></canvas>
        </div>
    </div>
</div>

<!-- ================= VIEW: MANAJEMEN VA ================= -->
<div id="view-manajemen-va" class="hidden space-y-6">
    <div class="flex justify-between items-end mb-2">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Nomor Virtual Account</h1>
            <p class="text-sm text-slate-500 mt-1">Proses permintaan pembuatan nomor VA baru dari partner T-Link.</p>
        </div>
        <button onclick="simulateMassGenerate()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg shadow-orange-600/30 transition-all flex items-center gap-2">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Massal
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100">
                        <th class="py-4 px-6">ID Warga / Unit</th>
                        <th class="py-4 px-6">Nama Warga</th>
                        <th class="py-4 px-6">Nomor VA (Generated)</th>
                        <th class="py-4 px-6">Status VA</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($residents as $res)
                    <tr>
                        <td class="py-4 px-6 font-bold text-slate-600">TAMAR-{{ str_pad($res->id, 3, '0', STR_PAD_LEFT) }} / {{ $res->block->name }}{{ $res->unit_no }}</td>
                        <td class="py-4 px-6">{{ $res->name }}</td>
                        <td class="py-4 px-6 font-mono font-bold">
                            @if($res->nik)
                                8823 0081 {{ substr($res->nik, -8, 4) }} {{ substr($res->nik, -4) }}
                            @else
                                <span class="text-slate-300 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($res->nik)
                                <span class="bg-emerald-100 text-emerald-600 text-[10px] font-black px-2 py-1 rounded-md uppercase tracking-widest">Active</span>
                            @else
                                <span class="bg-amber-100 text-amber-600 text-[10px] font-black px-2 py-1 rounded-md uppercase tracking-widest">Pending Req</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($res->nik)
                                <button class="text-orange-600 hover:underline font-bold">Details</button>
                            @else
                                <button onclick="simulateSingleGenerate(this)" class="bg-slate-800 hover:bg-black text-white px-3 py-1 rounded text-[10px] font-bold uppercase transition-all">Generate Now</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= VIEW: SINKRONISASI ================= -->
<div id="view-sinkronisasi" class="hidden space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Sinkronisasi & API Logs</h1>
        <p class="text-sm text-slate-500 mt-1">Pemantauan transmisi data antara BNI Host-to-Host (H2H) dengan aplikasi T-Link.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-slate-900 rounded-xl p-6 font-mono text-xs text-emerald-400 h-[500px] overflow-y-auto shadow-2xl" id="api-log-container">
                <p class="text-slate-500 mb-2">[SYSTEM] BNI Host-to-Host Terminal Started...</p>
                <p>[OK] Handshake with T-Link Server (https://api.t-link.id) success.</p>
                <p>[LOG] {{ now()->format('Y-m-d H:i:s') }} - INBOUND - Request VA Sync (PRT-TAMAR)</p>
                <p>[LOG] {{ now()->format('Y-m-d H:i:s') }} - OUTBOUND - Push {{ $total_va }} Active VAs</p>
                <div id="dynamic-logs"></div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h4 class="font-bold text-slate-800 text-sm mb-4">Configuration Status</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Callback URL (Webhook)</p>
                        <div class="bg-slate-50 p-2 rounded text-xs font-mono text-slate-600 break-all">https://api.t-link.id/v1/bni/callback</div>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Shared Secret Key</p>
                        <div class="bg-slate-50 p-2 rounded text-xs font-mono text-slate-600">******************************</div>
                    </div>
                    <button onclick="simulateSync()" class="w-full bg-orange-600 text-white py-2 rounded-lg text-xs font-bold shadow-md hover:bg-orange-700 transition">Test Connection</button>
                </div>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl">
                <h5 class="text-xs font-bold text-emerald-800 mb-1">Informasi Sinkronisasi</h5>
                <p class="text-[11px] text-emerald-700 leading-relaxed">Data Virtual Account disinkronisasi setiap kali ada penambahan warga baru atau perubahan status iuran di sisi T-Link.</p>
            </div>
        </div>
    </div>
</div>

<!-- ================= VIEW: PROFIL SAYA ================= -->
<div id="view-profil" class="hidden space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Profil Administrator Bank</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data diri dan informasi akses Anda sebagai pengelola sistem bank.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E55300&color=fff&size=128" class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-orange-50">
                <h3 class="font-bold text-slate-800">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-orange-600 font-bold uppercase tracking-widest mt-1">H2H Technical Officer</p>
                <div class="mt-6 pt-6 border-t border-slate-50 text-left space-y-3">
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <i class="fa-solid fa-building w-5 text-center text-slate-400"></i>
                        <span>Divisi IT - Head Office</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <i class="fa-solid fa-location-dot w-5 text-center text-slate-400"></i>
                        <span>Jakarta, Indonesia</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fa-solid fa-user-gear text-orange-500"></i> Detail Informasi Akun</h3>
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Petugas</label>
                            <input type="text" value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-orange-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">ID Pegawai</label>
                            <input type="text" value="EMP-BNI-{{ Auth::user()->id }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Email Corporate</label>
                            <input type="email" value="{{ Auth::user()->email ?? Auth::user()->username . '@bni.co.id' }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-orange-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Level Akses</label>
                            <input type="text" value="Partner Manager" readonly class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-500 outline-none">
                        </div>
                    </div>
                    <button type="button" onclick="alert('Profil bank administrator berhasil diperbarui!')" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md transition-all">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div> <!-- END VIEW: PROFIL -->

<!-- ================= VIEW: KEAMANAN ================= -->
<div id="view-keamanan" class="hidden space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Keamanan & Kredensial</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola kata sandi dan proteksi akses ke portal mitra bank.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fa-solid fa-key text-orange-500"></i> Ganti Password Akun</h3>
            <form class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Password Lama</label>
                    <input type="password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-orange-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Password Baru</label>
                    <input type="password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-orange-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-orange-500 outline-none">
                </div>
                <button type="button" onclick="alert('Password administrator bank berhasil diubah!')" class="w-full bg-slate-800 hover:bg-black text-white py-2.5 rounded-lg text-sm font-bold shadow-md transition-all">Update Password</button>
            </form>
        </div>

        <div class="space-y-6">
            <div class="bg-orange-50 border border-orange-100 p-6 rounded-2xl">
                <h3 class="font-bold text-orange-800 mb-2 flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> Kebijakan Keamanan</h3>
                <p class="text-xs text-orange-700 leading-relaxed">Sesuai standar ISO 27001, password wajib diubah setiap 90 hari dan harus mengandung simbol khusus demi keamanan integrasi Host-to-Host.</p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 text-sm">Security Logs</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-[11px] border-b border-slate-50 pb-2">
                        <span class="text-slate-500">Login Terakhir</span>
                        <span class="font-bold text-slate-700">Hari ini, {{ now()->format('H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] border-b border-slate-50 pb-2">
                        <span class="text-slate-500">IP Address</span>
                        <span class="font-bold text-slate-700">182.16.2. * *</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- END VIEW: KEAMANAN -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function initTransactionChart() {
        const ctx = document.getElementById('transactionChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Volume (Juta Rp)',
                    data: [12, 19, 3, 5, 2, 3, 15],
                    borderColor: '#E55300',
                    backgroundColor: 'rgba(229, 83, 0, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initTransactionChart);
</script>
@endpush
