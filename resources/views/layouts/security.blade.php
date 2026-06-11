<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Keamanan - T-Link Smart Security')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @stack('styles')
</head>
<body class="bg-slate-900 font-sans text-slate-300 antialiased overflow-hidden flex h-screen">

    <!-- Sidebar Keamanan -->
    <aside id="security-sidebar" class="w-64 bg-slate-800 border-r border-slate-700/50 flex flex-col shrink-0 transition-all duration-300">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-slate-700/50 bg-slate-900/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    S
                </div>
                <span class="text-white font-bold text-xl tracking-wide logo-text">T-Link <span class="text-xs font-normal text-rose-400 align-top">Security</span></span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-4 sidebar-label">Security Ops</p>
            
            <a href="#" onclick="switchSecurityView('dashboard')" id="nav-dashboard" class="flex items-center gap-3 px-3 py-2.5 bg-rose-600/10 text-rose-500 rounded-lg border-r-2 border-rose-600 transition-colors group">
                <i class="fa-solid fa-shield-halved w-5 text-center"></i>
                <span class="font-medium nav-text">Monitor Darurat</span>
            </a>

            <a href="#" onclick="switchSecurityView('history')" id="nav-history" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-700 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="font-medium nav-text">Histori Kejadian</span>
            </a>

            <a href="#" onclick="switchSecurityView('patrol')" id="nav-patrol" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-700 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-route w-5 text-center"></i>
                <span class="font-medium nav-text">Jadwal Patroli</span>
            </a>
        </div>

        <!-- Connection Status -->
        <div class="p-4 bg-slate-900/50 border-t border-slate-700/50">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Server Connected</span>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 relative">
        <!-- Topbar -->
        <header class="h-16 bg-slate-800 border-b border-slate-700/50 flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="text-slate-400 p-2 hover:bg-slate-700 rounded-lg transition-colors">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <h2 id="view-title" class="text-lg font-bold text-white uppercase tracking-tight">@yield('view_title', 'Security Command Center')</h2>
            </div>

            <div class="flex items-center gap-6">
                <!-- Digital Clock -->
                <div class="text-right hidden sm:block">
                    <p id="real-time-clock" class="text-xl font-black text-white font-mono leading-none">12:45:00</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>

                <div class="h-8 w-px bg-slate-700"></div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 p-1 rounded-xl hover:bg-slate-700 transition-all border border-transparent">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e11d48&color=fff" alt="User" class="w-9 h-9 rounded-lg border border-slate-600 shadow-lg">
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-white leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-rose-400 mt-1 uppercase font-bold tracking-tighter">On-Duty</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-500"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-52 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl z-50 overflow-hidden py-1">
                        <div class="p-4 border-b border-slate-700 bg-slate-900/50">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">ID Petugas</p>
                            <p class="text-xs font-bold text-white mt-1">SEC-2026-{{ str_pad(Auth::user()->id, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="p-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors font-bold text-left">
                                    <i class="fa-solid fa-power-off w-4 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8" id="security-content-area">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="h-10 bg-slate-800 border-t border-slate-700/50 flex items-center justify-center text-[10px] text-slate-500 font-bold tracking-widest uppercase shrink-0">
            T-Link Smart Security Console v2.0 - AES 256 Encrypted
        </footer>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/security.js') }}"></script>
    @stack('scripts')
</body>
</html>
