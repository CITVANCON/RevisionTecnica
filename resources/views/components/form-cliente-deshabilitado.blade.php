@props(['tipo_cliente', 'm_tipo_documento', 'm_numero_documento', 'm_nombre_razon_social', 'm_direccion', 'm_telefono', 'm_email'])
<div class="max-w-5xl m-auto bg-white rounded-lg shadow-md" id="datosCliente">
    <div class="flex items-center justify-between bg-accent py-4 px-6 rounded-t-lg text-white">
        <span class="text-lg font-semibold">Datos del cliente</span>
        <i class="fas fa-check-circle fa-lg"></i>
    </div>
    <div class="mt-2 mb-6 px-8 py-2">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <x-label value="Tipo Cliente:" />
                {{--<x-input type="text" value="{{ $tipo_cliente }}" class="w-full bg-gray-100" disabled/>--}}
                <x-input type="text" wire:model="tipo_cliente" class="w-full bg-gray-100" disabled/>
            </div>
            <div>
                <x-label value="Documento:" />
                {{--<x-input type="text" value="{{ $m_tipo_documento }}" class="w-full bg-gray-100" disabled/>--}}
                <x-input type="text" wire:model="m_tipo_documento" class="w-full bg-gray-100" disabled/>
            </div>

            <div>
                <x-label value="Nro. Documento / RUC" />
                {{--<x-input type="text" value="{{ $m_numero_documento }}" class="w-full bg-gray-100" disabled/>--}}
                <x-input type="text" wire:model="m_numero_documento" class="w-full bg-gray-100" disabled/>
            </div>

            <div class="sm:col-span-2">
                <x-label value="Nombres y Apellidos / Razón Social" />
                {{--<x-input type="text" value="{{ $m_nombre_razon_social }}" class="w-full uppercase bg-gray-100" disabled/>--}}
                <x-input type="text" wire:model="m_nombre_razon_social" class="w-full uppercase bg-gray-100" disabled/>
            </div>

            <div class="sm:col-span-1">
                <x-label value="Dirección:" />
                {{--<x-input type="text" value="{{ $m_direccion ?? 'No registrada' }}" class="w-full uppercase bg-gray-100" disabled/>--}}
                <x-input type="text" wire:model="m_direccion" class="w-full uppercase bg-gray-100" disabled/>
            </div>

            <div>
                <x-label value="Teléfono:" />
                {{--<x-input type="text" value="{{ $m_telefono ?? '-' }}" class="w-full bg-gray-100" disabled/>--}}
                <x-input type="text" wire:model="m_telefono" class="w-full bg-gray-100" disabled/>
            </div>
            <div>
                <x-label value="Email:" />
                {{--<x-input type="email" value="{{ $m_email ?? '-' }}" class="w-full bg-gray-100" disabled/>--}}
                <x-input type="email" wire:model="m_email" class="w-full bg-gray-100" disabled/>
            </div>
        </div>

        {{-- 
            <div class="mt-6 flex justify-center gap-4">
                <button wire:click="editarCliente"
                    class="inline-flex items-center px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-medium rounded shadow-sm transition">
                    <i class="fas fa-edit mr-2"></i> Editar cliente
                </button>
                
                <button wire:click="resetForm"
                    class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-medium rounded shadow-sm transition">
                    <i class="fas fa-times mr-2"></i> Quitar
                </button>
            </div>
        --}}
        <div class="mt-4  mb-2 flex flex-row justify-center items-center">
            <a wire:click="editarCliente"
                class="hover:cursor-pointer focus:ring-2 focus:ring-offset-2 focus:ring-amber-600 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded">
                <p class="text-sm font-medium leading-none text-white">Editar cliente</p>
            </a>
        </div>
    </div>
</div>