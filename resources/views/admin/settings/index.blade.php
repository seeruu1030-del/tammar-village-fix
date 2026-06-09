@extends('layouts.admin')

@section('title', 'Pengaturan Sistem | T-Link Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h1>
    <p class="text-sm text-slate-500 mt-1">Kelola profil, konfigurasi keuangan, dan akses staff dalam satu tempat.</p>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col md:flex-row min-h-[600px]">
    <!-- Tabs Sidebar -->
    <div class="w-full md:w-64 bg-slate-50/50 border-r border-slate-100 p-4 space-y-1">
        <button onclick="switchTab('profile')" id="tab-btn-profile" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all bg-white shadow-sm text-sky-600 border border-slate-200">
            <i class="fa-solid fa-user-circle w-5 text-center"></i> Profil Saya
        </button>
        <button onclick="switchTab('finance')" id="tab-btn-finance" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-500 hover:bg-white hover:text-emerald-600 transition-all">
            <i class="fa-solid fa-wallet w-5 text-center"></i> Keuangan
        </button>
        <button onclick="switchTab('staff')" id="tab-btn-staff" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-500 hover:bg-white hover:text-indigo-600 transition-all">
            <i class="fa-solid fa-users-gear w-5 text-center"></i> Manajemen Staff
        </button>
    </div>

    <!-- Tab Content -->
    <div class="flex-1 p-8">
        <!-- TAB: PROFIL SAYA -->
        <div id="tab-content-profile" class="tab-content">
            <div class="max-w-2xl">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-sky-500 rounded-full"></span> Detail Akun Pengelola
                </h3>
                <form action="{{ route('admin.settings.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Username</label>
                            <input type="text" name="username" value="{{ $user->username }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Aktif</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 mt-8">
                        <h4 class="text-sm font-black text-slate-700 mb-4">Ganti Password (Opsional)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password Saat Ini</label>
                                <input type="password" name="current_password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password Baru</label>
                                <input type="password" name="new_password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm outline-none transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Konfirmasi Password</label>
                                <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-lg shadow-sky-500/30 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: KEUANGAN -->
        <div id="tab-content-finance" class="tab-content hidden">
            <div class="max-w-2xl">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span> Parameter Keuangan Dasar
                </h3>
                <form action="{{ route('admin.settings.finance.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Biaya Iuran Keamanan (Rp)</label>
                            <input type="number" value="150000" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Biaya Kebersihan & Sampah (Rp)</label>
                            <input type="number" value="50000" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor Rekening Bendahara (BNI)</label>
                            <input type="text" value="8823 0081 1223 3445" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-mono font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 flex gap-3">
                        <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
                        <p class="text-[11px] text-amber-800 leading-relaxed font-medium">Perubahan pada parameter keuangan dasar akan berpengaruh pada tagihan invoice otomatis bulan depan untuk seluruh warga.</p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-check-double"></i> Simpan Konfigurasi Keuangan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB: STAFF -->
        <div id="tab-content-staff" class="tab-content hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span> Manajemen Akun Staff
                </h3>
                <button onclick="toggleModal('modal-tambah-staff', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Staff Baru
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                            <th class="py-4 px-6">Nama Staff</th>
                            <th class="py-4 px-6">Username</th>
                            <th class="py-4 px-6">Role / Akses</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($staffs as $staff)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-800">{{ $staff->name }}</td>
                            <td class="py-4 px-6 text-slate-500">{{ $staff->username }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                    {{ $staff->role == 'admin' ? 'bg-sky-50 text-sky-600 border border-sky-100' : ($staff->role == 'bank' ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $staff->role }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('admin.settings.staff.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akses staff ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400 italic">Belum ada staff tambahan terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: TAMBAH STAFF ================= -->
<div id="modal-tambah-staff" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="relative h-32 bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 flex items-center px-8 overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="relative z-10">
                <h3 class="font-black text-white text-2xl">Tambah Staff Pengurus</h3>
                <p class="text-indigo-300 text-sm font-medium">Berikan akses aksesibilitas khusus pengurus.</p>
            </div>
            <button onclick="toggleModal('modal-tambah-staff', false)" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-2xl bg-white/10 text-white hover:bg-rose-500 transition-all backdrop-blur-md">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.settings.staff.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:border-indigo-500 transition-all">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Username</label>
                    <input type="text" name="username" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:border-indigo-500 transition-all">
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hak Akses (Role)</label>
                <select name="role" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:border-indigo-500 transition-all">
                    <option value="admin">Admin (Full Access)</option>
                    <option value="bank">Bendahara / Bank</option>
                    <option value="security">Keamanan / Security</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password Awal</label>
                <input type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold outline-none focus:border-indigo-500 transition-all">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:shadow-indigo-500/30 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                    <i class="fa-solid fa-user-shield"></i> Daftarkan Akun Staff
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
            setTimeout(() => modal.classList.add('invisible'), 200);
        }
    }

    function switchTab(tab) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        // Show active content
        document.getElementById(`tab-content-${tab}`).classList.remove('hidden');

        // Reset all buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-white', 'shadow-sm', 'text-sky-600', 'text-emerald-600', 'text-indigo-600', 'border', 'border-slate-200');
            b.classList.add('text-slate-500');
        });

        // Set active button
        const btn = document.getElementById(`tab-btn-${tab}`);
        btn.classList.remove('text-slate-500');
        const colors = { 'profile': 'text-sky-600', 'finance': 'text-emerald-600', 'staff': 'text-indigo-600' };
        btn.classList.add('bg-white', 'shadow-sm', colors[tab], 'border', 'border-slate-200');
    }
</script>
@endpush
@endsection
