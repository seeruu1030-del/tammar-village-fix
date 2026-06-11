<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'T-Link Admin Dashboard')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @stack('styles')
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased overflow-hidden flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-800 text-slate-300 flex flex-col hidden md:flex transition-all duration-300 shadow-xl z-20 shrink-0">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-slate-700/50 bg-slate-900/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-500 to-blue-700 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-blue-500/30">
                    T
                </div>
                <span class="text-white font-bold text-xl tracking-wide logo-text">T-Link <span class="text-xs font-normal text-slate-400 align-top">Admin</span></span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1" id="sidebar-menu">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-4 sidebar-label">Menu Utama</p>
            
            <a href="{{ url('/admin') }}" class="flex items-center gap-3 px-3 py-2.5 {{ Request::is('admin') ? 'bg-sky-500/10 text-sky-500 border-r-2 border-sky-500' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span class="font-medium nav-text">Dashboard</span>
            </a>
            
            <!-- Collapsible Menu: Data Warga -->
            <div>
                <button onclick="toggleDropdown('dropdown-warga', 'arrow-warga')" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-users w-5 text-center group-hover:text-sky-500 transition-colors"></i>
                        <span class="font-medium nav-text">Data Warga</span>
                    </div>
                    <i id="arrow-warga" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ Request::is('admin/residents*', 'admin/vehicles*', 'admin/id-cards*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="dropdown-warga" class="overflow-hidden max-h-0 opacity-0 submenu-transition bg-slate-900/40 rounded-lg mt-1 space-y-1 {{ Request::is('admin/residents*', 'admin/vehicles*', 'admin/id-cards*') ? 'max-h-screen opacity-100 py-1' : '' }}">
                    <a href="{{ url('/admin/residents') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/residents') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Direktori Warga</span>
                    </a>
                    <a href="{{ route('admin.residents.pending') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/residents/pending') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-dot text-[6px]"></i>
                            <span class="nav-text">Persetujuan Baru</span>
                        </div>
                    </a>
                    <a href="{{ url('/admin/vehicles') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/vehicles') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Data Kendaraan</span>
                    </a>
                    <a href="{{ url('/admin/id-cards') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/id-cards') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">ID Card Warga</span>
                    </a>
                    <a href="{{ url('/admin/residents/non-active') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/residents/non-active') ? 'text-rose-500 bg-slate-800' : 'text-rose-400 hover:text-rose-500 hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text font-bold">Warga Non Aktif</span>
                    </a>
                </div>
            </div>

            <!-- Collapsible Menu: Denah & Blok -->
            <div>
                <button onclick="toggleDropdown('dropdown-denah', 'arrow-denah')" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-map-location-dot w-5 text-center group-hover:text-amber-500 transition-colors"></i>
                        <span class="font-medium nav-text">Denah & Blok</span>
                    </div>
                    <i id="arrow-denah" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ Request::is('admin/blocks*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="dropdown-denah" class="overflow-hidden max-h-0 opacity-0 submenu-transition bg-slate-900/40 rounded-lg mt-1 space-y-1 {{ Request::is('admin/blocks*') ? 'max-h-screen opacity-100 py-1' : '' }}">
                    <a href="{{ url('/admin/blocks') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/blocks') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Manajemen Blok</span>
                    </a>
                </div>
            </div>

            <!-- Collapsible Menu: Keuangan & Iuran -->
            <div>
                <button onclick="toggleDropdown('dropdown-keuangan', 'arrow-keuangan')" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-wallet w-5 text-center group-hover:text-emerald-500 transition-colors"></i>
                        <span class="font-medium nav-text">Keuangan & Iuran</span>
                    </div>
                    <i id="arrow-keuangan" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ Request::is('admin/finance*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="dropdown-keuangan" class="overflow-hidden max-h-0 opacity-0 submenu-transition bg-slate-900/40 rounded-lg mt-1 space-y-1 {{ Request::is('admin/finance*') ? 'max-h-screen opacity-100 py-1' : '' }}">
                    <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Dashboard Keuangan</span>
                    </a>
                    <a href="{{ route('admin.finance.index') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/finance') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Iuran Warga</span>
                    </a>
                    <a href="{{ route('admin.finance.verification') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/finance/verification') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Verifikasi Pembayaran</span>
                    </a>
                    <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Laporan Kas</span>
                    </a>
                    <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Buku Kas Umum</span>
                    </a>
                </div>
            </div>

            <!-- Collapsible Menu: Tabungan Warga -->
            <div>
                <button onclick="toggleDropdown('dropdown-tabungan', 'arrow-tabungan')" class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-piggy-bank w-5 text-center group-hover:text-amber-500 transition-colors"></i>
                        <span class="font-medium nav-text">Tabungan Warga</span>
                    </div>
                    <i id="arrow-tabungan" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ Request::is('admin/savings*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div id="dropdown-tabungan" class="overflow-hidden max-h-0 opacity-0 submenu-transition bg-slate-900/40 rounded-lg mt-1 space-y-1 {{ Request::is('admin/savings*') ? 'max-h-screen opacity-100 py-1' : '' }}">
                    <a href="{{ url('/admin/savings-programs') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/savings-programs') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Daftar Program</span>
                    </a>
                    <a href="{{ url('/admin/savings-deposits') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm {{ Request::is('admin/savings-deposits') ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Setoran Warga</span>
                    </a>
                    <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 pl-11 pr-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded-md transition-colors">
                        <i class="fa-solid fa-circle-dot text-[6px]"></i>
                        <span class="nav-text">Pencairan Dana</span>
                    </a>
                </div>
            </div>

            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-6 sidebar-label">Layanan & Komunikasi</p>

            <!-- Menu Utama: Kunjungan Tamu -->
            <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-address-book w-5 text-center group-hover:text-sky-500 transition-colors"></i>
                    <span class="font-medium nav-text">Kunjungan Tamu</span>
                </div>
                <span class="bg-slate-700 text-white text-[10px] px-2 py-0.5 rounded-full nav-text">12</span>
            </a>

            <!-- Menu Utama: Keluhan & Darurat -->
            <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation w-5 text-center group-hover:text-rose-500 transition-colors"></i>
                    <span class="font-medium nav-text">Keluhan & Darurat</span>
                </div>
                <span class="bg-rose-500 text-white text-[10px] px-2 py-0.5 rounded-full animate-pulse nav-text">2</span>
            </a>

            <!-- Menu Utama: Pengumuman -->
            <a href="{{ route('admin.announcements.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ Request::is('admin/announcements*') ? 'bg-sky-500/10 text-sky-500 border-r-2 border-sky-500' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors group">
                <i class="fa-solid fa-bullhorn w-5 text-center group-hover:text-sky-500 transition-colors"></i>
                <span class="font-medium nav-text">Pengumuman</span>
            </a>

            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-6 sidebar-label">Integrasi Perbankan</p>

            <!-- Menu Utama: Data Virtual Account -->
            <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-building-columns w-5 text-center group-hover:text-amber-500 transition-colors"></i>
                <span class="font-medium nav-text">Data Virtual Account</span>
            </a>

            <!-- Menu Utama: Konfigurasi API Bank -->
            <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-server w-5 text-center group-hover:text-emerald-500 transition-colors"></i>
                <span class="font-medium nav-text">Konfigurasi API Bank</span>
            </a>

            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-6 sidebar-label">Administrasi Sistem</p>

            <!-- Menu Utama: Pengaturan Sistem -->
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 {{ Request::is('admin/settings*') ? 'bg-sky-500/10 text-sky-500 border-r-2 border-sky-500' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors group">
                <i class="fa-solid fa-gear w-5 text-center group-hover:text-slate-300 transition-colors"></i>
                <span class="font-medium nav-text">Pengaturan Sistem</span>
            </a>

            <!-- Menu Utama: Backup Sistem -->
            <a href="{{ route('admin.under_construction') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-database w-5 text-center group-hover:text-amber-500 transition-colors"></i>
                <span class="font-medium nav-text">Backup Sistem</span>
            </a>
        </div>

        <!-- User Profile (Bottom) -->
        <div class="p-4 border-t border-slate-700/50 bg-slate-900/30 shrink-0">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff" alt="Admin" class="w-10 h-10 rounded-full border-2 border-slate-600">
                <div class="footer-text">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50/50">
        
        <!-- Topbar -->
        <header class="h-16 glass-card border-b border-slate-200 flex items-center justify-between px-6 z-10 sticky top-0 shadow-sm shrink-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-500 hover:text-slate-700" onclick="toggleMobileSidebar()">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <button class="hidden md:flex text-slate-500 hover:text-slate-700 transition-colors" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative group">
                    <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 py-1.5 px-2 rounded-xl hover:bg-slate-100 transition-all border border-transparent hover:border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 mt-1">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff" alt="Admin" class="w-9 h-9 rounded-lg border border-slate-200">
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>

                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden transform origin-top-right transition-all">
                        <div class="p-4 border-b border-slate-50 bg-slate-50/50">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1.5">Akun Terdaftar</p>
                            <p class="text-sm font-black text-slate-800">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                        </div>
                        <div class="p-2 space-y-1">
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors group">
                                <i class="fa-solid fa-gears w-4 text-center text-slate-400 group-hover:text-sky-500"></i> Pengaturan Sistem
                            </a>
                        </div>
                        <div class="p-2 border-t border-slate-50">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 rounded-xl transition-colors font-black text-left group">
                                    <i class="fa-solid fa-power-off w-4 text-center group-hover:animate-pulse"></i> Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 relative">
            @yield('content')
        </div>

        <footer class="h-10 bg-white border-t border-slate-200 flex items-center justify-center text-xs text-slate-400 font-medium tracking-wide shrink-0 z-10">
            &copy; 2026 T-Link - The Tamar Village Smart Management System.
        </footer>
    </main>

    <x-toast />
    <x-confirm-delete />

    <!-- Scripts -->
    <script src="{{ asset('assets/js/navigation.js') }}"></script>
    <script src="{{ asset('assets/js/ui-helpers.js') }}"></script>
    <script src="{{ asset('assets/js/charts.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
