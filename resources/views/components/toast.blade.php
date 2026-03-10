{{--
Componente Toast Global — x-toast
──────────────────────────────────────────────────────────
Uso desde Alpine.js: $dispatch('toast', { message: '...', type: 'warning' })
Uso desde JS vanilla: window.toast('Mensaje', 'success')
Tipos disponibles: warning | success | error | info
--}}
<div x-data="toastManager()" x-on:toast.window="show($event.detail.message, $event.detail.type ?? 'warning')"
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[200] flex flex-col items-center gap-2 pointer-events-none"
    style="min-width: 280px; max-width: 380px;">

    <template x-for="(t, i) in toasts" :key="t.id">
        <div x-show="t.visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative flex items-center gap-3 w-full px-5 py-4 rounded-2xl shadow-2xl shadow-black/15 pointer-events-auto overflow-hidden select-none"
            :class="{
                'bg-amber-500':   t.type === 'warning',
                'bg-emerald-600': t.type === 'success',
                'bg-rose-600':    t.type === 'error',
                'bg-blue-500':    t.type === 'info',
            }"
            style="color: #ffffff !important;">

            {{-- ícono según tipo --}}
            <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-white/20">

                {{-- warning --}}
                <svg x-show="t.type === 'warning'" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
                {{-- success --}}
                <svg x-show="t.type === 'success'" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                {{-- error --}}
                <svg x-show="t.type === 'error'" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
                {{-- info --}}
                <svg x-show="t.type === 'info'" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
            </div>

            {{-- mensaje --}}
            <p class="flex-1 text-sm font-semibold leading-snug" style="color: #ffffff !important;" x-text="t.message"></p>

            {{-- botón × --}}
            <button type="button" @click="dismiss(i)"
                class="shrink-0 ml-1 p-1 rounded-lg hover:bg-white/15 opacity-60 hover:opacity-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>

            {{-- barra de progreso --}}
            <div class="absolute bottom-0 left-0 h-[3px] rounded-full bg-white/40"
                 :style="`animation: toastProgress ${t.duration}ms linear forwards;`">
            </div>
        </div>
    </template>
</div>

{{-- ── Leer flash de sesión de Laravel ─────────────────────────────────── --}}
@if(session('success'))
    <div x-data x-init="$dispatch('toast', { message: @js(session('success')), type: 'success' })"></div>
@endif
@if(session('error'))
    <div x-data x-init="$dispatch('toast', { message: @js(session('error')), type: 'error' })"></div>
@endif
@if(session('warning'))
    <div x-data x-init="$dispatch('toast', { message: @js(session('warning')), type: 'warning' })"></div>
@endif
@if(session('info'))
    <div x-data x-init="$dispatch('toast', { message: @js(session('info')), type: 'info' })"></div>
@endif

<style>
    @keyframes toastProgress {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toastManager', () => ({
            toasts: [],
            _next: 0,

            show(message, type = 'warning', duration = 4000) {
                const id = this._next++;
                this.toasts.push({ id, message, type, duration, visible: true });
                setTimeout(() => this.dismiss(this.toasts.findIndex(t => t.id === id)), duration);
            },

            dismiss(index) {
                if (index < 0 || index >= this.toasts.length) return;
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts.splice(this.toasts.findIndex((t, i) => i === index), 1);
                }, 300);
            }
        }));
    });

    // API global para uso desde JS vanilla
    window.toast = (message, type = 'warning', duration = 4000) => {
        window.dispatchEvent(new CustomEvent('toast', { detail: { message, type, duration } }));
    };
</script>