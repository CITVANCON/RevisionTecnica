<div class="space-y-6">
    <div class="flex flex-col sm:flex-row gap-4 mt-4 justify-center">
        <!-- ANTERIOR -->
        <div class="bg-white shadow-md sm:rounded-lg p-2">
            <div class="text-center font-semibold text-gray-800">
                <x-label class="font-bold pb-1 block" value="ANTERIOR" />
            </div>
            <div class="border-t border-gray-300 mt-2 pt-2 space-y-2">
                <div class="flex gap-2 items-center">
                    <div class="flex flex-col">
                        <x-label value="Izquierda (%)" class="whitespace-nowrap text-sm" />
                        <x-input type="number" wire:model="suspensionDelanteraIzquierda" />
                    </div>
                    <div class="flex flex-col">
                        <x-label value="Derecha (%)" class="whitespace-nowrap text-sm" />
                        <x-input type="number" wire:model="suspensionDelanteraDerecha" />
                    </div>
                </div>
                <div class="flex flex-col">
                    <x-label value="Desviacion (%)" class="whitespace-nowrap text-sm" />
                    <x-input type="number" wire:model="suspensionDelanteraDesviacion" />
                </div>
                <div class="flex flex-col">
                    <x-label value="Resultado" class="whitespace-nowrap text-sm" />
                    <x-input wire:model="suspensionDelanteraResultado" />
                </div>
            </div>
        </div>

        <!-- POSTERIOR -->
        <div class="bg-white shadow-md sm:rounded-lg p-2">
            <div class="text-center font-semibold text-gray-800">
                <x-label class="font-bold pb-1 block" value="POSTERIOR" />
            </div>
            <div class="border-t border-gray-300 mt-2 pt-2 space-y-2">
                <div class="flex gap-2 items-center">
                    <div class="flex flex-col">
                        <x-label value="Izquierda (%)" class="whitespace-nowrap text-sm" />
                        <x-input type="number" wire:model="suspensionPosteriorIzquierda" />
                    </div>
                    <div class="flex flex-col">
                        <x-label value="Derecha (%)" class="whitespace-nowrap text-sm" />
                        <x-input type="number" wire:model="suspensionPosteriorDerecha" />
                    </div>
                </div>
                <div class="flex flex-col">
                    <x-label value="Desviacion (%)" class="whitespace-nowrap text-sm" />
                    <x-input type="number" wire:model="suspensionPosteriorDesviacion" />
                </div>
                <div class="flex flex-col">
                    <x-label value="Resultado" class="whitespace-nowrap text-sm" />
                    <x-input wire:model="suspensionPosteriorResultado" />
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Guardar -->
    <div class="mt-4 text-center">
        <x-button wire:click="guardar" wire:loading.attr="disabled" wire:target="guardar"
            class="bg-orange-500 hover:bg-orange-600">
            Guardar Medición
        </x-button>
    </div>
</div>
