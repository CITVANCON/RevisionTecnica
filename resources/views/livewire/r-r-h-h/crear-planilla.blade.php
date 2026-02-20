<div>
    <x-dialog-modal wire:model="abierto" maxWidth="4xl">
        <x-slot name="title">
            <div class="flex items-center">
                <i class="fas fa-file-invoice-dollar text-orange-500 mr-2"></i>
                Generar Nueva Planilla de Remuneraciones
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Columna Izquierda: Datos Principales --}}
                <div class="space-y-4 border-r pr-4 border-gray-100">
                    <h4 class="font-bold text-indigo-600 border-b pb-1 uppercase text-xs">Información General</h4>

                    <div>
                        <x-label value="Seleccionar Trabajador" />
                        <select wire:model.live="userId" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            <option value="">Seleccione un trabajador...</option>
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - DNI: {{ $user->dni }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="userId" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-label value="Fecha Periodo" />
                            <x-input type="date" class="w-full" wire:model.live="periodo" />
                            <x-input-error for="periodo" />
                        </div>
                        <div>
                            <x-label value="Tipo de Pago" />
                            <select wire:model="tipo_planilla" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                        <x-label value="Sueldo Base (Desde Contrato)" />
                        <x-input type="number" step="0.01" class="w-full font-bold text-indigo-700" wire:model.live="sueldo_base" />
                        <x-input-error for="sueldo_base" />
                    </div>
                </div>

                {{-- Columna Derecha: Ingresos y Descuentos --}}
                <div class="space-y-4">
                    <h4 class="font-bold text-green-600 border-b pb-1 uppercase text-xs">Ingresos Adicionales (+)</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-label value="Asig. Familiar" class="text-xs" />
                            <x-input type="number" step="0.01" class="w-full" wire:model.live="asignacion_familiar" />
                        </div>
                        <div>
                            <x-label value="Horas Extras" class="text-xs" />
                            <x-input type="number" step="0.01" class="w-full" wire:model.live="horas_extras" />
                        </div>
                        <div>
                            <x-label value="Movilidad" class="text-xs" />
                            <x-input type="number" step="0.01" class="w-full" wire:model.live="movilidad" />
                        </div>
                        <div>
                            <x-label value="Otros Ingresos" class="text-xs" />
                            <x-input type="number" step="0.01" class="w-full" wire:model.live="otros_ingresos" />
                        </div>
                    </div>

                    <h4 class="font-bold text-red-600 border-b pb-1 uppercase text-xs mt-4">Descuentos (-)</h4>
                    <div>
                        <x-label value="Otros Descuentos / Adelantos" />
                        <x-input type="number" step="0.01" class="w-full border-red-200" wire:model.live="otros_descuentos" />
                    </div>
                </div>
            </div>

            {{-- Resumen Total --}}
            <div class="mt-6 p-4 bg-gray-800 rounded-lg flex flex-col md:flex-row justify-between items-center shadow-inner">
                <div class="w-full md:w-2/3 pr-4">
                    <x-label value="Observaciones Internas" class="text-white opacity-80" />
                    <textarea wire:model="observacion" rows="1" class="mt-1 block w-full rounded-md border-transparent bg-gray-700 text-white shadow-sm sm:text-sm focus:border-indigo-500 focus:ring-0" placeholder="Escribir nota..."></textarea>
                </div>
                <div class="w-full md:w-1/3 mt-4 md:mt-0 text-right">
                    <span class="text-gray-400 font-bold text-xs block uppercase">Neto a Recibir</span>
                    <span class="text-3xl font-black text-green-400">S/ {{ number_format($total_visual, 2) }}</span>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('abierto', false)">
                Cancelar
            </x-secondary-button>
            <x-danger-button class="ml-3 !bg-orange-600" wire:click="guardar" wire:loading.attr="disabled">
                <i class="fas fa-save mr-2"></i> Generar Planilla
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
