@props(['tipo_cliente', 'tipo_documento', 'numero_documento'])
<div class="max-w-5xl m-auto bg-white rounded-lg shadow-md mb-4">
    <div class="flex items-center justify-between bg-accent py-4 px-6 rounded-t-lg">
        <span class="text-lg font-semibold text-white">Actualizar Datos del Cliente</span>
        <span class="px-3 py-1 text-sm font-bold text-yellow-100 bg-yellow-700 rounded">Modo Edición</span>
    </div>
    
    <div class="mt-2 mb-6 px-8 py-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            {{-- Campos Bloqueados (No se edita el DNI o Tipo en este modo para mantener integridad) --}}
            <div>
                <x-label value="Tipo Cliente:" />
                <x-input type="text" value="{{ $tipo_cliente }}" class="w-full bg-gray-100" disabled/>
            </div>
            <div>
                <x-label value="Documento:" />
                <x-input type="text" value="{{ $tipo_documento }}" class="w-full bg-gray-100" disabled/>
            </div>
            <div>
                <x-label value="Nro. Documento:" />
                <x-input type="text" value="{{ $numero_documento }}" class="w-full bg-gray-100" disabled/>
            </div>

            {{-- Campos Editables --}}
            <div class="sm:col-span-2">
                <x-label value="Nombres / Razón Social:" />
                <x-input type="text" wire:model="nombre_razon_social" class="w-full uppercase" />
                <x-input-error for="nombre_razon_social" />
            </div>

            <div class="sm:col-span-1">
                <x-label value="Dirección:" />
                <x-input type="text" wire:model="direccion" class="w-full uppercase" />
            </div>

            <div>
                <x-label value="Teléfono:" />
                <x-input type="text" wire:model="telefono" class="w-full" />
            </div>
            <div>
                <x-label value="Email:" />
                <x-input type="email" wire:model="email" class="w-full" />
            </div>
        </div>

        <div class="mt-6 flex justify-center space-x-4">
            <button wire:click="$set('estado', 'cargado')" 
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded font-bold transition">
                Cancelar
            </button>
            
            <button wire:click="actualizarCliente" wire:loading.attr="disabled"
                class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded font-bold transition">
                <span wire:loading wire:target="actualizarCliente">
                    <i class="fas fa-spinner animate-spin mr-2"></i>
                </span>
                Guardar Cambios
            </button>
        </div>
    </div>
</div>