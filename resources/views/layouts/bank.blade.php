<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BNI 46 - Portal Mitra T-Link')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased overflow-hidden flex h-screen">

    <!-- Sidebar Bank (BNI Branding) -->
    <aside id="bank-sidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-white border-r border-slate-200 flex flex-col transition-all duration-300 ease-in-out shadow-sm z-50 shrink-0 transform md:transform-none">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 bg-white">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-orange-500 flex items-center justify-center text-white font-bold text-xs">BNI</div>
                <span class="text-slate-800 font-bold text-lg tracking-wide logo-text">Bank <span class="text-orange-500 font-black">BNI 46</span></span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1" id="bank-sidebar-menu">
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-4 sidebar-label">Corporate Portal</p>
            
            <a href="#" onclick="switchBankView('dashboard')" id="nav-dashboard" class="flex items-center gap-3 px-3 py-2.5 bg-orange-50 text-orange-600 rounded-lg border-r-2 border-orange-600 transition-colors">
                <i class="fa-solid fa-chart-line w-5 text-center"></i>
                <span class="font-medium text-sm nav-text">Dashboard</span>
            </a>

            <a href="#" onclick="switchBankView('manajemen-va')" id="nav-manajemen-va" class="flex items-center gap-3 px-3 py-2.5 text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-lg transition-colors">
                <i class="fa-solid fa-address-card w-5 text-center"></i>
                <span class="font-medium text-sm nav-text">Manajemen VA</span>
            </a>

            <a href="#" onclick="switchBankView('sinkronisasi')" id="nav-sinkronisasi" class="flex items-center gap-3 px-3 py-2.5 text-slate-500 hover:bg-slate-50 hover:text-slate-800 rounded-lg transition-colors">
                <i class="fa-solid fa-cloud-arrow-up w-5 text-center"></i>
                <span class="font-medium text-sm nav-text">Sinkronisasi API</span>
            </a>

            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-8 sidebar-label">Admin Partner</p>
            <div class="px-3 py-4 bg-slate-50 rounded-xl border border-slate-100 sidebar-label">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-slate-600 uppercase">Partner Status</span>
                </div>
                <p class="text-[11px] font-bold text-slate-800 nav-text">The Tamar Village</p>
                <p class="text-[9px] text-slate-500 mt-1 italic nav-text">ID: PRT-2026-TAMAR</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 bg-slate-50 relative overflow-hidden">
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="text-slate-600 p-2 hover:bg-slate-50 rounded-lg transition-colors">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <h2 id="view-title" class="text-lg font-bold text-slate-800">@yield('view_title', 'Dashboard Portal Mitra')</h2>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-full">
                    <i class="fa-solid fa-server text-emerald-500 text-xs"></i>
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Server Online</span>
                </div>
                
                <div class="w-px h-6 bg-slate-200 hidden sm:block"></div>
                
                <!-- Account Dropdown (Top Right) -->
                <div class="relative">
                    <button onclick="toggleBankAdminDropdown()" class="flex items-center gap-3 p-1.5 hover:bg-slate-50 rounded-full transition-colors border border-transparent hover:border-slate-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E55300&color=fff" class="w-8 h-8 rounded-full shadow-sm">
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-slate-400 mt-1 uppercase">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1 transition-transform duration-300" id="bank-admin-arrow"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="bank-admin-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden py-1 z-50">
                        <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 mb-1">
                            <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                        </div>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-rose-500 hover:bg-rose-50 transition-colors font-bold text-left">
                                <i class="fa-solid fa-right-from-bracket w-4"></i>
                                <span>Logout (Keluar)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Content Area -->
        <div class="flex-1 overflow-y-auto p-8" id="bank-content-area">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="h-10 bg-white border-t border-slate-200 flex items-center justify-center text-[10px] text-slate-400 font-medium shrink-0">
            BNI 46 Corporate API Gateway v3.12 - Secure Connection SSL/TLS 1.3
        </footer>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bank.js') }}"></script>
    @stack('scripts')
</body>
</html>
