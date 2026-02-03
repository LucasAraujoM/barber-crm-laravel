<div x-data="{ open: false }" @keydown.escape.window="open = false">
    <!-- Trigger Button -->
    <button @click="open = true" type="button"
        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Agregar Miembro
    </button>

    <!-- Modal Backdrop -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40" style="display: none;"></div>

    <!-- Modal Dialog -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100"
                @click.away="open = false">

                <!-- Modal Header -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Nuevo Miembro del
                        Personal</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <!-- Avatar Upload -->
                        <div x-data x-init="
                                FilePond.create($refs.input, {
                                    storeAsFile: true,
                                    name: 'avatar',
                                    labelIdle: `Arrastra y suelta la foto o <span class='filepond--label-action'>Explorar</span>`,
                                    imagePreviewHeight: 100,
                                    imageCropAspectRatio: '1:1',
                                    imageResizeTargetWidth: 200,
                                    imageResizeTargetHeight: 200,
                                    stylePanelLayout: 'compact circle',
                                    styleLoadIndicatorPosition: 'center bottom',
                                    styleProgressIndicatorPosition: 'center bottom',
                                    styleButtonRemoveItemPosition: 'center bottom',
                                    styleButtonProcessItemPosition: 'center bottom',
                                });
                            ">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil</label>
                            <input type="file" x-ref="input" name="avatar" accept="image/png, image/jpeg, image/gif" />
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name" required
                                    class="block w-full pl-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2"
                                    placeholder="Jane Doe">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo
                                Electrónico</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" required
                                    class="block w-full pl-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2"
                                    placeholder="jane@example.com">
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Número de
                                Teléfono</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                </div>
                                <input type="text" name="phone" id="phone"
                                    class="block w-full pl-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2"
                                    placeholder="+1 (555) 000-0000">
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                            <div class="mt-1">
                                <select id="role" name="role"
                                    class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                    <option value="Staff">Personal</option>
                                    <option value="Manager">Gerente</option>
                                    <option value="Admin">Administrador</option>
                                </select>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notas</label>
                            <div class="mt-1">
                                <textarea id="notes" name="notes" rows="3"
                                    class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 p-2"
                                    placeholder="Información adicional..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100 rounded-b-xl">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">
                            Guardar
                        </button>
                        <button type="button" @click="open = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>