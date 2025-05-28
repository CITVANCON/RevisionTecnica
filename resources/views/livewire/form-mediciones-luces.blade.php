<div class="space-y-6">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-4">
            <div class="grid grid-cols-5 gap-4 text-center">
                <x-label value="Tipo de Luz" />
                <x-label value="Medida (I)(Lux)" />
                <x-label value="Medida (D)(Lux)" />
                <x-label value="Alineamiento" />
                <x-label value="Resultado" />
            </div>
            <div class="border-t border-gray-300 mt-2">
                {{-- Luz Baja --}}
                <div class="grid grid-cols-5 gap-4 items-center mt-2">
                    <x-label value="Luz Baja" />
                    <x-input type="number" wire:model="luzBajaIzquierda" class="w-full" />
                    <x-input-error for="luzBajaIzquierda" />
                    <x-input type="number" wire:model="luzBajaDerecha" class="w-full" />
                    <x-input-error for="luzBajaDerecha" />
                    <x-input wire:model="luzBajaAlineamiento" class="w-full" list="items" />
                    <datalist id="items">
                        <option value="OK">OK</option>
                        <option value="OK/">OK/</option>
                        <option value="/OK">/OK</option>
                        <option value="OK/OK">OK/OK</option>
                        <option value="OK/IZQUIERDA">OK/IZQUIERDA</option>
                        <option value="OK/DERECHA">OK/DERECHA</option>
                        <option value="OK/INFERIOR">OK/INFERIOR</option>
                        <option value="OK/SUPERIOR">OK/SUPERIOR</option>
                        <option value="IZQUIERDA/OK">IZQUIERDA/OK</option>
                        <option value="IZQUIERDA/IZQUIERDA">IZQUIERDA/IZQUIERDA</option>
                        <option value="IZQUIERDA/DERECHA">IZQUIERDA/DERECHA</option>
                        <option value="IZQUIERDA/INFERIOR">IZQUIERDA/INFERIOR</option>
                        <option value="IZQUIERDA/SUPERIOR">IZQUIERDA/SUPERIOR</option>
                        <option value="DERECHA/OK">DERECHA/OK</option>
                        <option value="DERECHA/IZQUIERDA">DERECHA/IZQUIERDA</option>
                        <option value="DERECHA/DERECHA">DERECHA/DERECHA</option>
                        <option value="DERECHA/INFERIOR">DERECHA/INFERIOR</option>
                        <option value="DERECHA/SUPERIOR">DERECHA/SUPERIOR</option>
                        <option value="INFERIOR/OK">INFERIOR/OK</option>
                        <option value="INFERIOR/IZQUIERDA">INFERIOR/IZQUIERDA</option>
                        <option value="INFERIOR/DERECHA">INFERIOR/DERECHA</option>
                        <option value="INFERIOR/INFERIOR">INFERIOR/INFERIOR</option>
                        <option value="INFERIOR/SUPERIOR">INFERIOR/SUPERIOR</option>
                        <option value="SUPERIOR/OK">SUPERIOR/OK</option>
                        <option value="SUPERIOR/IZQUIERDA">SUPERIOR/IZQUIERDA</option>
                        <option value="SUPERIOR/DERECHA">SUPERIOR/DERECHA</option>
                        <option value="SUPERIOR/INFERIOR">SUPERIOR/INFERIOR</option>
                        <option value="SUPERIOR/SUPERIOR">SUPERIOR/SUPERIOR</option>
                    </datalist>
                    <x-input-error for="luzBajaAlineamiento" />
                    <x-input type="text" wire:model="luzBajaResultado" class="w-full" disabled />
                    <x-input-error for="luzBajaResultado" />
                </div>

                {{-- Luz Alta --}}
                <div class="grid grid-cols-5 gap-4 items-center mt-2">
                    <x-label value="Luz Alta" />
                    <x-input type="number" wire:model="luzAltaIzquierda" class="w-full" />
                    <x-input-error for="luzAltaIzquierda" />
                    <x-input type="number" wire:model="luzAltaDerecha" class="w-full" />
                    <x-input-error for="luzAltaDerecha" />
                    <x-input wire:model="luzAltaAlineamiento" class="w-full" list="items2" />
                    <datalist id="items2">
                        <option value="OK">OK</option>
                        <option value="OK/">OK/</option>
                        <option value="/OK">/OK</option>
                        <option value="OK/OK">OK/OK</option>
                        <option value="OK/IZQUIERDA">OK/IZQUIERDA</option>
                        <option value="OK/DERECHA">OK/DERECHA</option>
                        <option value="OK/INFERIOR">OK/INFERIOR</option>
                        <option value="OK/SUPERIOR">OK/SUPERIOR</option>
                        <option value="IZQUIERDA/OK">IZQUIERDA/OK</option>
                        <option value="IZQUIERDA/IZQUIERDA">IZQUIERDA/IZQUIERDA</option>
                        <option value="IZQUIERDA/DERECHA">IZQUIERDA/DERECHA</option>
                        <option value="IZQUIERDA/INFERIOR">IZQUIERDA/INFERIOR</option>
                        <option value="IZQUIERDA/SUPERIOR">IZQUIERDA/SUPERIOR</option>
                        <option value="DERECHA/OK">DERECHA/OK</option>
                        <option value="DERECHA/IZQUIERDA">DERECHA/IZQUIERDA</option>
                        <option value="DERECHA/DERECHA">DERECHA/DERECHA</option>
                        <option value="DERECHA/INFERIOR">DERECHA/INFERIOR</option>
                        <option value="DERECHA/SUPERIOR">DERECHA/SUPERIOR</option>
                        <option value="INFERIOR/OK">INFERIOR/OK</option>
                        <option value="INFERIOR/IZQUIERDA">INFERIOR/IZQUIERDA</option>
                        <option value="INFERIOR/DERECHA">INFERIOR/DERECHA</option>
                        <option value="INFERIOR/INFERIOR">INFERIOR/INFERIOR</option>
                        <option value="INFERIOR/SUPERIOR">INFERIOR/SUPERIOR</option>
                        <option value="SUPERIOR/OK">SUPERIOR/OK</option>
                        <option value="SUPERIOR/IZQUIERDA">SUPERIOR/IZQUIERDA</option>
                        <option value="SUPERIOR/DERECHA">SUPERIOR/DERECHA</option>
                        <option value="SUPERIOR/INFERIOR">SUPERIOR/INFERIOR</option>
                        <option value="SUPERIOR/SUPERIOR">SUPERIOR/SUPERIOR</option>
                    </datalist>
                    <x-input-error for="luzAltaAlineamiento" />
                    <x-input type="text" wire:model="luzAltaResultado" class="w-full" disabled />
                    <x-input-error for="luzAltaResultado" />
                </div>

                {{-- Luz Alta Adicional --}}
                <div class="grid grid-cols-5 gap-4 items-center mt-2">
                    <x-label value="Luz Alta adic." />
                    <x-input type="number" wire:model="luzAltaAdicionalIzquierda" class="w-full" />
                    <x-input-error for="luzAltaAdicionalIzquierda" />
                    <x-input type="number" wire:model="luzAltaAdicionalDerecha" class="w-full" />
                    <x-input-error for="luzAltaAdicionalDerecha" />
                    <x-input wire:model="luzAltaAdicionalAlineamiento" class="w-full" list="items3" />
                    <datalist id="items3">
                        <option value="OK">OK</option>
                        <option value="OK/">OK/</option>
                        <option value="/OK">/OK</option>
                        <option value="OK/OK">OK/OK</option>
                        <option value="OK/IZQUIERDA">OK/IZQUIERDA</option>
                        <option value="OK/DERECHA">OK/DERECHA</option>
                        <option value="OK/INFERIOR">OK/INFERIOR</option>
                        <option value="OK/SUPERIOR">OK/SUPERIOR</option>
                        <option value="IZQUIERDA/OK">IZQUIERDA/OK</option>
                        <option value="IZQUIERDA/IZQUIERDA">IZQUIERDA/IZQUIERDA</option>
                        <option value="IZQUIERDA/DERECHA">IZQUIERDA/DERECHA</option>
                        <option value="IZQUIERDA/INFERIOR">IZQUIERDA/INFERIOR</option>
                        <option value="IZQUIERDA/SUPERIOR">IZQUIERDA/SUPERIOR</option>
                        <option value="DERECHA/OK">DERECHA/OK</option>
                        <option value="DERECHA/IZQUIERDA">DERECHA/IZQUIERDA</option>
                        <option value="DERECHA/DERECHA">DERECHA/DERECHA</option>
                        <option value="DERECHA/INFERIOR">DERECHA/INFERIOR</option>
                        <option value="DERECHA/SUPERIOR">DERECHA/SUPERIOR</option>
                        <option value="INFERIOR/OK">INFERIOR/OK</option>
                        <option value="INFERIOR/IZQUIERDA">INFERIOR/IZQUIERDA</option>
                        <option value="INFERIOR/DERECHA">INFERIOR/DERECHA</option>
                        <option value="INFERIOR/INFERIOR">INFERIOR/INFERIOR</option>
                        <option value="INFERIOR/SUPERIOR">INFERIOR/SUPERIOR</option>
                        <option value="SUPERIOR/OK">SUPERIOR/OK</option>
                        <option value="SUPERIOR/IZQUIERDA">SUPERIOR/IZQUIERDA</option>
                        <option value="SUPERIOR/DERECHA">SUPERIOR/DERECHA</option>
                        <option value="SUPERIOR/INFERIOR">SUPERIOR/INFERIOR</option>
                        <option value="SUPERIOR/SUPERIOR">SUPERIOR/SUPERIOR</option>
                    </datalist>
                    <x-input-error for="luzAltaAdicionalAlineamiento" />
                    <x-input type="text" wire:model="luzAltaAdicionalResultado" class="w-full" disabled />
                    <x-input-error for="luzAltaAdicionalResultado" />
                </div>

                {{-- Luz Neblinera --}}
                <div class="grid grid-cols-5 gap-4 items-center mt-2">
                    <x-label value="Luz Neblinera" />
                    <x-input type="number" wire:model="luzNeblineraIzquierda" class="w-full" />
                    <x-input-error for="luzNeblineraIzquierda" />
                    <x-input type="number" wire:model="luzNeblineraDerecha" class="w-full" />
                    <x-input-error for="luzNeblineraDerecha" />
                    <x-input wire:model="luzNeblineraAlineamiento" class="w-full" list="items4" />
                    <datalist id="items4">
                        <option value="OK">OK</option>
                        <option value="OK/">OK/</option>
                        <option value="/OK">/OK</option>
                        <option value="OK/OK">OK/OK</option>
                        <option value="OK/IZQUIERDA">OK/IZQUIERDA</option>
                        <option value="OK/DERECHA">OK/DERECHA</option>
                        <option value="OK/INFERIOR">OK/INFERIOR</option>
                        <option value="OK/SUPERIOR">OK/SUPERIOR</option>
                        <option value="IZQUIERDA/OK">IZQUIERDA/OK</option>
                        <option value="IZQUIERDA/IZQUIERDA">IZQUIERDA/IZQUIERDA</option>
                        <option value="IZQUIERDA/DERECHA">IZQUIERDA/DERECHA</option>
                        <option value="IZQUIERDA/INFERIOR">IZQUIERDA/INFERIOR</option>
                        <option value="IZQUIERDA/SUPERIOR">IZQUIERDA/SUPERIOR</option>
                        <option value="DERECHA/OK">DERECHA/OK</option>
                        <option value="DERECHA/IZQUIERDA">DERECHA/IZQUIERDA</option>
                        <option value="DERECHA/DERECHA">DERECHA/DERECHA</option>
                        <option value="DERECHA/INFERIOR">DERECHA/INFERIOR</option>
                        <option value="DERECHA/SUPERIOR">DERECHA/SUPERIOR</option>
                        <option value="INFERIOR/OK">INFERIOR/OK</option>
                        <option value="INFERIOR/IZQUIERDA">INFERIOR/IZQUIERDA</option>
                        <option value="INFERIOR/DERECHA">INFERIOR/DERECHA</option>
                        <option value="INFERIOR/INFERIOR">INFERIOR/INFERIOR</option>
                        <option value="INFERIOR/SUPERIOR">INFERIOR/SUPERIOR</option>
                        <option value="SUPERIOR/OK">SUPERIOR/OK</option>
                        <option value="SUPERIOR/IZQUIERDA">SUPERIOR/IZQUIERDA</option>
                        <option value="SUPERIOR/DERECHA">SUPERIOR/DERECHA</option>
                        <option value="SUPERIOR/INFERIOR">SUPERIOR/INFERIOR</option>
                        <option value="SUPERIOR/SUPERIOR">SUPERIOR/SUPERIOR</option>
                    </datalist>
                    <x-input-error for="luzNeblineraAlineamiento" />
                    <x-input type="text" wire:model="luzNeblineraResultado" class="w-full" disabled />
                    <x-input-error for="luzNeblineraResultado" />
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
