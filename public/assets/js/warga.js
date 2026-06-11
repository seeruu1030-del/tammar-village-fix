// ==========================================
// NAVIGATION & UI LOGIC FOR PORTAL WARGA
// ==========================================

function switchWargaView(viewId) {
    const views = ['dashboard', 'profil', 'iuran', 'virtual-account', 'tabungan', 'tamu', 'laporan', 'keamanan'];
    
    views.forEach(v => {
        const viewElem = document.getElementById(`view-${v}`);
        const navElem = document.getElementById(`nav-${v}`);
        
        if (viewElem) {
            v === viewId ? viewElem.classList.remove('hidden') : viewElem.classList.add('hidden');
        }
        
        if (navElem) {
            if (v === viewId) {
                navElem.classList.add('bg-emerald-500/10', 'text-emerald-500', 'border-r-2', 'border-emerald-500');
                navElem.classList.remove('text-slate-300', 'hover:bg-slate-800', 'hover:text-white');
            } else {
                navElem.classList.remove('bg-emerald-500/10', 'text-emerald-500', 'border-r-2', 'border-emerald-500');
                navElem.classList.add('text-slate-300', 'hover:bg-slate-800', 'hover:text-white');
            }
        }
    });

    // Update Header Title (Optional, if title elem exists)
    const titleElem = document.getElementById('view-title');
    if (titleElem) {
        const titles = {
            'dashboard': 'Dashboard Portal Warga',
            'profil': 'Profil & ID Card',
            'iuran': 'Tagihan & Iuran',
            'virtual-account': 'Virtual Account Bendahara',
            'tabungan': 'Data Tabungan Warga',
            'tamu': 'Pendaftaran Tamu',
            'laporan': 'Lapor & Darurat',
            'keamanan': 'Keamanan Akun'
        };
        titleElem.innerText = titles[viewId] || 'Portal Warga';
    }

    // Scroll top
    const scrollArea = document.querySelector('main > div.flex-1');
    if (scrollArea) scrollArea.scrollTop = 0;
}

// Toggle Sidebar (Standardized)
function toggleSidebar() {
    const sidebar = document.getElementById('warga-sidebar');
    if (!sidebar) return;

    if (window.innerWidth < 768) {
        sidebar.classList.toggle('-translate-x-full');
    } else {
        document.body.classList.toggle('sidebar-collapsed');
    }
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

function switchProfileTab(tabId) {
    const tabs = ['personal', 'family', 'document', 'vehicle', 'idcard'];
    tabs.forEach(t => {
        const content = document.getElementById(`tab-${t}`);
        const btn = document.getElementById(`btn-tab-${t}`);
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

// MODAL FUNCTIONS
function openAddSetoranModal() {
    const modal = document.getElementById('modal-tambah-setoran');
    if (modal) modal.classList.remove('hidden');
}

function closeAddSetoranModal() {
    const modal = document.getElementById('modal-tambah-setoran');
    if (modal) modal.classList.add('hidden');
}

function openEditProfileModal() {
    const modal = document.getElementById('modal-edit-profil');
    if (modal) modal.classList.remove('hidden');
}

function closeEditProfileModal() {
    const modal = document.getElementById('modal-edit-profil');
    if (modal) modal.classList.add('hidden');
}

// INITIALIZATION
document.addEventListener('DOMContentLoaded', () => {
    // Check for hash in URL for specific view or tab redirect
    const hash = window.location.hash;
    
    if (hash && hash.startsWith('#view-')) {
        const urlParams = new URLSearchParams(hash.split('?')[1] || '');
        const tab = urlParams.get('tab');
        
        // Remove query params from hash for view ID
        const viewId = hash.split('?')[0].replace('#view-', '');
        
        switchWargaView(viewId);
        
        if (viewId === 'profil' && tab) {
            switchProfileTab(tab);
        } else if (viewId === 'profil') {
            switchProfileTab('personal');
        }
    } else {
        switchWargaView('dashboard');
    }
});
