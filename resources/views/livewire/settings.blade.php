<div class="flex flex-col h-full min-h-0">
    <div class="max-w-2xl mx-auto w-full space-y-4 overflow-y-auto custom-scrollbar flex-1 min-h-0 pr-1">

        <div>
            <h1 class="text-xl font-extrabold">Configuración</h1>
            <p class="text-xs text-base-content/50 mt-0.5">Personalizá tu Barber CRM</p>
        </div>

        <form wire:submit.prevent="save" class="space-y-4">

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title text-sm">Información de la Empresa</h2>
                    <div class="form-control">
                        <label class="label"><span
                                class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Nombre
                                de la Empresa</span></label>
                        <input type="text" wire:model="companyName" class="input input-bordered"
                            placeholder="Barber CRM">
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title text-sm">Seguridad de la App</h2>
                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" wire:model="requirePassword" class="toggle toggle-primary">
                            <span class="label-text font-bold">Requiere contraseña para acceder</span>
                        </label>
                        <p class="text-xs text-base-content/50 mt-1">Si está activado, la app solicitará una contraseña
                            al iniciar.</p>
                    </div>

                    <div x-data="{ show: @entangle('requirePassword') }" x-show="show" x-transition>
                        <div class="grid grid-cols-2 gap-3 mt-3">
                            <div class="form-control">
                                <label class="label"><span
                                        class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Nueva
                                        Contraseña</span></label>
                                <input type="password" wire:model="appPassword" class="input input-bordered"
                                    placeholder="Dejar vacío para no cambiar">
                            </div>
                            <div class="form-control">
                                <label class="label"><span
                                        class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Confirmar
                                        Contraseña</span></label>
                                <input type="password" wire:model="confirmPassword" class="input input-bordered"
                                    placeholder="Repetir contraseña">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title text-sm">Horarios de Atención</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Hora
                                    de Apertura</span></label>
                            <input type="time" wire:model="openTime" class="input input-bordered">
                        </div>
                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-xs font-bold uppercase tracking-wider text-base-content/50">Hora
                                    de Cierre</span></label>
                            <input type="time" wire:model="closeTime" class="input input-bordered">
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <p class="text-xs">Los horarios de apertura y cierre afectan la disponibilidad en el calendario.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                @if ($this->hasPassword)
                    <button type="button" wire:click="logout" class="btn btn-error gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Cerrar Sesión
                    </button>
                @endif
                <button type="submit" class="btn btn-primary gap-2" id="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Guardar Configuración
                </button>
            </div>

        </form>

        @if ($showDisablePasswordModal)
            <div class="modal modal-open bg-base-300/80 backdrop-blur-sm">
                <div class="modal-box max-w-sm">
                    <h3 class="font-bold text-lg text-error">Desactivar Seguridad</h3>
                    <p class="py-4 text-sm">Para desactivar la contraseña, por favor ingresá tu contraseña actual.</p>
                    <div class="form-control">
                        <input type="password" wire:model="verifyPassword"
                            class="input input-bordered @error('verifyPassword') input-error @enderror"
                            placeholder="Contraseña actual" autofocus wire:keydown.enter="save">
                        @error('verifyPassword') <span class="text-error text-xs mt-1 font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-action mt-2">
                        <button type="button" wire:click="cancelDisablePassword" class="btn btn-ghost">Cancelar</button>
                        <button type="button" wire:click="save" class="btn btn-error">Confirmar</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('settings-saved', () => {
                setTimeout(() => {
                    window.location.href = '/configuracion';
                }, 5000);
            });
        });
    </script>
</div>