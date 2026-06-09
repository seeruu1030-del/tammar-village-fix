@extends('layouts.admin')

@section('title', 'Denah & Blok | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Denah & Blok</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola tata letak perumahan dan ketersediaan unit di The Tamar Village.</p>
    </div>
    <button onclick="toggleModal('modal-tambah-blok', true)" class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Blok Baru
    </button>
</div>

<!-- Modern Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Left: List of Blocks -->
    <div class="lg:col-span-1 space-y-4">
        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Daftar Blok</h3>
        @foreach($blocks as $block)
        @php
            $colorMap = [
                'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200', 'ring' => 'ring-emerald-500/20'],
                'sky' => ['bg' => 'bg-sky-500', 'text' => 'text-sky-600', 'border' => 'border-sky-200', 'ring' => 'ring-sky-500/20'],
                'amber' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-600', 'border' => 'border-amber-200', 'ring' => 'ring-amber-500/20'],
                'rose' => ['bg' => 'bg-rose-500', 'text' => 'text-rose-600', 'border' => 'border-rose-200', 'ring' => 'ring-rose-500/20'],
                'indigo' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-600', 'border' => 'border-indigo-200', 'ring' => 'ring-indigo-500/20'],
            ];
            $c = $colorMap[$block->color] ?? $colorMap['sky'];
        @endphp
        <div onclick="showBlockMap({{ $block->id }}, '{{ $block->name }}')" class="group cursor-pointer bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-{{ $block->color }}-200 transition-all relative overflow-hidden block-card" id="block-card-{{ $block->id }}">
            <div class="absolute right-0 top-0 h-full w-1 {{ $c['bg'] }} opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $c['bg'] }} text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-{{ $block->color }}-500/20">
                    {{ $block->name[0] }}
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-slate-800 text-lg">Blok {{ $block->name }}</h4>
                    <p class="text-xs text-slate-500">{{ $block->description ?? 'Tidak ada deskripsi' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-slate-800 leading-none">{{ $block->total_units }}</p>
                    <p class="text-[10px] text-slate-400 uppercase font-bold mt-1">Unit</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                <div class="flex-1 mr-4">
                    @php
                        $occupancy = $block->total_units > 0 ? ($block->residents_count / $block->total_units) * 100 : 0;
                    @endphp
                    <div class="flex justify-between text-[10px] font-bold mb-1">
                        <span class="{{ $c['text'] }} uppercase">{{ round($occupancy) }}% Terisi</span>
                        <span class="text-slate-400">{{ $block->residents_count }}/{{ $block->total_units }}</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="{{ $c['bg'] }} h-full transition-all duration-1000" style="width: {{ $occupancy }}%"></div>
                    </div>
                </div>
                <div class="flex gap-1">
                    <button onclick="event.stopPropagation(); openEditBlokModal({{ $block->id }})" class="w-8 h-8 rounded-lg text-slate-400 hover:{{ $c['text'] }} hover:bg-{{ $block->color }}-50 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </button>
                    <form action="{{ route('admin.blocks.destroy', $block->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus blok ini?')">
                        @csrf
                        @method('DELETE')
                        <button onclick="event.stopPropagation()" type="submit" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Right: Map Preview -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden h-full flex flex-col min-h-[600px]">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center shadow-lg shadow-sky-500/20">
                        <i class="fa-solid fa-map-marked-alt text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800" id="map-title">Preview Denah Perumahan</h3>
                        <p class="text-xs text-slate-500" id="map-subtitle">Pilih blok untuk melihat detail unit</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-[10px] uppercase font-black tracking-wider">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-emerald-500"></span>
                        <span class="text-slate-600">Tersedia</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-blue-500"></span>
                        <span class="text-slate-600">Pemilik</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-amber-500"></span>
                        <span class="text-slate-600">Penyewa</span>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 p-8 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:24px_24px] overflow-auto flex items-center justify-center relative">
                <div id="unit-popup" class="hidden absolute z-20 bg-white border border-slate-100 shadow-2xl rounded-2xl p-4 min-w-[200px] pointer-events-none transform -translate-y-full mt-[-10px] transition-all duration-200">
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 rotate-45 w-3 h-3 bg-white border-r border-b border-slate-100"></div>
                    <div id="popup-content"></div>
                </div>
                <div id="map-container" class="w-full h-full flex items-center justify-center">
                    <div class="text-center space-y-4 max-w-xs animate-pulse">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto">
                            <i class="fa-solid fa-mouse-pointer text-3xl text-slate-300"></i>
                        </div>
                        <h4 class="font-bold text-slate-400">Silakan pilih blok di sebelah kiri untuk melihat pemetaan unit secara detail.</h4>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-home text-sky-400"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Total: <span id="stat-total">-</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check-circle text-emerald-400"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Tersedia: <span id="stat-available">-</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user-lock text-slate-400"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Terisi: <span id="stat-taken">-</span></span>
                    </div>
                </div>
                <button onclick="location.reload()" class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-arrows-rotate"></i> REFRESH MAP
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL: TAMBAH/EDIT BLOK ================= -->
<div id="modal-tambah-blok" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="relative h-32 bg-gradient-to-br from-slate-900 via-slate-800 to-sky-900 flex items-center px-8 overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="relative z-10">
                <h3 class="font-black text-white text-2xl" id="modal-title">Manajemen Blok Baru</h3>
                <p class="text-sky-300 text-sm font-medium">Konfigurasi area dan kapasitas unit perumahan.</p>
            </div>
            <button onclick="toggleModal('modal-tambah-blok', false)" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-2xl bg-white/10 text-white hover:bg-rose-500 transition-all backdrop-blur-md">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-blok" action="{{ route('admin.blocks.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div id="method-field"></div>
            <div class="space-y-4">
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-sky-500 transition-colors">Nama / Kode Blok</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-sky-500 transition-colors">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <input type="text" name="name" id="blok-name" required placeholder="Contoh: A, B, Emerald, dll." class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-12 py-4 text-sm font-bold text-slate-800 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 outline-none transition-all placeholder:text-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-sky-500 transition-colors">Jumlah Unit</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-sky-500 transition-colors">
                                <i class="fa-solid fa-hashtag"></i>
                            </div>
                            <input type="number" name="total_units" id="blok-units" required min="1" placeholder="50" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-12 py-4 text-sm font-bold text-slate-800 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 outline-none transition-all placeholder:text-slate-300">
                        </div>
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-sky-500 transition-colors">Warna Label</label>
                        <input type="hidden" name="color" id="blok-color-input" value="sky">
                        <div class="flex gap-2">
                            <div onclick="selectColor('emerald', this)" class="color-option w-10 h-10 rounded-full bg-emerald-500 border-4 border-white shadow-md cursor-pointer hover:scale-110 transition-all ring-offset-2"></div>
                            <div onclick="selectColor('sky', this)" class="color-option w-10 h-10 rounded-full bg-sky-500 border-4 border-white shadow-md cursor-pointer hover:scale-110 transition-all ring-offset-2 ring-2 ring-sky-500/50"></div>
                            <div onclick="selectColor('amber', this)" class="color-option w-10 h-10 rounded-full bg-amber-500 border-4 border-white shadow-md cursor-pointer hover:scale-110 transition-all ring-offset-2"></div>
                            <div onclick="selectColor('rose', this)" class="color-option w-10 h-10 rounded-full bg-rose-500 border-4 border-white shadow-md cursor-pointer hover:scale-110 transition-all ring-offset-2"></div>
                            <div onclick="selectColor('indigo', this)" class="color-option w-10 h-10 rounded-full bg-indigo-500 border-4 border-white shadow-md cursor-pointer hover:scale-110 transition-all ring-offset-2"></div>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 group-focus-within:text-sky-500 transition-colors">Keterangan Area</label>
                    <textarea name="description" id="blok-desc" rows="3" placeholder="Jelaskan detail lokasi atau area blok ini..." class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-sm font-medium text-slate-800 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-500/10 outline-none transition-all placeholder:text-slate-300"></textarea>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-sky-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:shadow-sky-500/30 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                    <i class="fa-solid fa-paper-plane"></i> <span id="btn-submit-text">Verifikasi & Simpan Blok</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-load first block if exists
    document.addEventListener('DOMContentLoaded', () => {
        const firstBlock = document.querySelector('[onclick^="showBlockMap"]');
        if (firstBlock) {
            firstBlock.click();
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

    function openEditBlokModal(id) {
        fetch(`/admin/blocks/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('modal-title').innerText = 'Edit Konfigurasi Blok';
                document.getElementById('btn-submit-text').innerText = 'Update Perubahan Blok';
                document.getElementById('blok-name').value = data.name;
                document.getElementById('blok-units').value = data.total_units;
                document.getElementById('blok-desc').value = data.description || '';
                document.getElementById('form-blok').action = `/admin/blocks/${id}`;
                document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                // Set color
                const color = data.color || 'sky';
                const colorOption = document.querySelector(`.color-option[onclick*="'${color}'"]`);
                if (colorOption) selectColor(color, colorOption);

                toggleModal('modal-tambah-blok', true);
            });
    }

    let popupTimeout = null;

    function showPopup(event, data) {
        if (popupTimeout) {
            clearTimeout(popupTimeout);
            popupTimeout = null;
        }

        const popup = document.getElementById('unit-popup');
        const content = document.getElementById('popup-content');
        const rect = event.currentTarget.getBoundingClientRect();
        const containerRect = popup.parentElement.getBoundingClientRect();

        let html = '';
        if (data.status === 'available') {
            html = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Unit ${data.no}</p>
                        <p class="text-sm font-bold text-slate-800">Tersedia</p>
                    </div>
                </div>
            `;
        } else {
            const statusColor = data.housing_status === 'Owner' ? 'blue' : 'amber';
            const statusLabel = data.housing_status === 'Owner' ? 'Pemilik' : 'Penyewa';
            html = `
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit ${data.no}</span>
                        <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase bg-${statusColor}-50 text-${statusColor}-600 border border-${statusColor}-100">
                            ${statusLabel}
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(data.resident_name)}&background=${statusColor === 'blue' ? '3b82f6' : 'f59e0b'}&color=fff" class="w-10 h-10 rounded-xl shadow-md border-2 border-white">
                        <div class="max-w-[120px]">
                            <p class="text-[9px] font-bold text-slate-400 leading-none mb-1 uppercase">Penghuni</p>
                            <p class="text-sm font-black text-slate-800 leading-tight truncate">${data.resident_name}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        content.innerHTML = html;
        
        // Position popup above the element
        const x = rect.left - containerRect.left + (rect.width / 2);
        const y = rect.top - containerRect.top;
        
        popup.style.left = `${x}px`;
        popup.style.top = `${y}px`;
        
        // Immediate show for responsiveness
        popup.classList.remove('hidden');
        // Trigger reflow for animation
        popup.offsetHeight; 
        popup.style.transform = `translate(-50%, -100%) scale(1)`;
        popup.style.opacity = '1';
    }

    function hidePopup() {
        const popup = document.getElementById('unit-popup');
        popup.style.transform = `translate(-50%, -90%) scale(0.95)`;
        popup.style.opacity = '0';
        
        // Store timeout so it can be cancelled if mouse enters another unit quickly
        popupTimeout = setTimeout(() => {
            popup.classList.add('hidden');
            popupTimeout = null;
        }, 150);
    }

    function selectColor(color, el) {
        // Update hidden input
        document.getElementById('blok-color-input').value = color;
        
        // Update UI selection
        document.querySelectorAll('.color-option').forEach(opt => {
            opt.classList.remove('ring-2', 'ring-offset-2', 'ring-emerald-500/50', 'ring-sky-500/50', 'ring-amber-500/50', 'ring-rose-500/50', 'ring-indigo-500/50');
        });
        
        const colorClasses = {
            'emerald': 'ring-emerald-500/50',
            'sky': 'ring-sky-500/50',
            'amber': 'ring-amber-500/50',
            'rose': 'ring-rose-500/50',
            'indigo': 'ring-indigo-500/50'
        };
        
        el.classList.add('ring-2', 'ring-offset-2', colorClasses[color]);
    }

    // Reset modal on add
    window.addEventListener('click', (e) => {
        if(e.target.innerText && e.target.innerText.includes('Tambah Blok Baru')) {
            document.getElementById('modal-title').innerText = 'Manajemen Blok Baru';
            document.getElementById('btn-submit-text').innerText = 'Verifikasi & Simpan Blok';
            document.getElementById('blok-name').value = '';
            document.getElementById('blok-units').value = '';
            document.getElementById('blok-desc').value = '';
            document.getElementById('form-blok').action = "{{ route('admin.blocks.store') }}";
            document.getElementById('method-field').innerHTML = '';
        }
    });

    function showBlockMap(id, name) {
        const container = document.getElementById('map-container');
        const title = document.getElementById('map-title');
        const subtitle = document.getElementById('map-subtitle');
        
        // Highlight active card
        document.querySelectorAll('[onclick^="showBlockMap"]').forEach(el => {
            el.classList.remove('border-sky-500', 'ring-2', 'ring-sky-500/20');
        });
        const clickedCard = event.currentTarget;
        if (clickedCard && clickedCard.classList) {
            clickedCard.classList.add('border-sky-500', 'ring-2', 'ring-sky-500/20');
        }

        container.innerHTML = `
            <div class="flex flex-col items-center gap-3">
                <div class="animate-spin text-sky-500 text-4xl"><i class="fa-solid fa-circle-notch"></i></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat Denah Blok ${name}...</p>
            </div>
        `;
        title.innerText = `Peta Pemetaan Blok ${name}`;
        
        fetch(`/admin/blocks/${id}/units`)
            .then(r => {
                if (!r.ok) throw new Error('Gagal mengambil data');
                return r.json();
            })
            .then(data => {
                subtitle.innerText = `Detail Ketersediaan: ${data.total} Unit Terdaftar`;
                document.getElementById('stat-total').innerText = data.total;
                document.getElementById('stat-available').innerText = data.available_count;
                document.getElementById('stat-taken').innerText = data.taken_count;

                let gridHtml = `<div class="grid grid-cols-5 sm:grid-cols-10 gap-3 w-full max-w-2xl p-4 bg-white/50 rounded-3xl border border-slate-100/50 shadow-inner">`;
                
                data.units.forEach(u => {
                    let colorClass = 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:scale-110 hover:rotate-3';
                    let icon = `<span class="text-xs font-black">${u.no}</span>`;
                    let popupInfo = JSON.stringify(u).replace(/"/g, '&quot;');

                    if (u.status === 'taken') {
                        if (u.housing_status === 'Owner') {
                            colorClass = 'bg-blue-500 text-white shadow-lg shadow-blue-500/20 hover:scale-110';
                        } else {
                            colorClass = 'bg-amber-500 text-white shadow-lg shadow-amber-500/20 hover:scale-110';
                        }
                        icon = '<i class="fa-solid fa-house-user text-[10px]"></i>';
                    }
                    
                    gridHtml += `
                        <div class="aspect-square rounded-2xl flex flex-col items-center justify-center transition-all duration-300 border-2 border-white cursor-pointer ${colorClass}" 
                             onmouseenter="showPopup(event, ${popupInfo})" 
                             onmouseleave="hidePopup()">
                            ${icon}
                        </div>
                    `;
                });
                
                gridHtml += `</div>`;
                container.innerHTML = gridHtml;
            })
            .catch(err => {
                container.innerHTML = `
                    <div class="text-center text-rose-500">
                        <i class="fa-solid fa-circle-exclamation text-4xl mb-2"></i>
                        <p class="font-bold">Gagal memuat denah.</p>
                        <p class="text-xs text-slate-400">${err.message}</p>
                    </div>
                `;
            });
    }
</script>
@endpush
@endsection
