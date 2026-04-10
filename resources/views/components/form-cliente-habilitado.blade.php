<!-- resources/views/components/form-cliente-habilitado.blade.php -->
@props(['tipo_cliente', 'tipo_documento'])
<div class="max-w-5xl m-auto bg-white rounded-lg shadow-md mb-4" id="datosCliente">
    <div class="flex items-center justify-between bg-accent py-4 px-6 rounded-t-lg">
        <span class="text-lg font-semibold text-white">Datos del Cliente</span>
        <span class="px-3 py-1 text-sm font-bold text-gray-100 bg-blue-500 rounded">Modo Registro</span>
    </div>
    <div class="mt-2 mb-6 px-8 py-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <x-label value="Tipo Cliente:" />
                <x-select wire:model.live="tipo_cliente" class="w-full">
                    <option value="NATURAL">Persona Natural</option>
                    <option value="JURIDICA">Persona Jurídica (Empresa)</option>
                </x-select>
            </div>

            <div>
                <x-label value="Documento:" />
                <x-select wire:model.live="tipo_documento" class="w-full">
                    @if($tipo_cliente === 'NATURAL')
                        <option value="DNI">DNI</option>
                        <option value="CE">C.E.</option>
                    @else
                        <option value="RUC">RUC</option>
                    @endif
                </x-select>
            </div>

            <div>
                <x-label value="Nro. Documento:" />
                <div class="flex gap-2" wire:key="container-{{ $tipo_documento }}">
                    <x-input type="text" wire:model="numero_documento" class="w-full" maxlength="{{ $tipo_documento == 'RUC' ? 11 : 8 }}" />
                    <button wire:click="buscarCliente" class="bg-indigo-600 px-3 py-2 rounded text-white hover:bg-indigo-700">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <x-input-error for="numero_documento" />
            </div>

            <div class="sm:col-span-2">
                <x-label value="{{ $tipo_cliente == 'NATURAL' ? 'Nombres y Apellidos:' : 'Razón Social:' }}" />
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

        <div class="mt-6 flex justify-center">
            <button wire:click="guardaCliente" wire:loading.attr="disabled"
                class="inline-flex items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded shadow-sm transition">
                <span wire:loading wire:target="guardaCliente">
                    <i class="fas fa-spinner animate-spin mr-2"></i>
                </span>
                Guardar Cliente
            </button>
        </div>
    </div>
</div>