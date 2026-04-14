<div class="block justify-center pt-4 max-h-max pb-4">
    <h1 class="text-center text-xl my-4 font-bold text-secondary uppercase">Realizar Inspección Extra</h1>

    {{-- Selección de Servicio --}}
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <x-label value="N° Certificado:" />
                    <x-input type="text" wire:model="numero_certificado" class="w-full" placeholder="00060" />
                </div>
                <div>
                    <x-label value="Método de Pago:" />
                    <x-select wire:model="metodo_pago" class="w-full">
                        <option value="">Seleccione</option>
                        <option value="EFECTIVO">EFECTIVO</option>
                        <option value="YAPE">YAPE</option>
                        <option value="VISA">VISA (POS)</option>
                        <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                    </x-select>
                </div>
                <div>
                    <x-label value="Comisión S/:" />
                    <x-input type="number" wire:model="comision_monto" class="w-full" />
                </div>
                <div>
                    <x-label value="Vigencia (Meses):" />
                    <x-select wire:model="vigencia_meses" class="w-full">
                        <option value="6">6 Meses</option>
                        <option value="12">12 Meses</option>
                    </x-select>
                </div>
            </div>
        </div>

        {{-- Switch de Detalles de Servicio --}}
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
                            <a href="" target="__blank" rel="noopener noreferrer"
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
