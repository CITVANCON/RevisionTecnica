<div class="block justify-center pt-4 max-h-max pb-4">
    <h1 class="text-center text-xl my-4 font-bold text-secondary uppercase">Realizar Inspección Extra</h1>

    <div class="max-w-5xl m-auto bg-accent rounded-lg shadow-md">
        <div class="mt-2 mb-6 px-8 py-2">
            <div class="mt-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <x-label value="Seleccionar Servicio:" class="text-white min-w-[120px]" />
                <x-select wire:model.live="servicio_id" class="w-full sm:flex-1">
                    <option value="">Seleccionar</option>
                    @foreach ($servicios as $ser)
                        <option value="{{ $ser->id }}">{{ $ser->nombre_servicio }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>
    </div>

    @if ($servicio_seleccionado)
        <div class="max-w-5xl m-auto bg-white rounded-lg shadow-md my-4 p-6">
            <h3 class="font-bold text-secondary border-b mb-4">DATOS DEL CERTIFICADO Y PAGO</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <x-label value="N° Certificado:" />
                    <x-input type="text" wire:model="numero_certificate" class="w-full" placeholder="00060-2026" />
                </div>
                <div>
                    <x-label value="Método de Pago:" />
                    <x-select wire:model="metodo_pago" class="w-full">
                        <option value="">Seleccione</option>
                        <option value="EFECTIVO">Efectivo</option>
                        <option value="TRANSFERENCIA">Transferencia</option>
                        <option value="YAPE/PLIN">Yape/Plin</option>
                    </x-select>
                </div>
                <div>
                    <x-label value="Comisión S/:" />
                    <x-input type="number" wire:model="comision_monto" class="w-full" />
                </div>
            </div>

            @livewire('form-vehiculo')
        </div>

        @switch($servicio_seleccionado->id)
            @case(1)
                {{-- HERMETICIDAD --}}
                <div class="max-w-5xl m-auto bg-white rounded-lg shadow-md my-4 p-6">
                    <h3 class="font-bold text-blue-600 border-b mb-4 text-center">DETALLES DE PRUEBA DE HERMETICIDAD</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="p-2 border">Elemento</th>
                                    <th class="p-2 border">Estado (A/O/NA)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (['tapa', 'compuerta', 'tolva', 'sellos', 'bisagras', 'pistones', 'mangueras', 'remaches'] as $item)
                                    <tr>
                                        <td class="p-2 border uppercase">{{ $item }}</td>
                                        <td class="p-2 border text-center">
                                            <select wire:model="hermeticidad.{{ $item }}"
                                                class="w-full text-xs rounded border-gray-300">
                                                <option value="A">Apto (A)</option>
                                                <option value="O">Observado (O)</option>
                                                <option value="N">No Aplica (N.A)</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded">
                                <x-label value="Tiempo de Prueba:" />
                                <x-input type="text" wire:model="hermeticidad.tiempo_prueba" class="w-full" />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <x-label value="Cant. Bisagras:" />
                                    <x-input type="number" wire:model="hermeticidad.cant_bisagras" class="w-full" />
                                </div>
                                <div>
                                    <x-label value="Cant. Pistones:" />
                                    <x-input type="number" wire:model="hermeticidad.cant_pistones" class="w-full" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @break

            @case(2)
                {{-- OPACIDAD --}}
                <div class="max-w-5xl m-auto bg-white rounded-lg shadow-md my-4 p-6">
                    <h3 class="font-bold text-green-600 border-b mb-4 text-center">LECTURAS DEL OPACÍMETRO</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="border p-2 rounded bg-gray-50">
                                <p class="text-xs font-bold mb-1">MEDICIÓN 0{{ $i }}</p>
                                <x-label value="Valor K:" />
                                <x-input type="number" step="0.01" wire:model.live="opacidad.ciclo{{ $i }}_k"
                                    class="w-full mb-2" />
                                <x-label value="Temp. Aceite (°C):" />
                                <x-input type="number" wire:model="opacidad.ciclo{{ $i }}_t" class="w-full" />
                            </div>
                        @endfor
                    </div>
                </div>
            @break
        @endswitch

        <div class="max-w-5xl m-auto bg-white rounded-lg shadow-md my-4 py-6 px-6">
            <div class="flex justify-center">
                <button wire:click="guardarInspeccion" wire:loading.attr="disabled"
                    class="hover:cursor-pointer border border-orange-500 bg-orange-500 hover:bg-orange-600 px-10 py-3 rounded text-white font-bold transition">
                    <span wire:loading wire:target="guardarInspeccion">
                        <i class="fas fa-spinner animate-spin"></i>&nbsp;
                    </span>
                    &nbsp;GUARDAR E IMPRIMIR PDF
                </button>
            </div>
        </div>
    @endif
</div>
