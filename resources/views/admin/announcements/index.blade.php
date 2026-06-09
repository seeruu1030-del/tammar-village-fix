@extends('layouts.admin')

@section('title', 'Papan Pengumuman Digital | T-Link Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Papan Pengumuman Digital</h1>
        <p class="text-sm text-slate-500 mt-1">Siarkan surat edaran, kegiatan, atau agenda perumahan ke aplikasi seluruh warga.</p>
    </div>
    <button onclick="toggleModal('modal-tambah-pengumuman', true)" class="bg-sky-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg transition-all flex items-center gap-2">
        <i class="fa-solid fa-pen-nib"></i> Tulis Pengumuman Baru
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($announcements as $ann)
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition group">
        <div class="h-32 bg-slate-800 flex items-center justify-center text-white relative overflow-hidden">
            @if($ann->image_path)
                <img src="{{ asset('storage/' . $ann->image_path) }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
            @endif
            <i class="fa-solid {{ $ann->icon }} text-4xl opacity-50 relative z-10"></i>
        </div>
        <div class="p-5">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded uppercase border border-emerald-100">{{ $ann->category }}</span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $ann->created_at->format('d M Y') }}</span>
            </div>
            <h3 class="font-bold text-slate-800 text-lg mb-2 line-clamp-1 group-hover:text-sky-600 transition-colors">{{ $ann->title }}</h3>
            <p class="text-sm text-slate-600 mb-4 line-clamp-2 leading-relaxed">{{ $ann->content }}</p>
            
            <div class="flex justify-between items-center border-t border-slate-50 pt-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><i class="fa-regular fa-eye mr-1 text-slate-300"></i> Dibaca: {{ $ann->view_count }} Warga</span>
                <div class="flex gap-1">
                    <button onclick="openEditAnnouncementModal({{ $ann->id }})" class="w-8 h-8 rounded-lg text-slate-300 hover:text-sky-500 hover:bg-sky-50 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <form action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all flex items-center justify-center">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-slate-200 flex flex-col items-center justify-center text-center px-4">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
            <i class="fa-solid fa-bullhorn text-3xl text-slate-200"></i>
        </div>
        <h4 class="font-bold text-slate-400">Belum ada pengumuman.</h4>
        <p class="text-xs text-slate-400 mt-1">Klik tombol "Tulis Pengumuman Baru" untuk menyiarkan informasi ke warga.</p>
    </div>
    @endforelse
</div>

<!-- ================= MODAL: TAMBAH/EDIT PENGUMUMAN ================= -->
<div id="modal-tambah-pengumuman" class="invisible opacity-0 fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-sm px-4 transition-all duration-200">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-all duration-200">
        <div class="relative h-32 bg-gradient-to-br from-slate-900 via-slate-800 to-sky-900 flex items-center px-8 overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="relative z-10">
                <h3 class="font-black text-white text-2xl" id="modal-title">Tulis Pengumuman</h3>
                <p class="text-sky-300 text-sm font-medium">Informasikan agenda perumahan kepada seluruh warga.</p>
            </div>
            <button onclick="toggleModal('modal-tambah-pengumuman', false)" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-2xl bg-white/10 text-white hover:bg-rose-500 transition-all backdrop-blur-md">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-announcement" action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
            @csrf
            <div id="method-field"></div>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                        <select name="category" id="ann-category" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-800 focus:border-sky-500 outline-none transition-all cursor-pointer">
                            <option value="Informasi Penting">Informasi Penting</option>
                            <option value="Kegiatan Sosial">Kegiatan Sosial</option>
                            <option value="Keamanan">Keamanan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Icon Visual</label>
                        <select name="icon" id="ann-icon" required class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-800 focus:border-sky-500 outline-none transition-all cursor-pointer">
                            <option value="fa-bullhorn">📢 Bullhorn</option>
                            <option value="fa-calendar-check">📅 Calendar</option>
                            <option value="fa-broom">🧹 Kerja Bakti</option>
                            <option value="fa-shield-halved">🛡️ Keamanan</option>
                            <option value="fa-file-invoice">📄 IPL / Keuangan</option>
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Judul Pengumuman</label>
                    <input type="text" name="title" id="ann-title" required placeholder="Contoh: Kerja Bakti Rutin Minggu Ini" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 text-sm font-bold text-slate-800 focus:border-sky-500 outline-none transition-all">
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Isi Pengumuman</label>
                    <textarea name="content" id="ann-content" rows="4" required placeholder="Tuliskan detail pengumuman di sini..." class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-3 text-sm font-medium text-slate-800 focus:border-sky-500 outline-none transition-all"></textarea>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Foto / Gambar (Opsional)</label>
                    <input type="file" name="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-sky-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:shadow-sky-500/30 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                    <i class="fa-solid fa-paper-plane"></i> <span id="btn-submit-text">Terbitkan Pengumuman</span>
                </button>
            </div>
        </form>
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

    function openEditAnnouncementModal(id) {
        fetch(`/admin/announcements/${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('modal-title').innerText = 'Edit Pengumuman';
                document.getElementById('btn-submit-text').innerText = 'Simpan Perubahan';
                document.getElementById('ann-category').value = data.category;
                document.getElementById('ann-icon').value = data.icon;
                document.getElementById('ann-title').value = data.title;
                document.getElementById('ann-content').value = data.content;
                document.getElementById('form-announcement').action = `/admin/announcements/${id}`;
                document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                toggleModal('modal-tambah-pengumuman', true);
            });
    }

    // Reset modal on add
    window.addEventListener('click', (e) => {
        if(e.target.innerText && e.target.innerText.includes('Tulis Pengumuman Baru')) {
            document.getElementById('modal-title').innerText = 'Tulis Pengumuman';
            document.getElementById('btn-submit-text').innerText = 'Terbitkan Pengumuman';
            document.getElementById('ann-category').value = 'Informasi Penting';
            document.getElementById('ann-icon').value = 'fa-bullhorn';
            document.getElementById('ann-title').value = '';
            document.getElementById('ann-content').value = '';
            document.getElementById('form-announcement').action = "{{ route('admin.announcements.store') }}";
            document.getElementById('method-field').innerHTML = '';
        }
    });
</script>
@endpush
@endsection
