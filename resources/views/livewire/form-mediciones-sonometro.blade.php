<div class="space-y-6">
    <!-- Contenedor responsive -->
    <div class="flex flex-col sm:flex-row gap-4 mt-4 justify-center">
        <!-- ANTERIOR -->
        <div class="bg-white shadow-md sm:rounded-lg p-2">
            <div class="flex gap-2 items-center">
                <div class="flex flex-col">
                    <x-label value="Ruido (dB)" class="whitespace-nowrap text-sm" />
                    <x-input type="number" wire:model.lazy="sonometroMedida" min="0" />
                    <x-input-error for="sonometroMedida" />
                </div>
                <div class="flex flex-col">
                    <x-label value="Resultado" class="whitespace-nowrap text-sm" />
                    <x-input type="text" wire:model="sonometroResultado" disabled {{--readonly--}}  />
                    <x-input-error for="sonometroResultado" />
                </div>
            </div>
        </div>
    </div>

    <!-- Botón debajo del contenedor -->
    <div class="text-center">
        <x-button wire:click="guardar" wire:loading.attr="disabled" wire:target="guardar"
            class="bg-orange-500 hover:bg-orange-600">
            Guardar Medición
        </x-button>
    </div>
</div>
