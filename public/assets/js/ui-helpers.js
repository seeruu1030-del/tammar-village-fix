// FUNGSI MODAL TAMBAH SAVINGS PROGRAM (BARU)
function openAddSavingsProgramModal() {
    alert('Form Tambah Program Tabungan Baru akan segera terbuka!');
}

// FUNGSI MODAL TAMBAH WARGA
function openAddWargaModal() {
    document.getElementById('modal-tambah-warga').classList.remove('hidden');
}

function closeAddWargaModal() {
    document.getElementById('modal-tambah-warga').classList.add('hidden');
    // Reset form saat ditutup
    document.getElementById('select-blok').value = '';
    document.getElementById('select-unit').innerHTML = '<option value="" disabled selected>Pilih Blok Dulu</option>';
    document.getElementById('select-unit').disabled = true;
}

// FUNGSI GENERATE NOMOR UNIT DINAMIS (Diperbarui sesuai satu huruf & hanya angka)
function populateUnitNumbers() {
    // Data Master Blok dicocokkan dengan value satu huruf (A, B, C, D)
    const masterDataBlok = {
        'A': 50,
        'B': 60,
        'C': 40,
        'D': 30
    };

    const selectBlok = document.getElementById('select-blok');
    const selectUnit = document.getElementById('select-unit');
    const selectedBlok = selectBlok.value;

    // Kosongkan opsi unit sebelumnya
    selectUnit.innerHTML = '<option value="" disabled selected>Pilih Nomor Unit</option>';

    if (selectedBlok && masterDataBlok[selectedBlok]) {
        const totalUnits = masterDataBlok[selectedBlok];
        
        // Looping untuk membuat pilihan unit
        for (let i = 1; i <= totalUnits; i++) {
            // Format angka agar menjadi 2 digit (01, 02, ... 10, 11)
            const unitNumber = i < 10 ? '0' + i : i.toString(); 
            
            const option = document.createElement('option');
            option.value = unitNumber;
            option.textContent = unitNumber; // Menampilkan hanya angkanya saja (misal: "01")
            selectUnit.appendChild(option);
        }
        // Aktifkan dropdown unit
        selectUnit.disabled = false;
    } else {
        selectUnit.innerHTML = '<option value="" disabled selected>Pilih Blok Dulu</option>';
        selectUnit.disabled = true;
    }
}

// FUNGSI MODAL TAMBAH BLOK
function openAddBlokModal() {
    document.getElementById('modal-tambah-blok').classList.remove('hidden');
}

// FUNGSI MODAL TAMBAH BLOK
function closeAddBlokModal() {
    document.getElementById('modal-tambah-blok').classList.add('hidden');
}

// FUNGSI MODAL EDIT WARGA (MIRROR PROFILE)
function openEditWargaModal(name) {
    const modal = document.getElementById('modal-edit-warga');
    if (modal) {
        modal.classList.remove('hidden');
        // Reset tab ke personal saat buka
        switchEditWargaTab('personal');
    }
}

function closeEditWargaModal() {
    const modal = document.getElementById('modal-edit-warga');
    if (modal) modal.classList.add('hidden');
}

function switchEditWargaTab(tabId) {
    const tabs = ['personal', 'family', 'document', 'vehicle', 'idcard'];
    tabs.forEach(t => {
        const content = document.getElementById(`edit-tab-${t}`);
        const btn = document.getElementById(`btn-edit-tab-${t}`);
        
        if (content) {
            t === tabId ? content.classList.remove('hidden') : content.classList.add('hidden');
        }
        
        if (btn) {
            if (t === tabId) {
                btn.classList.add('text-emerald-600', 'border-b-2', 'border-emerald-600');
                btn.classList.remove('text-slate-400');
            } else {
                btn.classList.remove('text-emerald-600', 'border-b-2', 'border-emerald-600');
                btn.classList.add('text-slate-400');
            }
        }
    });
}

// FUNGSI TOGGLE TAMPILKAN/SEMBUNYIKAN ANGGOTA KELUARGA DI TABEL
function toggleFamilyRows(groupId, button) {
    const rows = document.querySelectorAll(`.family-row-${groupId}`);
    const icon = button.querySelector('i');
    
    rows.forEach(row => {
        row.classList.toggle('hidden');
    });
    
    // Rotate icon
    if (icon) {
        icon.classList.toggle('rotate-90');
    }
    
    // Toggle active state on button
    if (button.classList.contains('bg-slate-100')) {
        button.classList.remove('bg-slate-100', 'text-slate-400');
        button.classList.add('bg-emerald-100', 'text-emerald-600');
    } else {
        button.classList.add('bg-slate-100', 'text-slate-400');
        button.classList.remove('bg-emerald-100', 'text-emerald-600');
    }
}

// FUNGSI CHECKBOX DIREKTORI WARGA
function toggleAllWarga(source) {
    const checkboxes = document.querySelectorAll('.warga-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = source.checked;
    });
    checkWargaSelection();
}

// MEMERIKSA SELEKSI CHECKBOX WARGA
function checkWargaSelection() {
    const checkboxes = document.querySelectorAll('.warga-checkbox');
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const btnRegistrasi = document.getElementById('btn-registrasi-massal');
    const checkAll = document.getElementById('check-all-warga');

    // Tampilkan atau Sembunyikan Tombol Registrasi
    if (checkedCount > 0) {
        btnRegistrasi.classList.remove('hidden');
    } else {
        btnRegistrasi.classList.add('hidden');
    }

    // Atur status centang "Select All"
    if (checkedCount === checkboxes.length && checkboxes.length > 0) {
        checkAll.checked = true;
        checkAll.indeterminate = false;
    } else if (checkedCount > 0) {
        checkAll.checked = false;
        checkAll.indeterminate = true; // Efek strip (sebagian terpilih)
    } else {
        checkAll.checked = false;
        checkAll.indeterminate = false;
    }
}

// FUNGSI CHECKBOX ID CARD
function toggleAllIDCard(source) {
    const checkboxes = document.querySelectorAll('.idcard-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = source.checked;
    });
}

// MEMERIKSA SELEKSI CHECKBOX ID CARD
function checkIDCardSelection() {
    const checkboxes = document.querySelectorAll('.idcard-checkbox');
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const checkAll = document.getElementById('check-all-idcard');

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

// Variabel Global untuk menyimpan background kustom
let customIDCardBg = null;

// FUNGSI HANDLE UPLOAD BACKGROUND ID CARD
function handleIDCardBgUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('id-card-preview');
            if (preview) {
                // Simpan URL data ke variabel global
                customIDCardBg = e.target.result;
                
                // Update tampilan preview di dashboard
                preview.style.backgroundImage = `url('${customIDCardBg}')`;
                preview.style.backgroundSize = 'cover';
                preview.style.backgroundPosition = 'center';
                
                // Sembunyikan pattern bawaan agar background kustom terlihat jelas
                const patterns = preview.querySelectorAll('.blur-3xl');
                patterns.forEach(p => p.style.opacity = '0.2');
                
                alert('Background ID Card berhasil diperbarui!');
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// FUNGSI UPDATE PRATINJAU ID CARD SECARA DINAMIS
function updateIDCardPreview(name, unit, nik, status) {
    document.getElementById('preview-name').textContent = name.toUpperCase();
    document.getElementById('preview-unit').textContent = unit;
    document.getElementById('preview-status').textContent = status;
    
    // Generate QR Code dinamis berdasarkan data warga
    const qrData = `T-LINK_RESIDENT_${nik}_${name.replace(/\s+/g, '_')}_${unit.replace(/\s+/g, '_')}`;
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrData)}`;
    
    const qrImg = document.getElementById('preview-qr');
    if (qrImg) {
        qrImg.src = qrUrl;
    }
}

// FUNGSI PRINT PREVIEW ID CARD (DIPERBARUI DENGAN QR DINAMIS)
function openIDCardPrintPreview() {
    const checkboxes = document.querySelectorAll('.idcard-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Silakan pilih minimal satu warga untuk dicetak!');
        return;
    }

    // Buat jendela baru untuk print preview
    const printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>Print ID Cards - The Tamar Village</title>');
    
    // Copy external CSS
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.tailwindcss.com">');
    printWindow.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">');
    
    printWindow.document.write('<style>');
    // Gunakan background custom jika ada, jika tidak gunakan gradient default
    const bgStyle = customIDCardBg 
        ? `background-image: url('${customIDCardBg}'); background-size: cover; background-position: center;` 
        : `background: linear-gradient(135deg, #1e293b, #0f172a);`;
    const patternOpacity = customIDCardBg ? '0.1' : '1.0';

    printWindow.document.write(`
        @page { size: A4; margin: 10mm; }
        body { font-family: 'Inter', sans-serif; background: #fff; }
        .print-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10mm; }
        .id-card { 
            width: 85.6mm; 
            height: 54mm; 
            ${bgStyle}
            border-radius: 12px; 
            color: white; 
            position: relative; 
            padding: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .pattern { position: absolute; opacity: ${patternOpacity}; border-radius: 50%; filter: blur(40px); pointer-events: none; }
        .pattern-1 { top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(14, 165, 233, 0.2); }
        .pattern-2 { bottom: -20px; left: -20px; width: 80px; height: 80px; background: rgba(59, 130, 246, 0.2); }
        .card-photo { width: 60px; height: 75px; background: rgba(51, 65, 85, 0.8); border-radius: 6px; border: 1px solid rgba(71, 85, 105, 0.5); display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .card-qr { width: 48px; height: 48px; background: white; border-radius: 6px; padding: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .logo-t { width: 18px; height: 18px; background: #0ea5e9; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 8px; font-weight: bold; }
    `);
    printWindow.document.write('</style></head><body>');
    
    printWindow.document.write('<div class="print-grid">');
    
    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        const name = row.querySelector('.font-medium').textContent;
        const unit = row.querySelector('.text-slate-600').textContent;
        const nik = row.querySelector('.text-slate-500').textContent.replace('NIK: ', '');
        
        // QR Code data dinamis untuk cetak
        const qrData = `T-LINK_RESIDENT_${nik}_${name.replace(/\s+/g, '_')}_${unit.replace(/\s+/g, '_')}`;
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrData)}`;

        printWindow.document.write(`
            <div class="id-card">
                <div class="pattern pattern-1"></div>
                <div class="pattern pattern-2"></div>
                <div style="position: absolute; top: 0; right: 0; padding: 4px 10px; background: #0ea5e9; font-size: 7px; font-weight: bold; border-bottom-left-radius: 10px; z-index: 10;">RESIDENT CARD</div>
                <div style="display: flex; height: 100%; position: relative; z-index: 5;">
                    <div style="width: 30%; display: flex; flex-direction: column; justify-content: space-between; align-items: center;">
                        <div class="card-photo"><i class="fa-solid fa-user" style="font-size: 24px; color: #94a3b8;"></i></div>
                        <div class="card-qr"><img src="${qrUrl}" style="width: 100%; height: 100%; object-fit: contain;"></div>
                    </div>
                    <div style="width: 70%; padding-left: 15px; display: flex; flex-direction: column;">
                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px;">
                            <div class="logo-t">T</div>
                            <span style="font-size: 8px; font-weight: bold; letter-spacing: 1px; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.5);">THE TAMAR VILLAGE</span>
                        </div>
                        <div style="margin-bottom: 6px;">
                            <div style="font-size: 7px; color: rgba(255,255,255,0.7); text-transform: uppercase; font-weight: 600;">Nama Lengkap</div>
                            <div style="font-size: 11px; font-weight: 800; letter-spacing: 0.5px; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">${name.toUpperCase()}</div>
                        </div>
                        <div style="margin-bottom: 6px;">
                            <div style="font-size: 7px; color: rgba(255,255,255,0.7); text-transform: uppercase; font-weight: 600;">Blok / No. Unit</div>
                            <div style="font-size: 10px; font-weight: 700; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">${unit}</div>
                        </div>
                        <div style="margin-top: auto;">
                            <div style="font-size: 7px; color: rgba(255,255,255,0.7); text-transform: uppercase; font-weight: 600;">Status Hunian</div>
                            <div style="font-size: 9px; font-weight: 900; color: #38bdf8; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">WARGA TETAP</div>
                        </div>
                    </div>
                </div>
            </div>
        `);
    });
    
    printWindow.document.write('</div>');
    printWindow.document.write('<script>setTimeout(() => { window.print(); window.close(); }, 500);</script>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
}

// FUNGSI HANDLE PERUBAHAN TAHUN AKTIF SECARA GLOBAL
function handleGlobalYearChange(year) {
    // 1. Update text di Dashboard Ringkasan
    const dashboardSub = document.querySelector('#view-dashboard p.text-slate-500');
    if (dashboardSub) {
        dashboardSub.innerHTML = `Pantau aktivitas The Tamar Village hari ini, 27 Mei ${year}.`;
    }

    // 2. Update Target IPL di Dashboard Keuangan (Menggunakan selector yang lebih spesifik)
    const targetIPL = document.querySelector('#view-ringkasan-keuangan h3.font-bold');
    if (targetIPL) {
        targetIPL.innerHTML = `Target Pengumpulan IPL (Mei ${year})`;
    }

    // 3. Update Label Laporan Rekapitulasi
    const rekapLabel = document.querySelector('#view-rekapitulasi h2.font-bold');
    if (rekapLabel) {
        rekapLabel.innerHTML = `Laporan Bulanan Tahun ${year}`;
    }

    // 4. Update Filter di Iuran Warga & Buku Kas
    const iuranYearSelect = document.querySelector('#view-iuran select');
    if (iuranYearSelect) {
        iuranYearSelect.innerHTML = `<option>Mei ${year}</option><option>April ${year}</option><option>Maret ${year}</option>`;
    }
    
    const kasYearSelect = document.querySelector('#view-laporan-kas select');
    if (kasYearSelect) {
        kasYearSelect.innerHTML = `<option>Mei ${year}</option><option>April ${year}</option><option>Maret ${year}</option><option>Semua Bulan</option>`;
    }

    // 5. Memberikan feedback visual
    alert(`Sistem kini beralih ke Tahun Operasional ${year}. Data telah disesuaikan.`);
}

// FUNGSI MODAL EDIT VIRTUAL ACCOUNT
function openEditVAModal() {
    const modal = document.getElementById('modal-edit-va');
    if (modal) modal.classList.remove('hidden');
}

function closeEditVAModal() {
    const modal = document.getElementById('modal-edit-va');
    if (modal) modal.classList.add('hidden');
}

// FUNGSI TOGGLE DROPDOWN ADMIN (Pojok Kanan Atas)
function toggleAdminDropdown() {
    const dropdown = document.getElementById('admin-dropdown');
    const arrow = document.getElementById('admin-arrow');
    
    if (dropdown) {
        const isHidden = dropdown.classList.contains('hidden');
        if (isHidden) {
            dropdown.classList.remove('hidden');
            // Sedikit delay untuk memicu animasi transisi jika ada
            setTimeout(() => {
                dropdown.classList.add('opacity-100', 'translate-y-0');
            }, 10);
            arrow.classList.add('rotate-180');
        } else {
            dropdown.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }
}

// Menutup dropdown jika klik di luar area profil
window.addEventListener('click', function(e) {
    const adminDropdown = document.getElementById('admin-dropdown');
    const adminButton = document.querySelector('button[onclick="toggleAdminDropdown()"]');
    
    if (adminDropdown && !adminDropdown.contains(e.target) && !adminButton.contains(e.target)) {
        adminDropdown.classList.add('hidden');
        const arrow = document.getElementById('admin-arrow');
        if (arrow) arrow.classList.remove('rotate-180');
    }
});
