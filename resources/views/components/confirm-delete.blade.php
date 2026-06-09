<div id="modal-confirm-delete" class="invisible opacity-0 fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/60 backdrop-blur-[2px] px-4 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-all duration-200" id="confirm-delete-box">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-trash-can text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Data Warga?</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Tindakan ini tidak dapat dibatalkan. Semua data terkait warga ini akan dihapus secara permanen.</p>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
            <button onclick="closeConfirmDelete()" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-100 transition">Batal</button>
            <button id="btn-confirm-delete-submit" class="flex-1 px-4 py-2.5 bg-rose-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-rose-500/20 hover:bg-rose-600 transition">Hapus Data</button>
        </div>
    </div>
</div>

<script>
    (function() {
        let formToDelete = null;
        const modal = document.getElementById('modal-confirm-delete');
        const box = document.getElementById('confirm-delete-box');

        window.confirmDelete = function(formId) {
            formToDelete = document.getElementById(formId);
            modal.classList.remove('invisible', 'opacity-0');
            box.classList.remove('scale-95');
        };

        window.closeConfirmDelete = function() {
            box.classList.add('scale-95');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('invisible');
                formToDelete = null;
            }, 200);
        };

        document.getElementById('btn-confirm-delete-submit').onclick = () => formToDelete?.submit();
        modal.onclick = (e) => { if (e.target === modal) closeConfirmDelete(); };
    })();
</script>
