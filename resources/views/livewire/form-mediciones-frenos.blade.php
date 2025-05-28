<div class="space-y-6">
    <!-- PRIMER BLOQUE -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2 mt-4">
        <!-- Cabecera Principal Dividida -->
        <div class="grid grid-cols-14 text-center font-semibold text-gray-800">
            <x-label class="col-span-2" value="" />
            <x-label class="col-span-3 pb-1" value="Frenada Izquierda (KN)" />
            <x-label class="col-span-3 pb-1" value="Frenada Derecha (KN)" />
            <x-label class="col-span-3 pb-1" value="Desequilibrio (%)" />
            <x-label class="col-span-3 pb-1" value="Resultado" />
        </div>
        <!-- Encabezado de columnas -->
        <div class="grid grid-cols-14 gap-2 text-center mt-2">
            <x-label value="Ejes" />
            <x-label value="Peso (Kg)" />
            <x-label value="Fs" />
            <x-label value="Fe" />
            <x-label value="Fem" />
            <x-label value="Fs" />
            <x-label value="Fe" />
            <x-label value="Fem" />
            <x-label value="Fs" />
            <x-label value="Fe" />
            <x-label value="Fem" />
            <x-label value="Fs" />
            <x-label value="Fe" />
            <x-label value="Fem" />
        </div>

        <div class="border-t border-gray-300 mt-2">
            <!-- Eje 1 -->
            <div class="grid grid-cols-14 gap-2 items-center mt-2">
                <x-label value="Eje 1" />
                <!--Peso-->
                <x-input type="number" wire:model="eje1Peso" class="w-full" />
                <x-input-error for="eje1Peso" />
                <!--Frenada Izquierda (KN)-->
                <x-input type="number" wire:model="eje1FSI" class="w-full" />
                <x-input-error for="eje1FSI" />
                <x-input type="number" wire:model="eje1FEI" class="w-full" disabled />
                <x-input-error for="eje1FEI" />
                <x-input type="number" wire:model="eje1FEMI" class="w-full" disabled />
                <x-input-error for="eje1FEMI" />
                <!--Frenada Derecha (KN)-->
                <x-input type="number" wire:model="eje1FSD" class="w-full" />
                <x-input-error for="eje1FSD" />
                <x-input type="number" wire:model="eje1FED" class="w-full" disabled />
                <x-input-error for="eje1FED" />
                <x-input type="number" wire:model="eje1FEMD" class="w-full" disabled />
                <x-input-error for="eje1FEMD" />
                <!--Desequilibrio (%)-->
                <x-input type="number" wire:model="eje1FSDesequilibrio" class="w-full" />
                <x-input-error for="eje1FSDesequilibrio" />
                <x-input type="number" wire:model="eje1FEDesequilibrio" class="w-full" disabled />
                <x-input-error for="eje1FEDesequilibrio" />
                <x-input type="number" wire:model="eje1FEMDesequilibrio" class="w-full" disabled />
                <x-input-error for="eje1FEMDesequilibrio" />
                <!--Resultado-->
                <x-input type="text" list="items" wire:model="eje1FSResultado" class="w-full" />
                <x-input-error for="eje1FSResultado" />
                <datalist id="items">
                    <option value="APROBADO">APROBADO</option>
                    <option value="DESAPROBADO">DESAPROBADO</option>
                </datalist>
                <x-input wire:model="eje1FEResultado" class="w-full" disabled />
                <x-input-error for="eje1FEResultado" />
                <x-input wire:model="eje1FEMResultado" class="w-full" disabled />
                <x-input-error for="eje1FEMResultado" />
            </div>
            <!-- Eje 2 -->
            <div class="grid grid-cols-14 gap-2 items-center mt-2">
                <x-label value="Eje 2" />
                <!--Peso-->
                <x-input type="number" wire:model="eje2Peso" class="w-full" />
                <x-input-error for="eje2Peso" />
                <!--Frenada Izquierda (KN)-->
                <x-input type="number" wire:model="eje2FSI" class="w-full" />
                <x-input-error for="eje2FSI" />
                <x-input type="number" wire:model="eje2FEI" class="w-full" />
                <x-input-error for="eje2FEI" />
                <x-input type="number" wire:model="eje2FEMI" class="w-full" disabled />
                <x-input-error for="eje2FEMI" />
                <!--Frenada Derecha (KN)-->
                <x-input type="number" wire:model="eje2FSD" class="w-full" />
                <x-input-error for="eje2FSD" />
                <x-input type="number" wire:model="eje2FED" class="w-full" />
                <x-input-error for="eje2FED" />
                <x-input type="number" wire:model="eje2FEMD" class="w-full" disabled />
                <x-input-error for="eje2FEMD" />
                <!--Desequilibrio (%)-->
                <x-input type="number" wire:model="eje2FSDesequilibrio" class="w-full" />
                <x-input-error for="eje2FSDesequilibrio" />
                <x-input type="number" wire:model="eje2FEDesequilibrio" class="w-full" />
                <x-input-error for="eje2FEDesequilibrio" />
                <x-input type="number" wire:model="eje2FEMDesequilibrio" class="w-full" disabled />
                <x-input-error for="eje2FEMDesequilibrio" />
                <!--Resultado-->
                <x-input type="text" list="items2" wire:model="eje2FSResultado" class="w-full" />
                <x-input-error for="eje2FSResultado" />
                <datalist id="items2">
                    <option value="APROBADO">APROBADO</option>
                    <option value="DESAPROBADO">DESAPROBADO</option>
                </datalist>
                <x-input wire:model="eje2FEResultado" class="w-full" disabled />
                <x-input-error for="eje2FEResultado" />
                <x-input wire:model="eje2FEMResultado" class="w-full" disabled />
                <x-input-error for="eje2FEMResultado" />
            </div>
        </div>
    </div>

    <!-- SEGUNDO BLOQUE -->
    <div class="flex gap-4 mt-4 justify-center">
        <!-- Eficiencia -->
        <div class="bg-white w-[30%] shadow-xl sm:rounded-lg p-4">
            <div class="grid grid-cols-3 text-center font-semibold text-gray-800">
                <x-label class="col-span-3 pb-1" value="Eficiencia (%)" />
            </div>
            <div class="grid grid-cols-3 gap-4 text-center mt-2">
                <x-label value="Fs" />
                <x-label value="Fe" />
                <x-label value="Fem" />
            </div>
            <div class="border-t border-gray-300 mt-2">
                <div class="grid grid-cols-3 gap-4 items-center mt-2">
                    <x-input type="number" wire:model="eje1FSEficiencia" class="w-full" />
                    <x-input-error for="eje1FSEficiencia" />
                    <x-input type="number" wire:model="eje1FEEficiencia" class="w-full" />
                    <x-input-error for="eje1FEEficiencia" />
                    <x-input type="number" wire:model="eje1FEMEficiencia" class="w-full" disabled />
                    <x-input-error for="eje1FEMEficiencia" />
                </div>
            </div>
        </div>

        <!-- Resultado Final -->
        <div class="bg-white w-[30%] shadow-xl sm:rounded-lg p-4">
            <div class="grid grid-cols-3 text-center font-semibold text-gray-800">
                <x-label class="col-span-3 pb-1" value="Resultado Final" />
            </div>
            <div class="grid grid-cols-3 gap-4 text-center mt-2">
                <x-label value="Fs" />
                <x-label value="Fe" />
                <x-label value="Fem" />
            </div>
            <div class="border-t border-gray-300 mt-2">
                <div class="grid grid-cols-3 gap-4 items-center mt-2">
                    <x-input type="text" list="items3" wire:model="FSResultadoFinal" class="w-full" />
                    <x-input-error for="FSResultadoFinal" />
                    <datalist id="items3">
                        <option value="APROBADO">APROBADO</option>
                        <option value="DESAPROBADO">DESAPROBADO</option>
                    </datalist>
                    <x-input type="text" list="items4" wire:model="FEResultadoFinal" class="w-full" />
                    <x-input-error for="FEResultadoFinal" />
                    <datalist id="items4">
                        <option value="APROBADO">APROBADO</option>
                        <option value="DESAPROBADO">DESAPROBADO</option>
                    </datalist>
                    <x-input wire:model="FEMResultadoFinal" class="w-full" disabled />
                    <x-input-error for="FEMResultadoFinal" />
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
