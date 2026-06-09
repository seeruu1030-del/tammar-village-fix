@extends('layouts.admin')

@section('title', 'Data Kendaraan | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Data Kendaraan Warga</h1>
        <p class="text-sm text-slate-500 mt-1">Registrasi kendaraan untuk integrasi akses Gerbang Pintar (Smart Gate).</p>
    </div>
    <button onclick="toggleModal('modal-tambah-kendaraan', true)" class="bg-sky-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Daftarkan Kendaraan
    </button>
</div>

<!-- Filter -->
<div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm mb-6 flex gap-3">
    <input type="text" placeholder="Cari plat nomor..." class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-sky-500 flex-1">
    <select class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-sky-500 w-40">
        <option>Semua Jenis</option>
        <option>Mobil</option>
        <option>Motor</option>
    </select>
</div>

<!-- Table Data Kendaraan -->
<div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold">
                    <th class="py-3 px-4">Plat Nomor</th>
                    <th class="py-3 px-4">Jenis & Merk</th>
                    <th class="py-3 px-4">Pemilik & Unit</th>
                    <th class="py-3 px-4">Status Akses</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($vehicles as $vehicle)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-800 text-base uppercase">{{ $vehicle->plate_number }}</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">ID: VHC-{{ str_pad($vehicle->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="py-3 px-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-{{ $vehicle->type == 'car' ? 'car-side' : 'motorcycle' }}"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-700">{{ ucfirst($vehicle->type == 'car' ? 'mobil' : 'motor') }}</p>
                            <p class="text-xs text-slate-500">{{ $vehicle->brand_model_color ?? '-' }}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <p class="font-medium text-slate-800">{{ $vehicle->resident->name }}</p>
                        <p class="text-xs text-slate-500">Blok {{ $vehicle->resident->block->name }} / {{ $vehicle->resident->unit_no }}</p>
                    </td>
                    <td class="py-3 px-4">
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-md">{{ $vehicle->status }}</span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="openEditVehicleModal({{ $vehicle->id }})" class="text-slate-400 hover:text-sky-500 p-1 transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                        <form id="delete-form-{{ $vehicle->id }}" action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('delete-form-{{ $vehicle->id }}')" class="text-slate-400 hover:text-rose-500 p-1 transition ml-1" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-slate-400">Belum ada data kendaraan terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kendaraan -->
<div id="modal-tambah-kendaraan" class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-[2px] px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col transform scale-95 transition-all duration-200">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Daftarkan Kendaraan Baru</h2>
            <button onclick="toggleModal('modal-tambah-kendaraan', false)" class="text-slate-400 hover:text-rose-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="p-6">
            <form id="form-tambah-kendaraan" action="{{ route('admin.vehicles.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Pemilik Kendaraan (Warga) <span class="text-rose-500">*</span></label>
                    <select name="resident_id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                        <option value="">Pilih Warga</option>
                        @foreach($residents as $resident)
                        <option value="{{ $resident->id }}">{{ $resident->name }} (Blok {{ $resident->block->name }} / {{ $resident->unit_no }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Plat Nomor <span class="text-rose-500">*</span></label>
                        <input type="text" name="plate_number" required placeholder="Contoh: B 1234 ABC" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Jenis Kendaraan <span class="text-rose-500">*</span></label>
                        <select name="type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                            <option value="car">Mobil</option>
                            <option value="motor">Motor</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Merk / Model / Warna</label>
                    <input type="text" name="brand_model_color" placeholder="Contoh: Toyota Avanza Hitam" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
            <button onclick="toggleModal('modal-tambah-kendaraan', false)" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
            <button type="submit" form="form-tambah-kendaraan" class="px-4 py-2 bg-sky-500 text-white rounded-lg text-sm font-medium shadow-sm hover:bg-blue-600 transition flex items-center gap-2"><i class="fa-solid fa-save"></i> Daftarkan</button>
        </div>
    </div>
</div>

<!-- Modal Edit Kendaraan -->
<div id="modal-edit-kendaraan" class="invisible opacity-0 fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-[2px] px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col transform scale-95 transition-all duration-200">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Edit Data Kendaraan</h2>
            <button onclick="toggleModal('modal-edit-kendaraan', false)" class="text-slate-400 hover:text-rose-500 transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="p-6">
            <form id="form-edit-kendaraan" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Pemilik Kendaraan (Warga)</label>
                    <select name="resident_id" id="edit-resident-id" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                        @foreach($residents as $resident)
                        <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Plat Nomor</label>
                        <input type="text" name="plate_number" id="edit-plate-number" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Jenis Kendaraan</label>
                        <select name="type" id="edit-type" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                            <option value="car">Mobil</option>
                            <option value="motor">Motor</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Merk / Model / Warna</label>
                    <input type="text" name="brand_model_color" id="edit-brand-model-color" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Status Akses</label>
                    <select name="status" id="edit-status" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:border-sky-500 outline-none">
                        <option value="TERDAFTAR">TERDAFTAR</option>
                        <option value="DITANGGUHKAN">DITANGGUHKAN</option>
                        <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
            <button onclick="toggleModal('modal-edit-kendaraan', false)" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition">Batal</button>
            <button type="submit" form="form-edit-kendaraan" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium shadow-sm hover:bg-emerald-700 transition flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
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

    function openEditVehicleModal(id) {
        fetch(`/admin/vehicles/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-resident-id').value = data.resident_id;
                document.getElementById('edit-plate-number').value = data.plate_number;
                document.getElementById('edit-type').value = data.type;
                document.getElementById('edit-brand-model-color').value = data.brand_model_color || '';
                document.getElementById('edit-status').value = data.status;
                
                document.getElementById('form-edit-kendaraan').action = `/admin/vehicles/${id}`;
                toggleModal('modal-edit-kendaraan', true);
            });
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('fixed') && event.target.id.includes('modal')) {
            toggleModal(event.target.id, false);
        }
    }
</script>
@endpush
@endsection
