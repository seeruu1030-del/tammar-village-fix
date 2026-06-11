@extends('layouts.admin')

@section('title', 'Direktori Warga | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Direktori Warga Aktif</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data profil dan unit rumah warga yang saat ini aktif.</p>
    </div>
    <div class="flex gap-2">
        <button id="btn-registrasi-massal" onclick="openRegisterModal()" class="hidden bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg shadow-amber-500/30 transition-all flex items-center gap-2">
            <i class="fa-solid fa-id-card"></i> Registrasi
        </button>
        <button onclick="alert('Mengekspor data direktori warga ke Excel...')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm font-medium shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-file-export"></i> Ekspor Data
        </button>
        <button onclick="toggleModal('modal-tambah-warga', true)" class="bg-sky-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Tambah Warga
        </button>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse min-w-[1200px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
                    <th class="py-3 px-4 w-10 text-center">
                        <input type="checkbox" id="check-all-warga" class="rounded border-slate-300 text-sky-500 focus:ring-sky-500 cursor-pointer" onchange="toggleAllWarga(this)">
                    </th>
                    <th class="py-3 px-4">Profil Warga</th>
                    <th class="py-3 px-4">Status Keluarga</th>
                    <th class="py-3 px-4">Usia</th>
                    <th class="py-3 px-4">Kelengkapan</th>
                    <th class="py-3 px-4">Unit Rumah</th>
                    <th class="py-3 px-4">Kontak</th>
                    <th class="py-3 px-4">Status Hunian</th>
                    <th class="py-3 px-4">Status Akun</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($residents as $resident)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="py-3 px-4 text-center">
                        <input type="checkbox" name="selected_warga[]" value="{{ $resident->id }}" class="warga-checkbox rounded border-slate-300 text-sky-500 focus:ring-sky-500 cursor-pointer" onchange="toggleWargaItem()">
                    </td>
                    <td class="py-3 px-4 flex items-center gap-3">
                        @if($resident->familyMembers->count() > 0)
                        <button onclick="toggleFamilyRows('{{ $resident->id }}', this)" class="w-6 h-6 flex items-center justify-center rounded bg-slate-100 text-slate-400 hover:bg-emerald-100 hover:text-emerald-600 transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] transition-transform duration-300"></i>
                        </button>
                        @else
                        <div class="w-6"></div>
                        @endif
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($resident->name) }}&background=0ea5e9&color=fff" class="w-9 h-9 rounded-full">
                        <div>
                            <p class="font-medium text-slate-800">{{ $resident->name }}</p>
                            <p class="text-[11px] text-slate-400">NIK: {{ $resident->nik }}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $resident->family_status == 'KK' ? 'Kepala Keluarga' : $resident->family_status }}</span>
                    </td>
                    <td class="py-3 px-4 text-slate-600 font-medium">
                        {{ $resident->age ? $resident->age . ' Thn' : '-' }}
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <div class="w-16 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="h-full {{ $resident->completeness > 80 ? 'bg-emerald-500' : ($resident->completeness > 50 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $resident->completeness }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">{{ $resident->completeness }}%</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-medium text-slate-700">Blok {{ $resident->block->name }} / {{ $resident->unit_no }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <p class="text-slate-600">{{ $resident->contact }}</p>
                    </td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md">{{ $resident->housing_status }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex flex-col gap-1">
                            <span class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-bold uppercase tracking-tight">
                                <i class="fa-solid fa-circle text-[6px]"></i> {{ $resident->status }}
                            </span>
                            @if($resident->user_id)
                                <span class="flex items-center gap-1 text-sky-600 text-[9px] font-medium bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100 w-fit" title="{{ $resident->user->email }}">
                                    <i class="fa-solid fa-user-check"></i> Akun Aktif
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-slate-400 text-[9px] font-medium bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100 w-fit">
                                    <i class="fa-solid fa-user-slash"></i> Belum Ada Akun
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="openEditWargaModal({{ $resident->id }})" class="text-slate-400 hover:text-sky-500 p-1 transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                        <form id="delete-form-{{ $resident->id }}" action="{{ route('admin.residents.destroy', $resident->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('delete-form-{{ $resident->id }}')" class="text-slate-400 hover:text-rose-500 p-1 transition ml-1" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                
                @foreach($resident->familyMembers as $member)
                <tr class="bg-slate-50/30 hover:bg-slate-50 transition-colors border-l-4 border-emerald-400 hidden family-row-{{ $resident->id }}">
                    <td class="py-3 px-4 text-center">
                        <input type="checkbox" class="warga-checkbox rounded border-slate-300 text-sky-500 focus:ring-sky-500 cursor-pointer" onchange="toggleWargaItem()">
                    </td>
                    <td class="py-3 px-4 flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=f43f5e&color=fff" class="w-8 h-8 rounded-full ml-10">
                        <div>
                            <p class="font-medium text-slate-800">{{ $member->name }}</p>
                            <p class="text-[10px] text-slate-400">NIK: {{ $member->nik ?? 'Belum Diatur' }}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">{{ $member->relationship }}</span>
                    </td>
                    <td class="py-3 px-4 text-slate-500">
                        {{ $member->age ? $member->age . ' Thn' : 'N/A' }}
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <div class="w-16 bg-slate-100 h-1 rounded-full overflow-hidden">
                                <div class="h-full {{ $member->completeness > 80 ? 'bg-emerald-500' : ($member->completeness > 50 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $member->completeness }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">{{ round($member->completeness) }}%</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="text-slate-500 text-xs italic">Sama dengan KK</span>
                    </td>
                    <td class="py-3 px-4 text-slate-500 text-xs">-</td>
                    <td class="py-3 px-4">
                        <span class="text-[10px] text-slate-400 font-medium">Dependent</span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="flex items-center gap-1.5 text-emerald-600 text-[10px] font-medium">
                            <i class="fa-solid fa-circle text-[6px]"></i> Aktif
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="openEditWargaModal({{ $resident->id }}, 'family')" class="text-slate-400 hover:text-emerald-500 p-1 transition" title="Lengkapi Data Anggota"><i class="fa-solid fa-user-pen"></i></button>
                    </td>
                </tr>
                @endforeach
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-slate-400">Belum ada data warga.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Warga -->
<div id="modal-tambah-warga" class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-[2px] px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] transform scale-95 transition-all duration-200">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Tambah Data Warga Baru</h2>
            <button onclick="toggleModal('modal-tambah-warga', false)" class="text-slate-400 hover:text-rose-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form id="form-tambah-warga" action="{{ route('admin.residents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">NIK (16 Digit) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" id="add-nik" onkeyup="updateGeneratedPassword()" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none" maxlength="16">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="tel" name="contact" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Password Akun Login (Auto-Generate)</label>
                        <input type="text" id="add-generated-password" readonly class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-600 outline-none cursor-not-allowed font-mono" placeholder="Ketik NIK terlebih dahulu...">
                    </div>
                </div>
                
                <div class="p-3 bg-sky-50 rounded-xl border border-sky-100 flex gap-3 mt-4">
                    <i class="fa-solid fa-circle-info text-sky-500 mt-0.5"></i>
                    <p class="text-[11px] text-sky-800 leading-relaxed">Sistem akan otomatis membuatkan akun login menggunakan <b>NIK</b> sebagai Username. Kata sandi (password) akan di-generate otomatis dan ditampilkan setelah data disimpan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Blok <span class="text-rose-500">*</span></label>
                        <select name="block_id" id="add-block-id" required onchange="loadAvailableUnits(this.value, 'add-unit-no')" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                            <option value="">Pilih Blok</option>
                            @foreach($blocks as $block)
                            <option value="{{ $block->id }}">{{ $block->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">No. Unit <span class="text-rose-500">*</span></label>
                        <select name="unit_no" id="add-unit-no" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                            <option value="">Pilih Blok Dulu</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="hidden">
                        <input type="hidden" name="family_status" value="KK">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Status Hunian <span class="text-rose-500">*</span></label>
                        <select name="housing_status" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                            <option value="Owner">Pemilik (Owner)</option>
                            <option value="Tenant">Penyewa (Tenant)</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
            <button onclick="toggleModal('modal-tambah-warga', false)" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
            <button type="submit" form="form-tambah-warga" class="px-4 py-2 bg-sky-500 text-white rounded-lg text-sm font-medium shadow-sm hover:bg-blue-600 transition flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan Data</button>
        </div>
    </div>
</div>

<!-- Modal Edit Warga (The Hub) -->
<div id="modal-edit-warga" class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-[2px] px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh] transform scale-95 transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Edit Profil Warga</h2>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider" id="edit-warga-subtitle"></p>
                </div>
            </div>
            <button onclick="toggleModal('modal-edit-warga', false)" class="text-slate-400 hover:text-rose-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>

        <!-- Tab Navigation -->
        <div class="px-6 bg-white border-b border-slate-100 overflow-x-auto">
            <div class="flex gap-8">
                <button onclick="switchTab('personal')" id="tab-btn-personal" class="tab-btn py-4 text-sm font-bold text-emerald-600 border-b-2 border-emerald-600 transition-all whitespace-nowrap">
                    <i class="fa-solid fa-user-circle mr-2"></i>Informasi Data Diri
                </button>
                <button onclick="switchTab('family')" id="tab-btn-family" class="tab-btn py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all whitespace-nowrap">
                    <i class="fa-solid fa-users mr-2"></i>Anggota Keluarga
                </button>
                <button onclick="switchTab('document')" id="tab-btn-document" class="tab-btn py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all whitespace-nowrap">
                    <i class="fa-solid fa-file-invoice mr-2"></i>Dokumen
                </button>
                <button onclick="switchTab('vehicle')" id="tab-btn-vehicle" class="tab-btn py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all whitespace-nowrap">
                    <i class="fa-solid fa-car mr-2"></i>Kendaraan
                </button>
                <button onclick="switchTab('idcard')" id="tab-btn-idcard" class="tab-btn py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all whitespace-nowrap">
                    <i class="fa-solid fa-id-card mr-2"></i>ID Card
                </button>
            </div>
        </div>

        <!-- Tab Content Wrapper -->
        <div class="flex-1 overflow-y-auto bg-slate-50/30 p-6">
            
            <!-- TAB: PERSONAL INFO -->
            <div id="tab-content-personal" class="tab-content">
                <form id="form-edit-warga" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Nama Lengkap</label>
                            <input type="text" name="name" id="edit-name" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">NIK (16 Digit)</label>
                            <input type="text" name="nik" id="edit-nik" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">WhatsApp</label>
                            <input type="text" name="contact" id="edit-contact" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Email Aktif</label>
                            <input type="email" name="email" id="edit-email" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Password Akun Baru</label>
                            <input type="password" name="password" id="edit-password" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">ID Telegram</label>
                            <input type="text" name="telegram_id" id="edit-telegram" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Tempat Lahir</label>
                            <input type="text" name="birth_place" id="edit-birth-place" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Tanggal Lahir</label>
                            <input type="date" name="birth_date" id="edit-birth-date" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-100 pt-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Blok</label>
                            <select name="block_id" id="edit-block" onchange="loadAvailableUnits(this.value, 'edit-unit')" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                                @foreach($blocks as $block)
                                <option value="{{ $block->id }}">{{ $block->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">No. Unit</label>
                            <select name="unit_no" id="edit-unit" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                                <option value="">Pilih Blok Dulu</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Status Keluarga</label>
                            <select name="family_status" id="edit-family-status" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                                <option value="KK">Kepala Keluarga</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Status Hunian</label>
                            <select name="housing_status" id="edit-housing-status" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                                <option value="Owner">Pemilik (Owner)</option>
                                <option value="Tenant">Penyewa (Tenant)</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Status Akun</label>
                            <select name="status" id="edit-status" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i> Simpan Informasi Data Diri
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB: FAMILY MEMBERS -->
            <div id="tab-content-family" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-slate-700">Daftar Anggota Keluarga</h3>
                    <button onclick="showAddFamilyForm()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700"><i class="fa-solid fa-plus mr-1"></i> Tambah Anggota</button>
                </div>

                <!-- Add/Edit Family Form (Hidden by default) -->
                <div id="add-family-form" class="hidden bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 mb-4">
                    <input type="hidden" id="family-id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Nama Lengkap</label>
                            <input type="text" id="family-name" placeholder="Nama Anggota" class="w-full text-sm px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">NIK (16 Digit)</label>
                            <input type="text" id="family-nik" placeholder="NIK Anggota" class="w-full text-sm px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Tempat Lahir</label>
                            <input type="text" id="family-birth-place" placeholder="Kota Lahir" class="w-full text-sm px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Tanggal Lahir</label>
                            <input type="date" id="family-birth-date" class="w-full text-sm px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Hubungan</label>
                            <select id="family-relationship" class="w-full text-sm px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-emerald-500">
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Orang Tua">Orang Tua</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Status Verifikasi</label>
                            <select id="family-status" class="w-full text-sm px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-emerald-500">
                                <option value="Verifikasi">Verifikasi</option>
                                <option value="Belum Verifikasi">Belum Verifikasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button onclick="hideFamilyForm()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50">Batal</button>
                        <button onclick="saveFamilyMember()" id="btn-save-family" class="bg-emerald-600 text-white rounded-lg text-xs font-bold px-4 py-2 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all">Simpan Anggota</button>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-sm">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b">
                            <tr>
                                <th class="py-3 px-4">Nama / NIK</th>
                                <th class="py-3 px-4">Hubungan</th>
                                <th class="py-3 px-4">Usia</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="family-list" class="divide-y divide-slate-100">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: DOCUMENTS -->
            <div id="tab-content-document" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="doc-container" class="p-4 bg-white border border-slate-200 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-100">
                                <i class="fa-solid fa-file-pdf text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Kartu Tanda Penduduk (KTP)</p>
                                <p id="doc-filename" class="text-[9px] text-slate-500">Belum ada file</p>
                            </div>
                        </div>
                        <div class="flex gap-2" id="doc-actions">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                </div>
                <div class="mt-6 p-4 bg-amber-50 rounded-xl text-xs text-amber-700 leading-relaxed">
                    <i class="fa-solid fa-info-circle mr-1"></i> Gunakan tab <b>Informasi Data Diri</b> di atas jika Anda ingin mengganti atau memperbarui berkas dokumen.
                </div>
            </div>

            <!-- TAB: VEHICLES -->
            <div id="tab-content-vehicle" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-slate-700">Daftar Kendaraan</h3>
                    <button onclick="showAddVehicleForm()" class="text-xs font-bold text-sky-600 hover:text-sky-700"><i class="fa-solid fa-plus mr-1"></i> Tambah Kendaraan</button>
                </div>

                <div id="add-vehicle-form" class="hidden bg-sky-50/50 border border-sky-100 rounded-xl p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <input type="text" id="vehicle-plate" placeholder="B 1234 XYZ" class="text-sm px-3 py-2 border rounded-lg outline-none">
                        <select id="vehicle-type" class="text-sm px-3 py-2 border rounded-lg outline-none">
                            <option value="car">Mobil</option>
                            <option value="motor">Motor</option>
                        </select>
                        <input type="text" id="vehicle-desc" placeholder="Merk/Warna" class="text-sm px-3 py-2 border rounded-lg outline-none">
                        <button onclick="saveVehicle()" class="bg-sky-600 text-white rounded-lg text-sm font-bold px-4 py-2">Simpan</button>
                    </div>
                </div>

                <div id="vehicle-list" class="space-y-3">
                    <!-- Populated via JS -->
                </div>
            </div>

            <!-- TAB: ID CARD -->
            <div id="tab-content-idcard" class="tab-content hidden">
                <div class="flex flex-col items-center py-4">
                    <div id="id-card-preview" class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden shadow-xl border border-slate-800 flex flex-col justify-between" style="width: 85mm; height: 55mm;">
                        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <p class="text-[8px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-1">Resident Digital ID</p>
                                <h4 class="text-sm font-bold tracking-tight">The Tamar Village</h4>
                            </div>
                            <div class="bg-emerald-500/20 text-emerald-400 text-[7px] font-bold px-1.5 py-0.5 rounded border border-emerald-500/30 uppercase">Active</div>
                        </div>
                        <div class="flex items-center gap-4 relative z-10">
                            <img id="id-card-avatar" src="" class="w-12 h-12 rounded-lg border border-white/10 shadow-lg">
                            <div class="flex-1">
                                <h3 id="id-card-name" class="text-base font-bold leading-tight"></h3>
                                <p id="id-card-unit" class="text-[9px] text-emerald-400 font-mono mt-0.5"></p>
                            </div>
                            <div class="bg-white p-1.5 rounded-lg shadow-lg">
                                <i class="fa-solid fa-qrcode text-slate-900 text-3xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/5 relative z-10 flex justify-between items-end">
                            <p class="text-[7px] text-slate-500 uppercase font-medium">Valid until {{ date('M Y', strtotime('+1 year')) }}</p>
                            <p class="text-[8px] font-bold text-emerald-400">ADMIN VIEW</p>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-700 flex items-center gap-2"><i class="fa-solid fa-print"></i> Cetak ID Card</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
            <button type="button" onclick="toggleModal('modal-edit-warga', false)" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-100 transition">Tutup Portal</button>
        </div>
    </div>
</div>

</div>

<!-- ================= MODAL: REGISTER WARGA KELUAR ================= -->
<div id="modal-register-keluar" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-lg">Form Registrasi Warga Keluar</h3>
            <button onclick="toggleModal('modal-register-keluar', false)" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-400 hover:text-rose-50 hover:text-rose-500 shadow-sm border border-slate-100 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Nama Warga</label>
                <input type="text" id="reg-nama-warga" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-500 cursor-not-allowed outline-none transition-all" placeholder="Nama warga terpilih...">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Tanggal Keluar</label>
                <input type="date" id="reg-tgl-keluar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition-all" value="{{ date('Y-m-d') }}">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Status</label>
                <select id="reg-status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition-all cursor-pointer">
                    <option value="" disabled selected>Pilih Status</option>
                    <option value="Pindah">Pindah</option>
                    <option value="Meninggal">Meninggal</option>
                    <option value="Hilang">Hilang</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Alasan Keluar</label>
                <textarea id="reg-alasan-keluar" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition-all" placeholder="Alasan warga keluar/pindah..."></textarea>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
            <button onclick="toggleModal('modal-register-keluar', false)" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-200 transition-colors">Batal</button>
            <button onclick="submitRegisterKeluar()" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Registrasi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentResidentId = null;

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

    // CHECKBOX LOGIC
    function toggleAllWarga(master) {
        const checkboxes = document.querySelectorAll('.warga-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
        toggleWargaItem();
    }

    function toggleWargaItem() {
        const selected = document.querySelectorAll('.warga-checkbox:checked');
        const btnRegistrasi = document.getElementById('btn-registrasi-massal');
        
        if (selected.length > 0) {
            btnRegistrasi.classList.remove('hidden');
        } else {
            btnRegistrasi.classList.add('hidden');
        }

        // Update master checkbox state
        const master = document.getElementById('check-all-warga');
        const all = document.querySelectorAll('.warga-checkbox');
        if (selected.length === 0) {
            master.checked = false;
            master.indeterminate = false;
        } else if (selected.length === all.length) {
            master.checked = true;
            master.indeterminate = false;
        } else {
            master.checked = false;
            master.indeterminate = true;
        }
    }

    function openRegisterModal() {
        const selected = document.querySelectorAll('.warga-checkbox:checked');
        if (selected.length === 0) return;

        const inputNama = document.getElementById('reg-nama-warga');
        if (selected.length === 1) {
            const row = selected[0].closest('tr');
            const nama = row.querySelector('.font-medium.text-slate-800').innerText;
            inputNama.value = nama;
        } else {
            inputNama.value = `${selected.length} Warga Terpilih`;
        }

        document.getElementById('reg-status').value = '';
        document.getElementById('reg-alasan-keluar').value = '';
        toggleModal('modal-register-keluar', true);
    }

    function submitRegisterKeluar() {
        const selected = Array.from(document.querySelectorAll('.warga-checkbox:checked')).map(cb => cb.value);
        const exit_date = document.getElementById('reg-tgl-keluar').value;
        const exit_status = document.getElementById('reg-status').value;
        const exit_reason = document.getElementById('reg-alasan-keluar').value;

        if (!exit_date || !exit_status) {
            return alert('Harap isi tanggal dan status keluar.');
        }

        fetch('{{ route('admin.residents.register-out') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: selected, exit_date, exit_status, exit_reason })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-emerald-600', 'border-emerald-600');
            btn.classList.add('text-slate-400');
        });
        document.getElementById(`tab-btn-${tabId}`).classList.add('text-emerald-600', 'border-emerald-600');
        document.getElementById(`tab-btn-${tabId}`).classList.remove('text-slate-400');

        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        document.getElementById(`tab-content-${tabId}`).classList.remove('hidden');
    }

    function loadAvailableUnits(blockId, targetSelectId, selectedUnit = null) {
        const select = document.getElementById(targetSelectId);
        if (!blockId) {
            select.innerHTML = '<option value="">Pilih Blok Dulu</option>';
            return;
        }

        select.innerHTML = '<option value="">Memuat...</option>';
        fetch(`/admin/blocks/${blockId}/units`)
            .then(r => r.json())
            .then(data => {
                let options = '<option value="">Pilih No. Unit</option>';
                
                // data.units contains all units in the block
                data.units.forEach(u => {
                    const isAvailable = u.status === 'available';
                    const isCurrentUnit = selectedUnit && selectedUnit.toString() === u.no.toString();

                    if (isAvailable || isCurrentUnit) {
                        const label = isCurrentUnit ? `Unit ${u.no} (Unit Saat Ini)` : `Unit ${u.no} (Tersedia)`;
                        options += `<option value="${u.no}" ${isCurrentUnit ? 'selected' : ''}>${label}</option>`;
                    }
                });

                select.innerHTML = options;
            });
    }

    function openEditWargaModal(id, initialTab = 'personal') {
        currentResidentId = id;
        switchTab(initialTab);
        fetch(`/admin/residents/${id}`)
            .then(response => response.json())
            .then(data => {
                // Personal Tab
                document.getElementById('edit-warga-subtitle').innerText = `${data.name} • Blok ${data.block.name} / ${data.unit_no}`;
                document.getElementById('edit-name').value = data.name;
                document.getElementById('edit-nik').value = data.nik;
                document.getElementById('edit-contact').value = data.contact;
                document.getElementById('edit-email').value = data.email || '';
                document.getElementById('edit-telegram').value = data.telegram_id || '';
                document.getElementById('edit-birth-place').value = data.birth_place || '';
                document.getElementById('edit-birth-date').value = data.birth_date ? data.birth_date.split('T')[0] : '';
                document.getElementById('edit-block').value = data.block_id;
                
                // Load units for edit modal
                loadAvailableUnits(data.block_id, 'edit-unit', data.unit_no);
                
                document.getElementById('edit-family-status').value = data.family_status;
                document.getElementById('edit-housing-status').value = data.housing_status;
                document.getElementById('edit-status').value = data.status;
                document.getElementById('form-edit-warga').action = `/admin/residents/${id}`;

                // Family Tab
                renderFamilyList(data.family_members);

                // Document Tab
                const docFilename = document.getElementById('doc-filename');
                const docActions = document.getElementById('doc-actions');
                if (data.document) {
                    docFilename.innerText = data.document.split('/').pop();
                    docActions.innerHTML = `
                        <a href="/storage/${data.document}" target="_blank" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 transition-all flex items-center justify-center border border-slate-100"><i class="fa-solid fa-eye text-[10px]"></i></a>
                    `;
                } else {
                    docFilename.innerText = "Belum ada berkas terunggah.";
                    docActions.innerHTML = "";
                }

                // Vehicles Tab
                renderVehicleList(data.vehicles);

                // ID Card Tab
                document.getElementById('id-card-name').innerText = data.name;
                document.getElementById('id-card-unit').innerText = `Unit: Blok ${data.block.name} / ${data.unit_no}`;
                document.getElementById('id-card-avatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}&background=10b981&color=fff`;

                toggleModal('modal-edit-warga', true);
            });
    }

    // FAMILY MANAGEMENT
    function showAddFamilyForm() { 
        document.getElementById('family-id').value = '';
        document.getElementById('family-name').value = '';
        document.getElementById('family-nik').value = '';
        document.getElementById('family-birth-place').value = '';
        document.getElementById('family-birth-date').value = '';
        document.getElementById('family-relationship').value = 'Istri';
        document.getElementById('family-status').value = 'Verifikasi';
        document.getElementById('btn-save-family').innerText = 'Simpan Anggota';
        document.getElementById('add-family-form').classList.remove('hidden'); 
    }

    function hideFamilyForm() {
        document.getElementById('add-family-form').classList.add('hidden');
    }

    function editFamilyMember(id) {
        fetch(`/admin/family-members/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('family-id').value = data.id;
                document.getElementById('family-name').value = data.name;
                document.getElementById('family-nik').value = data.nik || '';
                document.getElementById('family-birth-place').value = data.birth_place || '';
                document.getElementById('family-birth-date').value = data.birth_date || '';
                document.getElementById('family-relationship').value = data.relationship;
                document.getElementById('family-status').value = data.status;
                document.getElementById('btn-save-family').innerText = 'Update Anggota';
                document.getElementById('add-family-form').classList.remove('hidden');
            });
    }

    function saveFamilyMember() {
        const id = document.getElementById('family-id').value;
        const name = document.getElementById('family-name').value;
        const nik = document.getElementById('family-nik').value;
        const birth_place = document.getElementById('family-birth-place').value;
        const birth_date = document.getElementById('family-birth-date').value;
        const relationship = document.getElementById('family-relationship').value;
        const status = document.getElementById('family-status').value;
        
        if (!name) return Toast.error('Error', 'Nama harus diisi');

        const url = id ? `/admin/family-members/${id}` : `{{ route('admin.family-members.store') }}`;
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ resident_id: currentResidentId, name, nik, birth_place, birth_date, relationship, status })
        }).then(r => r.json()).then(member => {
            Toast.success('Berhasil', id ? 'Data anggota diperbarui' : 'Anggota keluarga ditambahkan');
            hideFamilyForm();
            refreshResidentData();
        });
    }

    function deleteFamilyMember(id) {
        if (!confirm('Hapus anggota keluarga ini?')) return;
        fetch(`/admin/family-members/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            Toast.success('Dihapus', 'Anggota keluarga telah dihapus');
            refreshResidentData();
        });
    }

    function renderFamilyList(members) {
        const list = document.getElementById('family-list');
        list.innerHTML = members.map(m => `
            <tr>
                <td class="py-3 px-4">
                    <p class="font-medium text-slate-800">${m.name}</p>
                    <p class="text-[9px] text-slate-400">NIK: ${m.nik || '-'}</p>
                </td>
                <td class="py-3 px-4">${m.relationship}</td>
                <td class="py-3 px-4 text-slate-500">${m.age ? m.age + ' Thn' : '-'}</td>
                <td class="py-3 px-4">
                    <span class="px-2 py-0.5 rounded-full font-bold ${m.status === 'Verifikasi' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'}">
                        ${m.status}
                    </span>
                </td>
                <td class="py-3 px-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="editFamilyMember(${m.id})" class="text-slate-400 hover:text-emerald-500 transition-colors"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button onclick="deleteFamilyMember(${m.id})" class="text-slate-400 hover:text-rose-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('') || '<tr><td colspan="5" class="py-6 text-center text-slate-400 italic">Belum ada anggota keluarga terdaftar.</td></tr>';
    }

    // VEHICLE MANAGEMENT
    function showAddVehicleForm() { document.getElementById('add-vehicle-form').classList.toggle('hidden'); }

    function saveVehicle() {
        const plate_number = document.getElementById('vehicle-plate').value;
        const type = document.getElementById('vehicle-type').value;
        const brand_model_color = document.getElementById('vehicle-desc').value;
        if (!plate_number) return Toast.error('Error', 'Plat nomor harus diisi');

        fetch(`{{ route('admin.vehicles.store') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ resident_id: currentResidentId, plate_number, type, brand_model_color })
        }).then(r => r.json()).then(v => {
            Toast.success('Berhasil', 'Kendaraan ditambahkan');
            document.getElementById('vehicle-plate').value = '';
            document.getElementById('vehicle-desc').value = '';
            showAddVehicleForm();
            refreshResidentData();
        });
    }

    function deleteVehicle(id) {
        if (!confirm('Hapus kendaraan ini?')) return;
        fetch(`/admin/vehicles/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            Toast.success('Dihapus', 'Kendaraan telah dihapus');
            refreshResidentData();
        });
    }

    function renderVehicleList(vehicles) {
        const list = document.getElementById('vehicle-list');
        list.innerHTML = vehicles.map(v => `
            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-${v.type == 'car' ? 'car' : 'motorcycle'}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 uppercase">${v.plate_number}</p>
                        <p class="text-[10px] text-slate-500 font-medium">${v.brand_model_color || '-'}</p>
                    </div>
                </div>
                <button onclick="deleteVehicle(${v.id})" class="text-slate-300 hover:text-rose-500 transition-colors"><i class="fa-solid fa-trash-can text-sm"></i></button>
            </div>
        `).join('') || '<div class="text-center py-6 text-slate-400 italic text-xs">Belum ada kendaraan terdaftar.</div>';
    }

    function refreshResidentData() {
        fetch(`/admin/residents/${currentResidentId}`)
            .then(response => response.json())
            .then(data => {
                renderFamilyList(data.family_members);
                renderVehicleList(data.vehicles);
            });
    }

    function updateGeneratedPassword() {
        const nikInput = document.getElementById('add-nik').value;
        const passOutput = document.getElementById('add-generated-password');
        
        if (nikInput.length >= 4) {
            passOutput.value = 'tamar' + nikInput.slice(-4);
        } else {
            passOutput.value = '';
        }
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('fixed') && event.target.id.includes('modal')) {
            toggleModal(event.target.id, false);
        }
    }
</script>
@endpush
@endsection
