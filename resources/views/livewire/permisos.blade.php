<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="items-center pb-6 md:block sm:block">
            <div class="px-2 w-64 mb-4 md:w-full">
                <h2 class="text-gray-600 font-semibold text-2xl">
                    <i class="fas fa-key mr-2"></i>Permisos
                </h2>
                <span class="text-xs">Acciones específicas del sistema</span>
            </div>

            <div class="w-full items-center md:flex md:justify-between">
                {{-- Selector de cantidad --}}
                <div class="flex bg-gray-50 items-center p-2 rounded-md mb-4">
                    <span>Mostrar</span>
                    <select wire:model.live="cant" class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none ml-1 block">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <span>Entradas</span>
                </div>

                {{-- Buscador --}}
                <div class="flex bg-gray-50 items-center lg:w-3/6 p-2 rounded-md mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    <input class="bg-gray-50 outline-none block rounded-md border-indigo-500 w-full border-none focus:ring-0" type="text"
                        wire:model.live="search" placeholder="Buscar permiso...">
                </div>

                <div class="mb-4">
                    <button wire:click="create" class="bg-orange-500 px-6 py-4 rounded-md text-white font-semibold tracking-wide cursor-pointer hover:bg-orange-600 transition">
                        Nuevo Permiso &nbsp;<i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        @if ($permisos->count())
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal rounded-md overflow-hidden">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Nombre</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Descripción</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permisos as $item)
                            <tr wire:key="perm-{{ $item->id }}">
                                <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->id }}
                                </td>
                                <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm font-medium text-gray-700 italic">
                                    {{ $item->name }}
                                </td>
                                <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm font-medium text-gray-700 italic">
                                    {{ $item->description ?? 'Sin descripción' }}
                                </td>
                                <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm text-right">
                                    <div class="flex items-center">
                                        <button wire:click="edit({{ $item->id }})" class="py-2 px-3 rounded-md bg-lime-500 font-bold text-white hover:bg-lime-600 transition mr-2">
                                            <i class="fa-solid fa-pencil"></i>
                                        </button>
                                        <button wire:click="delete({{ $item->id }})"
                                                onclick="confirm('¿Eliminar este permiso?') || event.stopImmediatePropagation()"
                                                class="py-2 px-3 rounded-md bg-red-500 font-bold text-white hover:bg-red-600 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $permisos->links() }}</div>
        @else
            <div class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                No hay permisos registrados con "{{ $search }}".
            </div>
        @endif
    </div>

    {{-- Modal --}}
    <x-dialog-modal wire:model.live="open_edit">
        <x-slot name="title">{{ $permission_id ? 'Editar Permiso' : 'Crear Permiso' }}</x-slot>
        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label value="Nombre del Permiso" />
                    <x-input type="text" class="w-full" wire:model="name" placeholder="Ej: ver-usuarios" />
                    <x-input-error for="name" />
                </div>
                <div>
                    <x-label value="Descripción del Permiso" />
                    <x-input type="text" class="w-full mt-1" wire:model="description" placeholder="¿Qué permite hacer este permiso?" />
                    <x-input-error for="description" />
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open_edit', false)">Cancelar</x-secondary-button>
            <x-danger-button class="ml-3" wire:click="save" wire:loading.attr="disabled">
                {{ $permission_id ? 'Actualizar' : 'Guardar' }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
