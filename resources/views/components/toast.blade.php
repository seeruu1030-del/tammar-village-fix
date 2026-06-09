<div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

<template id="toast-template">
    <div class="toast-item pointer-events-auto flex items-center gap-3 bg-white border border-slate-100 shadow-2xl shadow-slate-200/50 rounded-2xl p-4 min-w-[320px] max-w-md transform transition-all duration-300 translate-x-full opacity-0">
        <div class="toast-icon w-10 h-10 rounded-xl flex items-center justify-center shrink-0"></div>
        <div class="flex-1">
            <h4 class="toast-title text-sm font-bold text-slate-800 leading-none mb-1"></h4>
            <p class="toast-message text-[11px] text-slate-500 font-medium"></p>
        </div>
        <button class="toast-close text-slate-300 hover:text-slate-500 transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>
</template>

<script>
    const Toast = {
        success(title, message) { this.show('success', title, message); },
        error(title, message) { this.show('error', title, message); },
        info(title, message) { this.show('info', title, message); },
        warning(title, message) { this.show('warning', title, message); },

        show(type, title, message) {
            const container = document.getElementById('toast-container');
            const template = document.getElementById('toast-template');
            const clone = template.content.cloneNode(true);
            const item = clone.querySelector('.toast-item');
            const iconBox = clone.querySelector('.toast-icon');
            const titleEl = clone.querySelector('.toast-title');
            const messageEl = clone.querySelector('.toast-message');
            const closeBtn = clone.querySelector('.toast-close');

            const config = {
                success: { icon: 'fa-circle-check', color: 'bg-emerald-50 text-emerald-500', defaultTitle: 'Berhasil' },
                error: { icon: 'fa-circle-exclamation', color: 'bg-rose-50 text-rose-500', defaultTitle: 'Terjadi Kesalahan' },
                info: { icon: 'fa-circle-info', color: 'bg-sky-50 text-sky-500', defaultTitle: 'Informasi' },
                warning: { icon: 'fa-triangle-exclamation', color: 'bg-amber-50 text-amber-500', defaultTitle: 'Peringatan' }
            }[type];

            iconBox.innerHTML = `<i class="fa-solid ${config.icon} text-lg"></i>`;
            iconBox.className += ` ${config.color}`;
            titleEl.innerText = title || config.defaultTitle;
            messageEl.innerText = message;

            container.appendChild(clone);

            // Animate In
            setTimeout(() => {
                const el = container.lastElementChild;
                el.classList.remove('translate-x-full', 'opacity-0');
            }, 10);

            const removeToast = () => {
                const el = container.querySelector('.toast-item'); // In case multiple exist, but should target specific
                item.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => item.remove(), 300);
            };

            closeBtn.onclick = removeToast;
            setTimeout(removeToast, 5000);
        }
    };

    // Auto show toast from session
    window.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            Toast.success('Berhasil', "{{ session('success') }}");
        @endif
        @if(session('error'))
            Toast.error('Gagal', "{{ session('error') }}");
        @endif
        @if($errors->any())
            Toast.error('Kesalahan Input', "{{ $errors->first() }}");
        @endif
    });
</script>
