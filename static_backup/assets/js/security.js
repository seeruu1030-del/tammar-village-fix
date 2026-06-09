// ==========================================
// NAVIGATION & UI LOGIC FOR PORTAL KEAMANAN
// ==========================================

function switchSecurityView(viewId) {
    const views = ['dashboard', 'history', 'patrol'];
    views.forEach(v => {
        const el = document.getElementById('view-' + v);
        const nav = document.getElementById('nav-' + v);
        if (el) v === viewId ? el.classList.remove('hidden') : el.classList.add('hidden');
        
        if (nav) {
            if (v === viewId) {
                nav.classList.add('bg-rose-600/10', 'text-rose-500', 'border-r-2', 'border-rose-600');
                nav.classList.remove('hover:bg-slate-700', 'text-slate-300');
            } else {
                nav.classList.remove('bg-rose-600/10', 'text-rose-500', 'border-r-2', 'border-rose-600');
                nav.classList.add('hover:bg-slate-700', 'text-slate-300');
            }
        }
    });
    const titleEl = document.getElementById('view-title');
    if (titleEl) {
        titleEl.innerText = viewId === 'dashboard' ? 'Security Command Center' : viewId.toUpperCase();
    }
}

function requestNotificationPermission() {
    if ("Notification" in window) {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                alert('Akses Notifikasi Aktif! Petugas akan menerima alert meskipun browser di-minimize.');
            }
        });
    }
}

// Real-time Clock
function updateClock() {
    const clockEl = document.getElementById('real-time-clock');
    if (clockEl) {
        const now = new Date();
        clockEl.innerText = now.toLocaleTimeString('id-ID', { hour12: false });
    }
}

// Toggle Sidebar (Standardized)
function toggleSidebar() {
    const sidebar = document.getElementById('security-sidebar');
    if (sidebar) {
        if (window.innerWidth < 768) {
            sidebar.classList.toggle('hidden');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
        }
    }
}

// Profile Dropdown
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

// INITIALIZATION
document.addEventListener('DOMContentLoaded', () => {
    switchSecurityView('dashboard');
    setInterval(updateClock, 1000);
    updateClock();
    
    // Minta Izin Notifikasi Sistem saat portal dibuka
    requestNotificationPermission();
});

// Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('profileDropdown');
    const btn = document.querySelector('button[onclick="toggleProfileDropdown()"]');
    
    if (dropdown && !dropdown.contains(e.target) && btn && !btn.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
