<style>
    @keyframes lomo-toast-progress {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }
</style>

<script>
(() => {
    if (window.__lomoUiBooted) {
        return;
    }

    window.__lomoUiBooted = true;

    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('toast')) {
            Alpine.store('toast', {
                items: [],
                show(message, type = 'info', duration = 4500) {
                    const id = `${Date.now()}-${Math.random().toString(36).slice(2)}`;
                    this.items.push({ id, message, type, duration });
                    window.setTimeout(() => this.dismiss(id), duration);
                },
                dismiss(id) {
                    this.items = this.items.filter(item => item.id !== id);
                },
            });
        }

        if (!Alpine.store('confirm')) {
            Alpine.store('confirm', {
                open: false,
                title: 'Please confirm',
                message: 'Are you sure you want to continue?',
                confirmText: 'Continue',
                cancelText: 'Cancel',
                tone: 'danger',
                resolver: null,
                openDialog(options = {}) {
                    this.title = options.title || 'Please confirm';
                    this.message = options.message || 'Are you sure you want to continue?';
                    this.confirmText = options.confirmText || 'Continue';
                    this.cancelText = options.cancelText || 'Cancel';
                    this.tone = options.tone || 'danger';
                    this.open = true;

                    return new Promise(resolve => {
                        this.resolver = resolve;
                    });
                },
                approve() {
                    this.open = false;
                    this.resolver?.(true);
                    this.resolver = null;
                },
                cancel() {
                    this.open = false;
                    this.resolver?.(false);
                    this.resolver = null;
                },
            });
        }
    });

    const fallbackConfirm = (input) => {
        const message = typeof input === 'string'
            ? input
            : (input?.message || 'Are you sure you want to continue?');

        return Promise.resolve(window.confirm(message));
    };

    window.showLomoToast = function(message, type = 'info', duration = 4500) {
        if (window.Alpine && Alpine.store('toast')) {
            Alpine.store('toast').show(message, type, duration);
            return;
        }

        if (type === 'error') {
            console.error(message);
        } else {
            console.log(message);
        }
    };

    window.showLomoConfirm = function(input) {
        if (!window.Alpine || !Alpine.store('confirm')) {
            return fallbackConfirm(input);
        }

        if (typeof input === 'string') {
            return Alpine.store('confirm').openDialog({ message: input });
        }

        return Alpine.store('confirm').openDialog(input || {});
    };

    const extractConfirmMessage = (source) => {
        if (!source || !/return\s+confirm\(/i.test(source)) {
            return null;
        }

        const match = source.match(/confirm\((['"])((?:\\.|(?!\1).)*)\1\)/i);
        if (!match) {
            return null;
        }

        return match[2]
            .replace(/\\'/g, "'")
            .replace(/\\"/g, '"')
            .replace(/\\n/g, '\n');
    };

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || form.dataset.lomoConfirmBypass === '1') {
            return;
        }

        const handler = form.getAttribute('onsubmit');
        const message = extractConfirmMessage(handler);

        if (!message) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        window.showLomoConfirm({
            title: 'Confirm action',
            message,
            confirmText: 'Yes, continue',
            tone: 'danger',
        }).then((confirmed) => {
            if (!confirmed) {
                return;
            }

            form.dataset.lomoConfirmBypass = '1';
            form.submit();
            window.setTimeout(() => {
                delete form.dataset.lomoConfirmBypass;
            }, 0);
        });
    }, true);

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[onclick]');
        if (!trigger || trigger.dataset.lomoConfirmBypass === '1') {
            return;
        }

        const handler = trigger.getAttribute('onclick');
        const message = extractConfirmMessage(handler);

        if (!message) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        window.showLomoConfirm({
            title: 'Confirm action',
            message,
            confirmText: 'Yes, continue',
            tone: 'danger',
        }).then((confirmed) => {
            if (!confirmed) {
                return;
            }

            const form = trigger.form || trigger.closest('form');
            if (form instanceof HTMLFormElement) {
                trigger.dataset.lomoConfirmBypass = '1';

                if (typeof form.requestSubmit === 'function' && (trigger instanceof HTMLButtonElement || trigger instanceof HTMLInputElement)) {
                    form.requestSubmit(trigger);
                } else {
                    form.submit();
                }

                window.setTimeout(() => {
                    delete trigger.dataset.lomoConfirmBypass;
                }, 0);

                return;
            }

            if (trigger instanceof HTMLAnchorElement && trigger.href) {
                window.location.assign(trigger.href);
            }
        });
    }, true);
})();
</script>

<div x-data="{
    title(type) {
        return {
            success: 'Saved',
            error: 'Problem',
            info: 'Notice',
            warning: 'Check this',
            chat: 'Live Chat',
        }[type] || 'Notice';
    }
}">
    <div class="pointer-events-none fixed inset-x-4 top-4 z-[9999] flex flex-col items-end gap-3 sm:inset-x-auto sm:right-4 sm:w-[420px]">
        <template x-for="toast in $store.toast.items" :key="toast.id">
            <div
                x-cloak
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-1 scale-[0.98]"
                class="pointer-events-auto relative w-full overflow-hidden rounded-[22px] border border-[#083321]/10 bg-white/95 shadow-[0_20px_80px_-28px_rgba(8,51,33,0.42)] backdrop-blur"
            >
                <div
                    class="absolute inset-y-0 left-0 w-1.5"
                    :class="{
                        'bg-[#0f766e]': toast.type === 'success',
                        'bg-[#dc2626]': toast.type === 'error',
                        'bg-[#0284c7]': toast.type === 'info',
                        'bg-[#d97706]': toast.type === 'warning',
                        'bg-[#FEBC11]': toast.type === 'chat',
                    }"
                ></div>

                <div class="flex items-start gap-3 px-5 py-4">
                    <div
                        class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl"
                        :class="{
                            'bg-emerald-50 text-[#0f766e]': toast.type === 'success',
                            'bg-red-50 text-[#dc2626]': toast.type === 'error',
                            'bg-sky-50 text-[#0284c7]': toast.type === 'info',
                            'bg-amber-50 text-[#d97706]': toast.type === 'warning',
                            'bg-[#FEBC11]/20 text-[#083321]': toast.type === 'chat',
                        }"
                    >
                        <template x-if="toast.type === 'success'">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1"/><circle cx="12" cy="12" r="9"/></svg>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18A2 2 0 003.53 21h16.94a2 2 0 001.71-3l-8.47-14.14a2 2 0 00-3.42 0z"/></svg>
                        </template>
                        <template x-if="toast.type === 'chat'">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 11.5a8.38 8.38 0 01-1.9 5.4 8.5 8.5 0 01-6.6 3.1 8.38 8.38 0 01-5.4-1.9L3 20l1.9-4.1A8.5 8.5 0 1112.5 20"/></svg>
                        </template>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-gray-400" x-text="title(toast.type)"></p>
                        <p class="mt-1 text-sm font-medium leading-6 text-[#131414]" x-text="toast.message"></p>
                    </div>

                    <button type="button" @click="$store.toast.dismiss(toast.id)" class="shrink-0 rounded-full p-1 text-gray-300 transition hover:bg-black/5 hover:text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="h-1 bg-black/5">
                    <div
                        class="h-full origin-left"
                        :class="{
                            'bg-[#0f766e]': toast.type === 'success',
                            'bg-[#dc2626]': toast.type === 'error',
                            'bg-[#0284c7]': toast.type === 'info',
                            'bg-[#d97706]': toast.type === 'warning',
                            'bg-[#FEBC11]': toast.type === 'chat',
                        }"
                        :style="'animation: lomo-toast-progress ' + toast.duration + 'ms linear forwards'"
                    ></div>
                </div>
            </div>
        </template>
    </div>

    <div
        x-cloak
        x-show="$store.confirm.open"
        x-transition.opacity
        @keydown.escape.window="$store.confirm.cancel()"
        class="fixed inset-0 z-[10000] flex items-center justify-center bg-[#131414]/55 px-4 py-6"
        style="display: none;"
    >
        <div class="absolute inset-0" @click="$store.confirm.cancel()"></div>
        <div
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 translate-y-3 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-180"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
            class="relative w-full max-w-md overflow-hidden rounded-[28px] border border-[#083321]/10 bg-white shadow-[0_30px_120px_-30px_rgba(8,51,33,0.5)]"
        >
            <div class="absolute inset-x-0 top-0 h-1.5" :class="$store.confirm.tone === 'danger' ? 'bg-red-500' : 'bg-[#083321]'"></div>

            <div class="px-7 py-6">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl"
                        :class="$store.confirm.tone === 'danger' ? 'bg-red-50 text-red-600' : 'bg-[#FEBC11]/20 text-[#083321]'"
                    >
                        <template x-if="$store.confirm.tone === 'danger'">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18A2 2 0 003.53 21h16.94a2 2 0 001.71-3l-8.47-14.14a2 2 0 00-3.42 0z"/></svg>
                        </template>
                        <template x-if="$store.confirm.tone !== 'danger'">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </template>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.26em] text-gray-400">Confirmation</p>
                        <h3 class="mt-2 text-xl font-semibold text-[#131414]" x-text="$store.confirm.title"></h3>
                        <p class="mt-3 text-sm leading-6 text-gray-600" x-text="$store.confirm.message"></p>
                    </div>
                </div>

                <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" @click="$store.confirm.cancel()" class="inline-flex items-center justify-center rounded-2xl border border-gray-200 px-5 py-3 text-sm font-semibold text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
                        <span x-text="$store.confirm.cancelText"></span>
                    </button>
                    <button
                        type="button"
                        @click="$store.confirm.approve()"
                        class="inline-flex items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold text-white transition"
                        :class="$store.confirm.tone === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-[#083321] hover:bg-[#062719]'"
                    >
                        <span x-text="$store.confirm.confirmText"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>