@extends('layouts.admin')

@section('title', 'Pengaturan Sistem | T-Link Admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-sky-600 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-gears"></i>
                <span>Administrasi Sistem</span>
            </div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Sistem</h1>
            <p class="text-slate-500 mt-1 font-medium">Konfigurasi profil pengelola, parameter keuangan, dan manajemen akses staff.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="hidden md:flex flex-col items-end px-4 border-r border-slate-200">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Sistem</span>
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Terhubung (Online)
                </span>
            </div>
            <div class="p-2 bg-white rounded-2xl border border-slate-200 shadow-sm">
                <i class="fa-solid fa-shield-halved text-sky-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Navigation -->
        <div class="w-full lg:w-72 space-y-2">
            <button onclick="switchTab('profile')" id="tab-btn-profile" class="tab-btn w-full flex items-center justify-between px-6 py-4 rounded-2xl text-sm font-black transition-all group bg-white shadow-sm border border-slate-200 text-sky-600">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-gear text-lg"></i>
                    </div>
                    <span>Profil Saya</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-50"></i>
            </button>

            <button onclick="switchTab('finance')" id="tab-btn-finance" class="tab-btn w-full flex items-center justify-between px-6 py-4 rounded-2xl text-sm font-black transition-all group text-slate-500 hover:bg-white hover:text-emerald-600">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <i class="fa-solid fa-vault text-lg"></i>
                    </div>
                    <span>Keuangan</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover:opacity-50"></i>
            </button>

            <button onclick="switchTab('staff')" id="tab-btn-staff" class="tab-btn w-full flex items-center justify-between px-6 py-4 rounded-2xl text-sm font-black transition-all group text-slate-500 hover:bg-white hover:text-indigo-600">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center group-hover:bg-indigo-50 transition-colors">
                        <i class="fa-solid fa-users-viewfinder text-lg"></i>
                    </div>
                    <span>Manajemen Staff</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover:opacity-50"></i>
            </button>

            <div class="mt-8 p-6 bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] text-white relative overflow-hidden shadow-xl shadow-slate-200">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>
                <h4 class="text-xs font-black uppercase tracking-[0.2em] opacity-50 mb-4">Informasi Keamanan</h4>
                <div class="space-y-4 relative z-10">
                    <div class="flex gap-3">
                        <i class="fa-solid fa-clock-rotate-left text-sky-400 mt-1"></i>
                        <div>
                            <p class="text-[10px] font-bold opacity-60">Login Terakhir</p>
                            <p class="text-xs font-black">Hari ini, 08:24 WIB</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <i class="fa-solid fa-earth-asia text-emerald-400 mt-1"></i>
                        <div>
                            <p class="text-[10px] font-bold opacity-60">Alamat IP</p>
                            <p class="text-xs font-black">127.0.0.1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1">
            <!-- TAB: PROFIL SAYA -->
            <div id="tab-content-profile" class="tab-content transition-all duration-300">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="h-32 bg-gradient-to-r from-sky-400 to-blue-600 relative">
                        <div class="absolute -bottom-12 left-10 p-1.5 bg-white rounded-[2rem] shadow-xl">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0ea5e9&color=fff&size=128" alt="Avatar" class="w-24 h-24 rounded-[1.7rem] object-cover border-4 border-white">
                            <button class="absolute bottom-1 right-1 w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center border-4 border-white hover:bg-sky-500 transition-colors">
                                <i class="fa-solid fa-camera text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="pt-16 p-10">
                        <div class="mb-8">
                            <h3 class="text-xl font-black text-slate-800">Detail Profil Pengelola</h3>
                            <p class="text-slate-500 text-sm mt-1">Perbarui informasi dasar dan kredensial akses Anda.</p>
                        </div>

                        <form action="{{ route('admin.settings.profile.update') }}" method="POST" class="space-y-8">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors">
                                            <i class="fa-solid fa-id-card-clip"></i>
                                        </div>
                                        <input type="text" name="name" value="{{ $user->name }}" class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition-all" placeholder="Masukkan nama lengkap...">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Username Akses</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors">
                                            <i class="fa-solid fa-at"></i>
                                        </div>
                                        <input type="text" name="username" value="{{ $user->username }}" class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition-all" placeholder="Username login...">
                                    </div>
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Email Aktif</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                        <input type="email" name="email" value="{{ $user->email }}" class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition-all" placeholder="Alamat email untuk notifikasi...">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-100">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500">
                                        <i class="fa-solid fa-lock text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800">Keamanan & Password</h4>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Saat Ini</label>
                                        <input type="password" name="current_password" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm outline-none transition-all focus:border-sky-500" placeholder="••••••••">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Baru</label>
                                        <input type="password" name="new_password" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm outline-none transition-all focus:border-sky-500" placeholder="Minimal 8 karakter">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                                        <input type="password" name="new_password_confirmation" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm outline-none transition-all focus:border-sky-500" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="bg-slate-900 hover:bg-sky-600 text-white px-10 py-4 rounded-2xl font-black text-sm shadow-xl hover:shadow-sky-500/20 transition-all flex items-center gap-3 active:scale-95">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    Simpan Perubahan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB: KEUANGAN -->
            <div id="tab-content-finance" class="tab-content hidden transition-all duration-300">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden p-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-16 h-16 rounded-3xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl shadow-inner shadow-emerald-200/50">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Konfigurasi Parameter Keuangan</h3>
                            <p class="text-slate-500 text-sm mt-1">Atur standar biaya iuran dan metode pembayaran warga.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.finance.update') }}" method="POST" class="space-y-10">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Biaya Rutin Bulanan</h4>
                                    <span class="text-[10px] bg-sky-50 text-sky-600 px-2 py-0.5 rounded-lg font-bold">Wajib</span>
                                </div>
                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-600 ml-1">Iuran Keamanan (Security Fee)</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none font-bold text-slate-400">Rp</div>
                                            <input type="number" name="security_fee" value="{{ $settings['security_fee'] ?? 150000 }}" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-600 ml-1">Kebersihan & Sampah (Waste Management)</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none font-bold text-slate-400">Rp</div>
                                            <input type="number" name="waste_fee" value="{{ $settings['waste_fee'] ?? 50000 }}" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-black text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Metode Pembayaran Transfer</h4>
                                    <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-lg font-bold">Aktif</span>
                                </div>
                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-600 ml-1">Bank Pengumpul (Vendor)</label>
                                        <select name="bank_name" class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all appearance-none">
                                            <option value="BNI" {{ ($settings['bank_name'] ?? '') == 'BNI' ? 'selected' : '' }}>Bank Negara Indonesia (BNI)</option>
                                            <option value="BCA" {{ ($settings['bank_name'] ?? '') == 'BCA' ? 'selected' : '' }}>Bank Central Asia (BCA)</option>
                                            <option value="Mandiri" {{ ($settings['bank_name'] ?? '') == 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-600 ml-1">Nomor Rekening Bendahara</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-solid fa-credit-card"></i>
                                            </div>
                                            <input type="text" name="bank_account_number" value="{{ $settings['bank_account_number'] ?? '8823 0081 1223 3445' }}" class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-mono font-bold text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-dashed border-slate-300 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-amber-500 shadow-sm shrink-0 mt-1">
                                <i class="fa-solid fa-lightbulb"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-1">Catatan Penting:</h5>
                                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">Perubahan pada nilai biaya rutin akan secara otomatis diterapkan pada **Invoice Masal** yang digenerate oleh sistem pada tanggal 1 setiap bulannya. Pastikan untuk menginformasikan warga melalui fitur Pengumuman jika ada penyesuaian tarif.</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-2xl font-black text-sm shadow-xl shadow-emerald-600/20 transition-all flex items-center gap-3 active:scale-95">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                Simpan Konfigurasi Keuangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB: STAFF -->
            <div id="tab-content-staff" class="tab-content hidden transition-all duration-300">
                <div class="flex justify-between items-end mb-8 px-4">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800">Manajemen Akses Staff</h3>
                        <p class="text-slate-500 text-sm mt-1">Kelola akun pengurus dengan hak akses spesifik.</p>
                    </div>
                    <button onclick="toggleModal('modal-tambah-staff', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3.5 rounded-2xl text-sm font-black shadow-xl shadow-indigo-600/20 transition-all flex items-center gap-3 active:scale-95">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        Tambah Staff
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6">
                    @forelse($staffs as $staff)
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4">
                            <div class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                {{ $staff->role == 'admin' ? 'bg-sky-50 text-sky-600' : ($staff->role == 'bank' ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-600') }}">
                                {{ $staff->role }}
                            </div>
                        </div>

                        <div class="flex items-center gap-5 relative z-10">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->name) }}&background=f1f5f9&color=475569&bold=true" alt="Staff" class="w-16 h-16 rounded-2xl bg-slate-100 object-cover">
                            <div>
                                <h4 class="font-black text-slate-800">{{ $staff->name }}</h4>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">{{ '@' . $staff->username }}</p>
                                <div class="flex items-center gap-2 mt-3">
                                    <span class="text-[10px] text-slate-400 flex items-center gap-1.5">
                                        <i class="fa-solid fa-shield-check text-emerald-500"></i> Terverifikasi
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between relative z-10">
                            <div class="flex gap-2">
                                <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all flex items-center justify-center">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-sky-50 hover:text-sky-600 transition-all flex items-center justify-center">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </button>
                            </div>
                            
                            <form action="{{ route('admin.settings.staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Hapus akses staff ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors px-4 py-2 text-xs font-black uppercase tracking-widest">
                                    Hapus Akses
                                </button>
                            </form>
                        </div>

                        <!-- Decoration -->
                        <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-slate-50 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 -z-0"></div>
                    </div>
                    @empty
                    <div class="md:col-span-2 py-20 bg-white rounded-[3rem] border border-dashed border-slate-200 flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-3xl mb-4">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>
                        <h4 class="font-black text-slate-800">Belum Ada Staff Tambahan</h4>
                        <p class="text-slate-500 text-sm mt-1">Daftarkan staff baru untuk membantu mengelola operasional warga.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: TAMBAH STAFF ================= -->
<div id="modal-tambah-staff" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 transition-all duration-300">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-xl overflow-hidden transform scale-95 transition-all duration-300 border border-white/20">
        <div class="relative p-10 pb-6">
            <button onclick="toggleModal('modal-tambah-staff', false)" class="absolute top-8 right-8 w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="flex items-center gap-4 mb-2">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-xl shadow-sm border border-indigo-100">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-2xl tracking-tight">Daftarkan Staff Baru</h3>
                    <p class="text-slate-500 text-sm font-medium">Buat akun akses untuk pengurus portal.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.settings.staff.store') }}" method="POST" class="px-10 pb-10 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" placeholder="Nama asli staff">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Username Unik</label>
                    <input type="text" name="username" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" placeholder="username_staff">
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Level Akses (Role)</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer group">
                        <input type="radio" name="role" value="admin" class="peer hidden" checked>
                        <div class="py-4 text-center rounded-2xl bg-slate-50 border-2 border-transparent group-hover:bg-slate-100 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                            <i class="fa-solid fa-shield-halved text-lg mb-2 block peer-checked:text-indigo-600 opacity-40 peer-checked:opacity-100"></i>
                            <span class="text-[10px] font-black uppercase text-slate-500 peer-checked:text-indigo-600">Admin</span>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="role" value="bank" class="peer hidden">
                        <div class="py-4 text-center rounded-2xl bg-slate-50 border-2 border-transparent group-hover:bg-slate-100 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                            <i class="fa-solid fa-piggy-bank text-lg mb-2 block peer-checked:text-amber-600 opacity-40 peer-checked:opacity-100"></i>
                            <span class="text-[10px] font-black uppercase text-slate-500 peer-checked:text-amber-600">Bendahara</span>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="role" value="security" class="peer hidden">
                        <div class="py-4 text-center rounded-2xl bg-slate-50 border-2 border-transparent group-hover:bg-slate-100 peer-checked:border-slate-500 peer-checked:bg-slate-100 transition-all">
                            <i class="fa-solid fa-user-shield text-lg mb-2 block peer-checked:text-slate-800 opacity-40 peer-checked:opacity-100"></i>
                            <span class="text-[10px] font-black uppercase text-slate-500 peer-checked:text-slate-800">Security</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password Keamanan Awal</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fa-solid fa-key text-xs"></i>
                    </div>
                    <input type="password" name="password" required class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" placeholder="••••••••">
                </div>
                <p class="text-[10px] text-slate-400 italic mt-1 font-medium">*Berikan password ini kepada staff yang bersangkutan untuk login pertama kali.</p>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white py-5 rounded-[1.8rem] font-black text-sm uppercase tracking-widest shadow-2xl shadow-slate-900/20 hover:shadow-indigo-500/30 transition-all flex items-center justify-center gap-3 active:scale-[0.97]">
                    <i class="fa-solid fa-circle-check"></i> 
                    Finalisasi Akun Staff
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Handle tab from URL
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            switchTab(tab);
        }
    });

    function toggleModal(id, show) {
        const modal = document.getElementById(id);
        const box = modal.querySelector('div');
        if (show) {
            modal.classList.remove('invisible', 'opacity-0');
            box.classList.remove('scale-95');
        } else {
            box.classList.add('scale-95');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('invisible'), 300);
        }
    }

    function switchTab(tab) {
        // Hide all contents with animation
        document.querySelectorAll('.tab-content').forEach(c => {
            c.classList.add('hidden');
        });
        
        // Show active content
        const activeContent = document.getElementById(`tab-content-${tab}`);
        activeContent.classList.remove('hidden');

        // Reset all buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-white', 'shadow-sm', 'text-sky-600', 'text-emerald-600', 'text-indigo-600', 'border-slate-200');
            b.classList.add('text-slate-500');
            
            const iconBox = b.querySelector('.w-10');
            iconBox.classList.remove('bg-sky-50', 'bg-emerald-50', 'bg-indigo-50');
            iconBox.classList.add('bg-slate-100');
            
            const arrow = b.querySelector('.fa-chevron-right');
            arrow.classList.add('opacity-0');
            arrow.classList.remove('opacity-50');
        });

        // Set active button
        const btn = document.getElementById(`tab-btn-${tab}`);
        btn.classList.remove('text-slate-500');
        btn.classList.add('bg-white', 'shadow-sm', 'border-slate-200');
        
        const colors = { 'profile': 'text-sky-600', 'finance': 'text-emerald-600', 'staff': 'text-indigo-600' };
        const bgColors = { 'profile': 'bg-sky-50', 'finance': 'bg-emerald-50', 'staff': 'bg-indigo-50' };
        
        btn.classList.add(colors[tab]);
        btn.querySelector('.w-10').classList.remove('bg-slate-100');
        btn.querySelector('.w-10').classList.add(bgColors[tab]);
        
        const arrow = btn.querySelector('.fa-chevron-right');
        arrow.classList.remove('opacity-0');
        arrow.classList.add('opacity-50');
    }
</script>
@endpush
@endsection
