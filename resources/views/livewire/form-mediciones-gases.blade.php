<div class="space-y-6">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-4">
            <!-- Grid de 3 columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Primera Columna -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <x-label value="T. Aceite (°C)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesTemperaturaAceite" class="w-32" />
                        <x-input-error for="gasesTemperaturaAceite" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-label value="RPM" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesRPM" class="w-32" />
                        <x-input-error for="gasesRPM" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-label value="Opacidad (1/m)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesOpacidad" class="w-32" />
                        <x-input-error for="gasesOpacidad" />
                    </div>
                </div>

                <!-- Segunda Columna -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <x-label value="CO Ralenti (%)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesCORalenti" class="flex-1" />
                        <x-input-error for="gasesCORalenti" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-label value="CO+CO2 Ralenti (%)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesCOCO2Ralenti" class="flex-1" />
                        <x-input-error for="gasesCOCO2Ralenti" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-label value="HC Ralenti (ppm)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesHCRalenti" class="flex-1" />
                        <x-input-error for="gasesHCRalenti" />
                    </div>
                </div>

                <!-- Tercera Columna -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <x-label value="CO Acelerado (%)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesCOAcelerado" class="flex-1" />
                        <x-input-error for="gasesCOAcelerado" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-label value="CO+CO2 Acelerado (%)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesCOCO2Acelerado" class="flex-1" />
                        <x-input-error for="gasesCOCO2Acelerado" />
                    </div>
                    <div class="flex items-center gap-2">
                        <x-label value="HC Acelerado (ppm)" class="whitespace-nowrap text-sm w-40" />
                        <x-input type="number" wire:model="gasesHCAcelerado" class="flex-1" />
                        <x-input-error for="gasesHCAcelerado" />
                    </div>
                </div>
            </div>

            <!-- Resultado Final y Botón en la misma fila -->
            <div class="flex items-center justify-center gap-6 mt-6">
                <!-- Resultado Final -->
                <div class="flex items-center gap-2">
                    <x-label value="Resultado Final" class="whitespace-nowrap text-sm w-40" />
                    <x-input wire:model="gasesResultado" class="flex-1" />
                    <x-input-error for="gasesResultado" />
                </div>

                <!-- Botón Guardar Medición -->
                <x-button wire:click="guardar" wire:loading.attr="disabled" wire:target="guardar"
                    class="bg-orange-500 hover:bg-orange-600">
                    Guardar Medición
                </x-button>
            </div>
        </div>
    </div>
</div>
