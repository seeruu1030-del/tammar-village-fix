<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Warga - The Tamar Village')</title>
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
<body class="bg-slate-100 font-sans text-slate-800 antialiased overflow-hidden flex h-screen">

    <!-- Sidebar (Tema Emerald untuk Warga) -->
    <aside id="warga-sidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-slate-800 text-slate-300 flex flex-col transition-all duration-300 ease-in-out shadow-xl z-50 shrink-0 transform -translate-x-full md:translate-x-0">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-slate-700/50 bg-slate-900/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-emerald-500/30">
                    W
                </div>
                <span class="text-white font-bold text-xl tracking-wide logo-text">T-Link <span class="text-xs font-normal text-slate-400 align-top">Warga</span></span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1" id="sidebar-menu">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-4 sidebar-label">Menu Utama</p>
            
            <a href="{{ route('warga.dashboard') }}" id="nav-dashboard" class="flex items-center gap-3 px-3 py-2.5 {{ Request::is('warga') ? 'bg-emerald-500/10 text-emerald-500 border-r-2 border-emerald-500' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} rounded-lg transition-colors group">
                <i class="fa-solid fa-house w-5 text-center"></i>
                <span class="font-medium nav-text">Dashboard</span>
            </a>

            <a href="#" onclick="switchWargaView('profil')" id="nav-profil" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-id-card w-5 text-center"></i>
                <span class="font-medium nav-text">Profil & ID Card</span>
            </a>

            <a href="#" onclick="switchWargaView('iuran')" id="nav-iuran" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i>
                <span class="font-medium nav-text">Tagihan & Iuran</span>
            </a>

            <a href="#" onclick="switchWargaView('tabungan')" id="nav-tabungan" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-coins w-5 text-center"></i>
                <span class="font-medium nav-text">Data Tabungan</span>
            </a>

            <a href="#" onclick="switchWargaView('virtual-account')" id="nav-virtual-account" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-building-columns w-5 text-center"></i>
                <span class="font-medium nav-text">Data Virtual Account</span>
            </a>

            <a href="#" onclick="switchWargaView('tamu')" id="nav-tamu" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-user-clock w-5 text-center"></i>
                <span class="font-medium nav-text">Pendaftaran Tamu</span>
            </a>

            <a href="#" onclick="switchWargaView('laporan')" id="nav-laporan" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-circle-exclamation w-5 text-center"></i>
                <span class="font-medium nav-text">Lapor & Darurat</span>
            </a>
            
            <a href="#" onclick="switchWargaView('keamanan')" id="nav-keamanan" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-colors group">
                <i class="fa-solid fa-shield-halved w-5 text-center"></i>
                <span class="font-medium nav-text">Keamanan</span>
            </a>
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-slate-700/50 bg-slate-900/30">
            <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-800/50">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff" class="w-8 h-8 rounded-full">
                <div class="overflow-hidden footer-text">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-500 truncate">
                        @if(Auth::user()->resident && Auth::user()->resident->block)
                            Blok {{ Auth::user()->resident->block->name }} / No. {{ Auth::user()->resident->unit_no }}
                        @else
                            Warga
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 bg-slate-100 relative overflow-hidden">
        
        <!-- Header / Topbar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="text-slate-600 p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <h2 id="view-title" class="text-lg font-bold text-slate-800">@yield('view_title', 'Portal Warga')</h2>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-6">
                <!-- Panic Button Header (Paling Penting) -->
                <button onclick="triggerEmergency('{{ Auth::user()->name }}', '{{ Auth::user()->resident ? Auth::user()->resident->block->name . ' / ' . Auth::user()->resident->unit_no : '-' }}')" class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg shadow-rose-500/30 transition-all flex items-center gap-2 animate-pulse">
                    <i class="fa-solid fa-triangle-exclamation"></i> PANIC BUTTON
                </button>
                
                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 py-1 px-1 rounded-xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff" alt="User" class="w-9 h-9 rounded-lg border border-slate-100 shadow-sm">
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-slate-500 mt-1">
                                @if(Auth::user()->resident)
                                    Blok {{ Auth::user()->resident->block->name }} / No. {{ Auth::user()->resident->unit_no }}
                                @else
                                    Warga
                                @endif
                            </p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 mr-1"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-52 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden transform origin-top-right transition-all">
                        <div class="p-4 border-b border-slate-50 bg-slate-50/50">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Akun Warga</p>
                            <p class="text-xs font-bold text-slate-700 mt-2 truncate">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                        </div>
                        <div class="p-2">
                            <a href="#" onclick="switchWargaView('profil')" class="flex items-center gap-3 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-xl transition-colors font-medium">
                                <i class="fa-solid fa-user-circle w-4 text-center"></i> Profil Saya
                            </a>
                            <a href="#" onclick="switchWargaView('keamanan')" class="flex items-center gap-3 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-xl transition-colors font-medium">
                                <i class="fa-solid fa-shield-halved w-4 text-center"></i> Keamanan
                            </a>
                            <div class="h-px bg-slate-100 my-1 mx-2"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 rounded-xl transition-colors font-bold text-left">
                                    <i class="fa-solid fa-power-off w-4 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Content Area -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-8 relative">
            <!-- Global Success Message -->
            @if(session('success'))
            <div id="global-success" class="absolute top-4 left-1/2 -translate-x-1/2 bg-emerald-600 text-white px-6 py-3 rounded-full font-bold text-sm shadow-xl flex items-center gap-3 z-50 transition-all duration-500">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <script>
                setTimeout(() => {
                    const el = document.getElementById('global-success');
                    if(el) {
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 500);
                    }
                }, 4000);
            </script>
            @endif

            <!-- Global Error Message -->
            @if($errors->any())
            <div id="global-error" class="absolute top-4 left-1/2 -translate-x-1/2 bg-rose-600 text-white px-6 py-3 rounded-full font-bold text-sm shadow-xl flex items-center gap-3 z-50 transition-all duration-500">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span>Terdapat {{ $errors->count() }} kesalahan form. Silakan cek kembali inputan Anda.</span>
            </div>
            <script>
                setTimeout(() => {
                    const el = document.getElementById('global-error');
                    if(el) {
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 500);
                    }
                }, 5000);
            </script>
            @endif

            @yield('content')
        </div> <!-- END SCROLLABLE CONTENT -->

        <!-- Sticky Footer -->
        <footer class="h-10 bg-white border-t border-slate-200 flex items-center justify-center text-[10px] text-slate-400 font-medium tracking-wide shrink-0">
            &copy; 2026 T-Link Warga - BNI 46 & Terintegrasi.
        </footer>
    </main>

    @yield('modals')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/navigation.js') }}"></script>
    <script src="{{ asset('assets/js/warga.js') }}"></script>
    <!-- Midtrans Snap JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    @stack('scripts')
</body>
</html>
