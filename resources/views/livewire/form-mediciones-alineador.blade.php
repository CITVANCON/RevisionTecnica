<div class="space-y-6">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-4">
            {{-- Cabecera Principal Dividida en 2 --}}
            <div class="grid grid-cols-6 text-center">
                <x-label class="col-span-3 pb-1" value="Prueba de ALINEAMIENTO" />
                <x-label class="col-span-3 pb-1" value="Profundidad de NEUMATICOS" />
            </div>
            {{-- Encabezado de columnas --}}
            <div class="grid grid-cols-6 gap-4 text-center mt-2">
                <x-label value="Ejes" />
                <x-label value="Desviación (m/Km)" />
                <x-label value="Resultado" />
                <x-label value="Izquierda (mm)" />
                <x-label value="Derecha (mm)" />
                <x-label value="Resultado" />
            </div>
            <div class="border-t border-gray-300 mt-2">
                <div class="grid grid-cols-6 gap-4 items-center mt-2">
                    <x-label value="Eje 1A" />
                    <x-input type="number" wire:model.lazy="eje1ADesviacion" class="w-full" />
                    <x-input-error for="eje1ADesviacion" />
                    <x-input type="text" wire:model="eje1AResultado" class="w-full" disabled />
                    <x-input-error for="eje1AResultado" />

                    <x-input type="number" wire:model="eje1RMedidaIzquierda" class="w-full" />
                    <x-input-error for="eje1RMedidaIzquierda" />
                    <x-input type="number" wire:model.lazy="eje1RMedidaDerecha" class="w-full" />
                    <x-input-error for="eje1RMedidaDerecha" />
                    <x-input type="text" wire:model="eje1RMedidaResultado" class="w-full" disabled />
                    <x-input-error for="eje1RMedidaResultado" />
                </div>
                <div class="grid grid-cols-6 gap-4 items-center mt-2">
                    <x-label value="Eje 2A" />
                    <x-input type="number" wire:model.lazy="eje2ADesviacion" class="w-full" />
                    <x-input-error for="eje2ADesviacion" />
                    <x-input type="text" wire:model="eje2AResultado" class="w-full" disabled />
                    <x-input-error for="eje2AResultado" />

                    <x-input type="number" wire:model="eje2RMedidaIzquierda" class="w-full" />
                    <x-input-error for="eje2RMedidaIzquierda" />
                    <x-input type="number" wire:model.lazy="eje2RMedidaDerecha" class="w-full" />
                    <x-input-error for="eje2RMedidaDerecha" />
                    <x-input type="text" wire:model="eje2RMedidaResultado" class="w-full" disabled />
                    <x-input-error for="eje2RMedidaResultado" />
                </div>
            </div>
        </div>
    </div>

    {{-- Botón Guardar --}}
    <div class="mt-4 text-center">
        <x-button wire:click="guardar" wire:loading.attr="disabled" wire:target="guardar"
            class="bg-orange-500 hover:bg-orange-600">
            Guardar Medición
        </x-button>
    </div>
</div>
