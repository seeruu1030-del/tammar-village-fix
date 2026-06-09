// ==========================================
// NAVIGATION & UI LOGIC FOR PORTAL BANK
// ==========================================

function switchBankView(viewId) {
    const views = ['dashboard', 'manajemen-va', 'sinkronisasi', 'profil', 'keamanan'];
    
    views.forEach(v => {
        const viewElem = document.getElementById(`view-${v}`);
        const navElem = document.getElementById(`nav-${v}`);
        
        if (viewElem) {
            v === viewId ? viewElem.classList.remove('hidden') : viewElem.classList.add('hidden');
        }
        
        if (navElem) {
            if (v === viewId) {
                navElem.classList.add('bg-orange-50', 'text-orange-600', 'border-r-2', 'border-orange-600');
                navElem.classList.remove('text-slate-500', 'hover:bg-slate-50', 'hover:text-slate-800');
            } else {
                navElem.classList.remove('bg-orange-50', 'text-orange-600', 'border-r-2', 'border-orange-600');
                navElem.classList.add('text-slate-500', 'hover:bg-slate-50', 'hover:text-slate-800');
            }
        }
    });

    // Update Header Title
    const titleElem = document.getElementById('view-title');
    if (titleElem) {
        const titles = {
            'dashboard': 'Dashboard Portal Mitra',
            'manajemen-va': 'Manajemen Virtual Account',
            'sinkronisasi': 'Sinkronisasi API & H2H',
            'profil': 'Profil Administrator Bank',
            'keamanan': 'Keamanan & Kredensial'
        };
        titleElem.innerText = titles[viewId] || 'Portal Mitra';
    }

    // Scroll top
    const scrollArea = document.querySelector('main > div.flex-1');
    if (scrollArea) scrollArea.scrollTop = 0;
}

// Toggle Sidebar (Standardized)
function toggleSidebar() {
    const sidebar = document.getElementById('bank-sidebar');
    if (!sidebar) return;

    if (window.innerWidth < 768) {
        sidebar.classList.toggle('-translate-x-full');
    } else {
        document.body.classList.toggle('sidebar-collapsed');
    }
}

// BANK SPECIFIC FEATURES
function simulateMassGenerate() {
    if (confirm('Apakah Anda yakin ingin melakukan generate Virtual Account secara massal?')) {
        alert('Proses generate massal dimulai... Silakan tunggu.');
        setTimeout(() => {
            alert('Berhasil! Nomor Virtual Account baru telah diterbitkan.');
            location.reload(); // Simple reload to simulate update
        }, 1500);
    }
}

function simulateSingleGenerate(btn) {
    btn.disabled = true;
    btn.innerText = 'PROCESSING...';
    setTimeout(() => {
        alert('Nomor VA BNI 46 berhasil diterbitkan!');
        location.reload();
    }, 1000);
}

function simulateSync() {
    alert('Koneksi ke server T-Link berhasil! Status: Online.');
}

function toggleBankAdminDropdown() {
    const dropdown = document.getElementById('bank-admin-dropdown');
    if (dropdown) dropdown.classList.toggle('hidden');
}

// DROPDOWN PROFILE LOGIC (Standardized)
function toggleProfileDropdown() {
    toggleBankAdminDropdown();
}

// Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('bank-admin-dropdown');
    const btn = document.querySelector('button[onclick="toggleBankAdminDropdown()"]');
    
    if (dropdown && !dropdown.contains(e.target) && btn && !btn.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

// INITIALIZATION
document.addEventListener('DOMContentLoaded', () => {
    switchBankView('dashboard');
    if (typeof initTransactionChart === 'function') {
        initTransactionChart();
    }
});
