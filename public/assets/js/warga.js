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

    // Close sidebar on mobile after switching view
    if (window.innerWidth < 768) {
        const sidebar = document.getElementById('warga-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
            if (backdrop) {
                backdrop.classList.add('opacity-0');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }
    }
}

// Toggle Sidebar (Standardized)
function toggleSidebar() {
    const sidebar = document.getElementById('warga-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar) return;

    if (window.innerWidth < 768) {
        const isHidden = sidebar.classList.contains('-translate-x-full');
        
        if (isHidden) {
            sidebar.classList.remove('-translate-x-full');
            if (backdrop) {
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                    backdrop.classList.remove('opacity-0');
                }, 10);
            }
        } else {
            sidebar.classList.add('-translate-x-full');
            if (backdrop) {
                backdrop.classList.add('opacity-0');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }
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
function openAddSetoranModal(progId = null, progName = null) {
    if (progId) document.getElementById('savings-prog-id').value = progId;
    const modal = document.getElementById('modal-tambah-setoran');
    if (modal) modal.classList.remove('hidden');
}

function closeAddSetoranModal() {
    const modal = document.getElementById('modal-tambah-setoran');
    if (modal) modal.classList.add('hidden');
}

// IURAN & PAYMENT MODAL LOGIC
function openPaymentModal() {
    const checkboxes = document.querySelectorAll('.invoice-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Silakan pilih minimal satu tagihan untuk dibayar.');
        return;
    }

    const container = document.getElementById('selected-invoices-container');
    const totalAmountElem = document.getElementById('modal-total-amount');
    const totalCountElem = document.getElementById('modal-total-count');
    
    if (!container || !totalAmountElem || !totalCountElem) return;

    container.innerHTML = '';
    let total = 0;

    checkboxes.forEach(cb => {
        const id = cb.value;
        const amount = parseInt(cb.dataset.amount);
        total += amount;

        // Add hidden input for each selected invoice
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'invoice_ids[]';
        input.value = id;
        container.appendChild(input);
    });

    totalAmountElem.innerText = 'Rp ' + total.toLocaleString('id-ID');
    totalCountElem.innerText = checkboxes.length + ' Bulan';

    const modal = document.getElementById('modal-bayar-iuran');
    if (modal) modal.classList.remove('hidden');
}

function closePaymentModal() {
    const modal = document.getElementById('modal-bayar-iuran');
    if (modal) modal.classList.add('hidden');
}

function updateInvoiceSelection() {
    const checkboxes = document.querySelectorAll('.invoice-checkbox:checked');
    const count = checkboxes.length;
    let total = 0;

    checkboxes.forEach(cb => {
        total += parseInt(cb.dataset.amount);
    });

    const summaryCount = document.getElementById('summary-count');
    const summaryTotal = document.getElementById('summary-total');
    const selectionSummary = document.getElementById('selection-summary');
    const btnPayNow = document.getElementById('btn-pay-now');
    const selectedCount = document.getElementById('selected-count');

    if (summaryCount) summaryCount.innerText = count + ' Bulan';
    if (summaryTotal) summaryTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    if (selectedCount) selectedCount.innerText = count;

    if (count > 0) {
        if (selectionSummary) selectionSummary.classList.remove('hidden');
        if (btnPayNow) btnPayNow.classList.remove('hidden');
    } else {
        if (selectionSummary) selectionSummary.classList.add('hidden');
        if (btnPayNow) btnPayNow.classList.add('hidden');
    }
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

    // Invoice Selection Listeners
    const selectAll = document.getElementById('select-all-invoices');
    const invoiceCheckboxes = document.querySelectorAll('.invoice-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            invoiceCheckboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateInvoiceSelection();
        });
    }

    invoiceCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateInvoiceSelection();
            
            // Update select-all state
            if (selectAll) {
                const allChecked = Array.from(invoiceCheckboxes).every(c => c.checked);
                const someChecked = Array.from(invoiceCheckboxes).some(c => c.checked);
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            }
        });
    });
});
