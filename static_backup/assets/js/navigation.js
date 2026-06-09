// ==========================================
// NAVIGATION & UI LOGIC FOR PORTAL ADMIN
// ==========================================

// Toggle Accordion Dropdown (Sistem Accordion Tunggal)
function toggleDropdown(id, arrowId) {
    const dropdown = document.getElementById(id);
    const arrow = document.getElementById(arrowId);
    
    if (!dropdown || !arrow) return;

    const isClosed = dropdown.classList.contains('max-h-0');
    
    // Daftar semua sub-menu dropdown di sidebar
    const allDropdowns = [
        { id: 'dropdown-warga', arrowId: 'arrow-warga' },
        { id: 'dropdown-keuangan', arrowId: 'arrow-keuangan' },
        { id: 'dropdown-tabungan', arrowId: 'arrow-tabungan' }
    ];
    
    if (isClosed) {
        // Tutup menu lain
        allDropdowns.forEach(item => {
            const el = document.getElementById(item.id);
            const arr = document.getElementById(item.arrowId);
            if (el && arr) {
                el.classList.add('max-h-0', 'opacity-0');
                el.classList.remove('max-h-60', 'opacity-100', 'py-1');
                arr.classList.remove('rotate-180');
            }
        });
        
        // Buka menu target
        dropdown.classList.remove('max-h-0', 'opacity-0');
        dropdown.classList.add('max-h-60', 'opacity-100', 'py-1');
        arrow.classList.add('rotate-180');
    } else {
        dropdown.classList.add('max-h-0', 'opacity-0');
        dropdown.classList.remove('max-h-60', 'opacity-100', 'py-1');
        arrow.classList.remove('rotate-180');
    }
}

// FUNGSI SWITCH VIEW (Navigasi Antar Halaman)
function switchView(viewName) {
    const views = [
        'dashboard', 'direktori', 'persetujuan', 'kendaraan', 'denah', 'id-card', 'warga-nonaktif',
        'ringkasan-keuangan', 'iuran', 'verifikasi-pembayaran', 'laporan-kas', 'buku-kas',
        'tabungan-program', 'tabungan-rincian', 'tabungan-setoran', 'tabungan-pencairan',
        'tamu', 'keluhan', 'pengumuman', 'virtual-account', 'bni-config', 'telegram-config', 'pengaturan', 'backup'
    ];
    
    views.forEach(v => {
        const viewEl = document.getElementById('view-' + v);
        const navEl = document.getElementById('nav-' + v);
        
        // Toggle Visibility
        if (viewEl) {
            if (v === viewName) {
                viewEl.classList.remove('hidden');
            } else {
                viewEl.classList.add('hidden');
            }
        }
        
        // Set Active State Sidebar
        if (navEl) {
            if (v === viewName) {
                applyActiveStyle(navEl, v);
            } else {
                resetInactiveStyle(navEl, v);
            }
        }
    });

    // Special behavior for dashboard (charts resize)
    if (viewName === 'dashboard') {
        window.dispatchEvent(new Event('resize')); 
    }

    // Scroll top on change
    const scrollArea = document.querySelector('main > div.flex-1');
    if (scrollArea) scrollArea.scrollTop = 0;
}

// Helper: Styling Menu Aktif
function applyActiveStyle(el, type) {
    if (['dashboard', 'tamu', 'pengumuman'].includes(type)) {
        el.classList.add('bg-sky-500/10', 'text-sky-500', 'border-r-2', 'border-sky-500');
        el.classList.remove('hover:bg-slate-800', 'hover:text-white', 'text-slate-400');
    } else if (['tabungan-program', 'tabungan-rincian', 'tabungan-setoran', 'tabungan-pencairan'].includes(type)) {
        el.classList.add('text-white', 'font-semibold');
        el.classList.remove('text-slate-400');
    } else if (type === 'keluhan' || type === 'warga-nonaktif') {
        const colorClass = type === 'keluhan' ? 'rose' : 'red';
        el.classList.add(`bg-${colorClass}-500/10`, `text-${colorClass}-500`, 'border-r-2', `border-${colorClass}-500`);
        el.classList.remove('hover:bg-slate-800', 'hover:text-white', 'text-slate-400', 'text-red-400');
    } else if (['pengaturan', 'virtual-account', 'bni-config', 'buku-kas', 'ringkasan-keuangan', 'backup'].includes(type)) {
        el.classList.add('bg-slate-700', 'text-white', 'border-r-2', 'border-sky-500');
        el.classList.remove('hover:bg-slate-800', 'text-slate-400');
    } else if (type === 'id-card') {
        el.classList.add('text-white', 'font-bold');
        el.classList.remove('text-slate-400');
    } else {
        el.classList.add('text-white', 'font-semibold');
        el.classList.remove('text-slate-400');
    }
}

// Helper: Reset Styling Menu
function resetInactiveStyle(el, type) {
    if (['dashboard', 'tamu', 'pengumuman', 'keluhan'].includes(type)) {
        el.classList.remove('bg-sky-500/10', 'text-sky-500', 'border-r-2', 'border-sky-500', 'bg-rose-500/10', 'text-rose-500', 'border-rose-500');
        el.classList.add('hover:bg-slate-800', 'hover:text-white', 'text-slate-400');
    } else if (type === 'warga-nonaktif') {
        el.classList.remove('bg-red-500/10', 'text-white', 'font-bold', 'border-r-2', 'border-red-500');
        el.classList.add('text-red-400', 'hover:text-red-300');
    } else if (['pengaturan', 'virtual-account', 'bni-config', 'buku-kas', 'ringkasan-keuangan', 'backup'].includes(type)) {
        el.classList.remove('bg-slate-700', 'text-white', 'border-r-2', 'border-sky-500', 'border-slate-400', 'border-amber-500', 'border-emerald-500');
        el.classList.add('hover:bg-slate-800', 'text-slate-400');
    } else {
        el.classList.remove('text-white', 'font-semibold', 'font-bold', 'bg-red-500/10', 'border-r-2', 'border-red-400', 'bg-sky-500/10', 'text-sky-500', 'border-sky-500');
        el.classList.add('text-slate-400');
    }
}

// Toggle Sidebar (Desktop Mini vs Mobile Slide)
function toggleSidebar() {
    if (window.innerWidth < 768) {
        const sidebar = document.querySelector('aside');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('absolute');
            sidebar.classList.toggle('z-50');
            sidebar.classList.toggle('h-full');
        }
    } else {
        document.body.classList.toggle('sidebar-collapsed');
    }
    
    // Trigger charts refresh
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 300);
}

function toggleMobileSidebar() {
    toggleSidebar();
}

// DROPDOWN PROFILE LOGIC
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

// Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('profileDropdown');
    const btn = document.querySelector('button[onclick="toggleProfileDropdown()"]');
    
    if (dropdown && !dropdown.contains(e.target) && btn && !btn.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

// ==========================================
// EMERGENCY & PANIC BUTTON LOGIC (CENTRALIZED)
// ==========================================

const emergencyChannel = new BroadcastChannel('tlink_emergency_channel');

// Voice Alert System (Text-to-Speech)
let voiceLoopInterval = null;

function playVoiceAlert(text, type) {
    if (voiceLoopInterval) return; // Jangan tumpuk suara

    const speak = () => {
        const msg = new SpeechSynthesisUtterance(text);
        msg.lang = 'id-ID'; // Bahasa Indonesia
        
        if (type === 'EMERGENCY') {
            msg.pitch = 1.4;
            msg.rate = 1.2; // Sangat cepat & melengking (URGENT)
            msg.volume = 1;
        } else if (type === 'PATROL') {
            msg.pitch = 1.1;
            msg.rate = 1.0; // Suara tegas & otoriter (STANDAR OPS)
            msg.volume = 0.9;
        } else if (type === 'SUSPICIOUS') {
            msg.pitch = 0.8;
            msg.rate = 0.85; // Nada rendah & pelan (SERIUS/WASPADA)
            msg.volume = 1;
        } else {
            msg.pitch = 1.0;
            msg.rate = 0.9; // Tenang & informatif (BANTUAN UMUM)
            msg.volume = 0.8;
        }
        
        window.speechSynthesis.speak(msg);
    };

    // Mainkan pertama kali
    speak();
    
    // Ulangi setiap 5 detik selama status aktif
    voiceLoopInterval = setInterval(speak, 5000);
}

/**
 * Memicu alarm darurat atau bantuan
 */
function triggerEmergency(residentName, unit, type = 'EMERGENCY') {
    const emergencyData = {
        name: residentName,
        unit: unit,
        time: new Date().toLocaleTimeString(),
        status: 'ACTIVE',
        type: type 
    };
    
    localStorage.setItem('tlink_emergency', JSON.stringify(emergencyData));
    emergencyChannel.postMessage(emergencyData);
    
    const msg = type === 'EMERGENCY' 
        ? '🚨 PERINGATAN BAHAYA TERKIRIM! Petugas segera menuju lokasi.' 
        : 'ℹ️ PERMINTAAN BANTUAN TERKIRIM! Petugas akan segera merespon.';
    
    alert(msg);
}

// Global listener
emergencyChannel.onmessage = (event) => {
    const emergency = event.data;
    if (emergency.status === 'ACTIVE') {
        handleGlobalEmergency(emergency);
    } else {
        stopAllEmergencyAlerts();
    }
};

function handleGlobalEmergency(data) {
    const alertBanner = document.getElementById('emergency-alert-banner') || 
                        document.getElementById('login-emergency-overlay') || 
                        document.getElementById('security-emergency-banner');
    
    if (alertBanner) {
        alertBanner.classList.remove('hidden');
        
        const standbyStatus = document.getElementById('standby-status');
        if (standbyStatus) standbyStatus.classList.add('hidden');
        
        // Reset warna banner sebelumnya
        alertBanner.classList.remove('bg-rose-600', 'bg-amber-500', 'bg-sky-600', 'bg-indigo-600', 'bg-blue-600');
        
        // Penyesuaian Warna Banner sesuai Tipe (Sinkron dengan Portal Warga)
        if (data.type === 'EMERGENCY') {
            alertBanner.classList.add('bg-rose-600'); // Merah - Panic
        } else if (data.type === 'PATROL') {
            alertBanner.classList.add('bg-sky-600'); // Biru - Patroli
        } else if (data.type === 'SUSPICIOUS') {
            alertBanner.classList.add('bg-indigo-600'); // Ungu - Tamu Mencurigakan
        } else if (data.type === 'ASSISTANCE') {
            alertBanner.classList.add('bg-amber-500'); // Kuning - Bantuan Umum
        } else {
            alertBanner.classList.add('bg-blue-600'); // Default Biru
        }

        const desc = document.getElementById('emergency-desc') || alertBanner.querySelector('p');
        const title = alertBanner.querySelector('h2') || alertBanner.querySelector('h3') || alertBanner.querySelector('h1');
        
        if (title) {
            if (data.type === 'EMERGENCY') title.innerText = 'PERINGATAN BAHAYA';
            else if (data.type === 'PATROL') title.innerText = 'PERINTAH PATROLI';
            else if (data.type === 'SUSPICIOUS') title.innerText = 'LAPORAN TAMU MENCURIGAKAN';
            else if (data.type === 'SECURITY_HELP') title.innerText = 'BANTUAN KEAMANAN';
            else title.innerText = 'PERINGATAN BUTUH BANTUAN';
            title.classList.remove('italic'); 
        }

        let actionText = '';
        if (data.type === 'EMERGENCY') actionText = 'memicu tombol panik';
        else if (data.type === 'PATROL') actionText = 'meminta pengecekan patroli';
        else if (data.type === 'SUSPICIOUS') actionText = 'melaporkan adanya tamu mencurigakan';
        else actionText = 'membutuhkan bantuan darurat';

        if (desc) desc.innerText = `${data.unit} (${data.name}) ${actionText}.`;

        const timeEl = document.getElementById('emergency-time');
        const nameEl = document.getElementById('emergency-name');
        if (timeEl) timeEl.innerText = data.time;
        if (nameEl) nameEl.innerText = data.name;

        // PUTAR VOICE ALERT (Suara Orang)
        let voiceText = '';
        if (data.type === 'EMERGENCY') {
            voiceText = `Peringatan Bahaya! Peringatan Bahaya! Unit ${data.unit}, warga bernama ${data.name} membutuhkan bantuan segera.`;
        } else if (data.type === 'PATROL') {
            voiceText = `Permintaan Patroli. Unit ${data.unit}, warga bernama ${data.name} meminta petugas mengecek area unit.`;
        } else if (data.type === 'SUSPICIOUS') {
            voiceText = `Laporan Keamanan. Unit ${data.unit}, warga bernama ${data.name} melaporkan adanya tamu mencurigakan.`;
        } else {
            voiceText = `Peringatan Bantuan. Warga di Unit ${data.unit}, atas nama ${data.name} memerlukan asistensi petugas.`;
        }
        
        playVoiceAlert(voiceText, data.type);
        sendSystemPush(data.name, data.unit, data.type);
    }
}

function stopAllEmergencyAlerts() {
    // 1. Matikan Semua Suara & Speech
    window.speechSynthesis.cancel();
    if (voiceLoopInterval) {
        clearInterval(voiceLoopInterval);
        voiceLoopInterval = null;
    }

    // 2. Sembunyikan Semua Banner
    const banners = ['emergency-alert-banner', 'login-emergency-overlay', 'security-emergency-banner', 'standby-status'];
    banners.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            if (id === 'standby-status') el.classList.remove('hidden');
            else el.classList.add('hidden');
        }
    });
}

function resolveEmergency() {
    if (confirm('Konfirmasi penanganan darurat?')) {
        const data = { status: 'RESOLVED' };
        localStorage.removeItem('tlink_emergency');
        emergencyChannel.postMessage(data);
        stopAllEmergencyAlerts();
    }
}

function sendSystemPush(name, unit, type) {
    if ("Notification" in window && Notification.permission === "granted") {
        const title = type === 'EMERGENCY' ? "🚨 EMERGENCY ALERT" : "ℹ️ BANTUAN WARGA";
        new Notification(title, {
            body: `Unit ${unit} (${name}): ${type === 'EMERGENCY' ? 'BAHAYA KRITIS!' : 'MEMBUTUHKAN BANTUAN.'}`,
            icon: "https://ui-avatars.com/api/?name=S&background=e11d48&color=fff",
            requireInteraction: true,
            tag: 'tlink-alert'
        });
    }
}

// Polling status darurat otomatis (Sinkronisasi Antar Tab via Storage)
function checkEmergencyStatusLoop() {
    const data = localStorage.getItem('tlink_emergency');
    if (data) {
        const emergency = JSON.parse(data);
        if (emergency.status === 'ACTIVE') {
            handleGlobalEmergency(emergency);
        } else {
            stopAllEmergencyAlerts();
        }
    } else {
        stopAllEmergencyAlerts();
    }
}

// Mulai polling
setInterval(checkEmergencyStatusLoop, 2000);

// MODAL ADMIN FUNCTIONS

// MODAL ADMIN FUNCTIONS
function openAddWargaModal() {
    const modal = document.getElementById('modal-tambah-warga');
    if (modal) modal.classList.remove('hidden');
}

function closeAddWargaModal() {
    const modal = document.getElementById('modal-tambah-warga');
    if (modal) {
        modal.classList.add('hidden');
        document.getElementById('select-blok').value = '';
        document.getElementById('select-unit').innerHTML = '<option value="" disabled selected>Pilih Blok Dulu</option>';
        document.getElementById('select-unit').disabled = true;
    }
}

function openAddBlokModal() {
    const modal = document.getElementById('modal-tambah-blok');
    if (modal) modal.classList.remove('hidden');
}

function closeAddBlokModal() {
    const modal = document.getElementById('modal-tambah-blok');
    if (modal) modal.classList.add('hidden');
}

// DYNAMIC UNIT GENERATOR
function populateUnitNumbers() {
    const masterDataBlok = { 'A': 50, 'B': 60, 'C': 40, 'D': 30 };
    const selectBlok = document.getElementById('select-blok');
    const selectUnit = document.getElementById('select-unit');
    if (!selectBlok || !selectUnit) return;

    const selectedBlok = selectBlok.value;
    selectUnit.innerHTML = '<option value="" disabled selected>Pilih Nomor Unit</option>';

    if (selectedBlok && masterDataBlok[selectedBlok]) {
        for (let i = 1; i <= masterDataBlok[selectedBlok]; i++) {
            const unitNumber = i < 10 ? '0' + i : i.toString(); 
            const option = document.createElement('option');
            option.value = unitNumber;
            option.textContent = unitNumber;
            selectUnit.appendChild(option);
        }
        selectUnit.disabled = false;
    } else {
        selectUnit.disabled = true;
    }
}

function openRegisterModal() {
    const modal = document.getElementById('modal-register-keluar');
    const inputNama = document.getElementById('reg-nama-warga');
    
    // Reset form
    inputNama.value = '';
    document.getElementById('reg-tgl-keluar').value = '';
    document.getElementById('reg-status').value = '';
    document.getElementById('reg-alasan-keluar').value = '';

    // Cek apakah ada warga yang dipilih untuk pre-fill nama
    const selectedCheckboxes = Array.from(document.querySelectorAll('.warga-checkbox:checked'));
    if (selectedCheckboxes.length === 1) {
        const row = selectedCheckboxes[0].closest('tr');
        const nama = row.querySelector('.font-medium.text-slate-800').innerText;
        inputNama.value = nama;
    } else if (selectedCheckboxes.length > 1) {
        inputNama.value = selectedCheckboxes.length + ' Warga terpilih';
    }

    if (modal) modal.classList.remove('hidden');
}

function closeRegisterModal() {
    const modal = document.getElementById('modal-register-keluar');
    if (modal) modal.classList.add('hidden');
}

function submitRegisterKeluar() {
    const nama = document.getElementById('reg-nama-warga').value;
    const tgl = document.getElementById('reg-tgl-keluar').value;
    const status = document.getElementById('reg-status').value;
    const alasan = document.getElementById('reg-alasan-keluar').value;

    if (!nama || !tgl || !status || !alasan) {
        alert('Mohon lengkapi semua data registrasi!');
        return;
    }

    alert('Data registrasi ' + status + ' untuk ' + nama + ' berhasil disimpan!');
    closeRegisterModal();
    
    // Uncheck all and hide button
    const source = document.getElementById('check-all-warga');
    if (source) {
        source.checked = false;
        toggleAllWarga(source);
    }
}

// CHECKBOX LOGIC
function toggleAllWarga(source) {
    const checkboxes = document.querySelectorAll('.warga-checkbox');
    checkboxes.forEach(cb => { cb.checked = source.checked; });
    checkWargaSelection();
}

function checkWargaSelection() {
    const checkboxes = document.querySelectorAll('.warga-checkbox');
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const btnRegistrasi = document.getElementById('btn-registrasi-massal');
    const checkAll = document.getElementById('check-all-warga');

    if (btnRegistrasi) {
        checkedCount > 0 ? btnRegistrasi.classList.remove('hidden') : btnRegistrasi.classList.add('hidden');
    }

    if (checkAll) {
        if (checkedCount === checkboxes.length && checkboxes.length > 0) {
            checkAll.checked = true;
            checkAll.indeterminate = false;
        } else if (checkedCount > 0) {
            checkAll.checked = false;
            checkAll.indeterminate = true;
        } else {
            checkAll.checked = false;
            checkAll.indeterminate = false;
        }
    }
}
