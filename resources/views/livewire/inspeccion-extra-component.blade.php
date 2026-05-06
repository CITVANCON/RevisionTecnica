<div class="block justify-center pt-4 max-h-max pb-4">
    <h1 class="text-center text-xl my-4 font-bold text-secondary uppercase">Realizar Inspección Extra</h1>

    <!-- Selección de Servicio -->
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
            <h3 class="font-bold text-secondary border-b mb-4 uppercase">1. Información del Cliente</h3>
            @livewire('form-cliente', ['nombreDelInvocador' => 'inspeccion-extra-component'])

            <h3 class="font-bold text-secondary border-b my-4 uppercase">2. Información del Vehículo</h3>
            @livewire('form-vehiculo', ['nombreDelInvocador' => 'inspeccion-extra-component'])

            <h3 class="font-bold text-secondary border-b my-4 uppercase">3. Datos del Certificado y Pago</h3>
            {{-- 
            <div class="grid grid-cols-1 md:grid-cols-10 gap-4 mb-6 items-end">               
                <!-- Área de Pagos Dinámicos -->
                <div class="md:col-span-4 bg-gray-50 p-3 rounded-lg border">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-600">MÉTODOS DE PAGO</span>
                        <button type="button" wire:click="agregarPago" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                            + Agregar Método
                        </button>
                    </div>
                    @foreach($pagos_multiples as $index => $pago)
                        <div class="flex gap-2 mb-2 items-center" wire:key="pago-{{ $index }}">
                            <x-select wire:model="pagos_multiples.{{ $index }}.metodo_pago" class="flex-1 text-sm">
                                <option value="">Seleccione</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="YAPE">YAPE</option>
                                <option value="VISA">VISA (POS)</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                            </x-select>

                            <x-input type="number" step="0.1" 
                                wire:model.live="pagos_multiples.{{ $index }}.monto" 
                                wire:change="actualizarMontoTotal"
                                placeholder="Monto" class="w-24 text-sm font-bold text-green-700" />

                            @if(count($pagos_multiples) > 1)
                                <button type="button" wire:click="eliminarPago({{ $index }})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <!-- Totales y Comision -->
                <div class="md:col-span-3 grid grid-cols-1 gap-2">
                    <div>
                        <x-label value="Total a Cobrar S/:" />
                        <div class="text-xl font-black text-green-700 bg-green-50 border border-green-200 rounded px-2 py-1">
                            S/{{ number_format($monto_total, 2) }}
                        </div>
                    </div>
                    <div>
                        <x-label value="Comisión S/:" />
                        <x-input type="number" wire:model="comision_monto" class="w-full text-sm" />
                    </div>                
                </div>
                <!-- Certificado y Vigencia -->
                <div class="md:col-span-3 grid grid-cols-1 gap-2">
                    <div>
                        <x-label value="N° Certificado:" />
                        <x-input type="text" wire:model="numero_certificado" class="w-full" />
                    </div>
                    <div>
                        <x-label value="Vigencia:" />
                        <x-select wire:model="vigencia_meses" class="w-full text-sm">
                            <option value="6">6 Meses</option>
                            <option value="12">12 Meses</option>
                        </x-select>
                    </div>                
                </div>
            </div>
            --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4 items-stretch">    
                <!-- Área de Pagos (Más compacto) -->
                <div class="md:col-span-5 bg-gray-50 p-2 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center mb-1 px-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase">Pagos</span>
                        <button type="button" wire:click="agregarPago" 
                            class="text-[10px] bg-indigo-600 text-white px-2 py-0.5 rounded hover:bg-indigo-700 transition-colors">
                            + Agregar
                        </button>
                    </div>
                    <div class="space-y-1 max-h-[120px] overflow-y-auto">
                        @foreach($pagos_multiples as $index => $pago)
                            <div class="flex gap-1 items-center bg-white p-1 rounded border border-gray-100 shadow-sm" wire:key="pago-{{ $index }}">
                                <x-select wire:model="pagos_multiples.{{ $index }}.metodo_pago" class="flex-1 text-[11px] py-1 border-none focus:ring-0">
                                    <option value="">Método</option>
                                    <option value="EFECTIVO">EFECTIVO</option>
                                    <option value="YAPE">YAPE</option>
                                    <option value="VISA">VISA (POS)</option>
                                    <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                </x-select>

                                <div class="relative w-24">
                                    <span class="absolute left-1 top-1/2 -translate-y-1/2 text-[10px] font-bold text-green-600">S/</span>
                                    <input type="number" step="0.1" 
                                        wire:model.live="pagos_multiples.{{ $index }}.monto" 
                                        wire:change="actualizarMontoTotal"
                                        class="w-full pl-5 pr-1 py-1 text-xs font-bold text-green-700 border-gray-200 rounded focus:ring-0"
                                        placeholder="0.00" />
                                </div>

                                @if(count($pagos_multiples) > 1)
                                    <button type="button" wire:click="eliminarPago({{ $index }})" 
                                        class="text-gray-400 hover:text-red-500 px-1">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Bloque Central: Totales y Comisión -->
                <div class="md:col-span-3 grid grid-cols-1 gap-2">
                    <div class="bg-green-100 border border-green-300 rounded-lg p-2 flex flex-col justify-center items-center">
                        <span class="text-[10px] font-bold text-green-800 uppercase">Total Cobrar</span>
                        <div class="text-xl font-black text-green-700 leading-none">
                            S/{{ number_format($monto_total, 2) }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-2 py-1">
                        <x-label value="Comisión:" class="text-[10px] font-bold text-gray-500 uppercase whitespace-nowrap" />
                        <input type="number" wire:model="comision_monto" class="w-full text-xs font-semibold py-1 border-none focus:ring-0 text-right" placeholder="0.00" />
                    </div>
                </div>
                <!-- Bloque Final: Certificado y Vigencia -->
                <div class="md:col-span-4 flex flex-col gap-2 p-2 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded px-2 py-1">
                        <i class="fas fa-certificate text-gray-400 text-xs"></i>
                        <input type="text" wire:model="numero_certificado" 
                            class="w-full text-xs font-bold text-indigo-700 border-none focus:ring-0 p-0" />
                    </div>

                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded px-2 py-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase">Vigencia:</span>
                        <select wire:model="vigencia_meses" class="w-full text-xs font-semibold border-none focus:ring-0 py-0 h-6">
                            <option value="6">6 Meses</option>
                            <option value="12">12 Meses</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Switch de Detalles de Servicio -->
        @switch($servicio_seleccionado->id)
            @case(1)
                @if ($paso == 1)
                    @livewire('form-hermeticidad-component', [], key('form-hermeticidad'))
                @endif
            @break

            @case(2)
                @if ($paso == 1)
                    @livewire('form-opacidad-component', [], key('form-opacidad'))
                @endif
            @break
        @endswitch

        @if ($paso == 1)
            <div class="max-w-5xl m-auto bg-white rounded-lg shadow-md flex flex-col md:flex-row justify-evenly items-center my-4 py-6 px-6">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <x-label value="RESULTADO:" class="font-bold text-secondary" />
                    <select wire:model.live="resultado_final" wire:key="select-resultado-{{ $resultado_final }}"
                        class="rounded-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $resultado_final == 'APROBADO' ? 'text-green-600' : ($resultado_final == 'DESAPROBADO' ? 'text-red-600' : 'text-gray-500') }}">                        
                        <option value="">-- Por definir --</option>
                        <option value="APROBADO">APROBADO</option>
                        <option value="DESAPROBADO">DESAPROBADO</option>
                    </select>
                </div>

                <button wire:click="guardarInspeccion" wire:loading.attr="disabled" wire:target="guardarInspeccion"
                    class="hover:cursor-pointer border border-orange-500 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-orange-500 hover:bg-orange-600 focus:outline-none rounded">
                    <p class="text-sm font-medium leading-none text-white">
                        <span wire:loading wire:target="guardarInspeccion">
                            <i class="fas fa-spinner animate-spin"></i>
                            &nbsp;
                        </span>
                        &nbsp;Guardar & Certificar
                    </p>
                </button>                
            </div>
        @endif

        @if ($paso == 2)
            <div class="max-w-5xl m-auto bg-white rounded-lg shadow-md my-4 py-4">
                <div class="my-2 flex flex-row justify-evenly items-center" x-data="{ menu: false }">
                    <button type="button" x-on:click="menu = ! menu" id="menu-button" aria-expanded="true"
                        aria-haspopup="true" data-te-ripple-init data-te-ripple-color="light"
                        class="hover:cursor-pointer border border-indigo-500 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 sm:mt-0 inline-flex items-center justify-center px-6 py-2 bg-indigo-400 text-white hover:bg-indigo-500 focus:outline-none rounded">
                        Documentos &nbsp; <i class="fas fa-angle-down"></i>
                    </button>
                    <div x-show="menu" x-on:click.away="menu = false"
                        class="dropdown-menu transition-all duration-300 transform origin-top-right -translate-y-2 scale-95 absolute  dropdown-content bg-white shadow w-56 z-30 mt-6 border border-slate-800 rounded-md"
                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="" role="none">
                            <a href="{{ $inspeccion_generada->url_certificado }}" target="__blank" rel="noopener noreferrer"
                                class="flex px-4 py-2 text-sm text-indigo-700 hover:bg-slate-600 hover:text-white justify-between items-center rounded-t-md hover:cursor-pointer">
                                <i class="fas fa-eye"></i>
                                <span>Ver Certificado.</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('inspeccion.extra') }}"
                        class="hover:cursor-pointer focus:ring-2 focus:ring-offset-2 focus:ring-amber-600 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-red-400 hover:bg-red-500 focus:outline-none rounded">
                        <p class="text-sm font-medium leading-none text-white">
                            <i class="fas fa-archive"></i>&nbsp;Finalizar
                        </p>
                    </a>
                </div>
            </div>
        @endif

    @endif
</div>
