<div class="space-y-6">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-4">
            {{-- Encabezado de 7Vcolumnas --}}    
            <div class="grid grid-cols-7 gap-2 text-center mt-2">
                <x-label value="Ejes" />
                <x-label value="Áng. Izquierda 1" />
                <x-label value="Áng. Izquierda 2" />
                <x-label value="Áng. Derecha 1" />
                <x-label value="Áng. Derecha 2" />
                <x-label value="Dif. Izquierda" />
                <x-label value="Dif. Derecha" />
            </div>
            <div class="border-t border-gray-300 mt-2">
                <!-- FILA 1 -->
                <div class="grid grid-cols-7 gap-4 items-center mt-2">
                    <x-label value="Eje 1A" />
                    <x-input type="number" wire:model="eje1AngIzquierda1" class="w-full" />
                    <x-input-error for="eje1AngIzquierda1" />
                    <x-input type="number" wire:model="eje1AngIzquierda2" class="w-full" />
                    <x-input-error for="eje1AngIzquierda2" />
                    <x-input type="number" wire:model="eje1AngDerecha1" class="w-full" />
                    <x-input-error for="eje1AngDerecha1" />
                    <x-input type="number" wire:model="eje1AngDerecha2" class="w-full" />
                    <x-input-error for="eje1AngDerecha2" />
                    <x-input type="number" wire:model="eje1AngDifIzquierda" class="w-full" />
                    <x-input-error for="eje1AngDifIzquierda" />
                    <x-input type="number" wire:model="eje1AngDifDerecha" class="w-full" />
                    <x-input-error for="eje1AngDifDerecha" />
                </div>
                <!-- FILA 2 -->
                <div class="grid grid-cols-7 gap-4 items-center mt-2">
                    <x-label value="Eje 2A" />
                    <x-input type="number" wire:model="eje2AngIzquierda1" class="w-full" />
                    <x-input-error for="eje2AngIzquierda1" />
                    <x-input type="number" wire:model="eje2AngIzquierda2" class="w-full" />
                    <x-input-error for="eje2AngIzquierda2" />
                    <x-input type="number" wire:model="eje2AngDerecha1" class="w-full" />
                    <x-input-error for="eje2AngDerecha1" />
                    <x-input type="number" wire:model="eje2AngDerecha2" class="w-full" />
                    <x-input-error for="eje2AngDerecha2" />
                    <x-input type="number" wire:model="eje2AngDifIzquierda" class="w-full" />
                    <x-input-error for="eje2AngDifIzquierda" />
                    <x-input type="number" wire:model="eje2AngDifDerecha" class="w-full" />
                    <x-input-error for="eje2AngDifDerecha" />
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
