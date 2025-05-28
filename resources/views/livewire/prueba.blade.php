<div class="block justify-center pt-4 max-h-max pb-4">
    <h1 class="text-center text-xl my-4 font-bold text-secondary">REALIZAR ALTA DE INSPECCION</h1>

    <!-- SERVICIO -->
    <div class="max-w-5xl m-auto bg-accent rounded-lg shadow-md">
        <div class="mt-2 mb-6 px-8 py-2">
            <!-- Servicio -->
            <div class="mt-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <x-label value="Servicio:" class="text-white min-w-[80px]" />
                <x-select wire:model.lazy="servicio_id" class="w-full sm:flex-1">
                    <option value="">Seleccionar</option>
                    @foreach ($servicios as $id => $descripcion)
                        <option value="{{ $id }}">{{ $descripcion }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="servicio_id" />
            </div>

            <!-- Ambito, Clase, SubClase -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="Ambito:" class="text-white min-w-[70px]" />
                    <x-select wire:model="ambito_id" class="w-full sm:flex-1">
                        <option value="">Seleccionar</option>
                        @foreach ($ambitos as $id => $descripcion)
                            <option value="{{ $id }}">{{ $descripcion }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="ambito_id" />
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="Clase:" class="text-white min-w-[60px]" />
                    <x-select wire:model="clase_id" class="w-full sm:flex-1">
                        <option value="">Seleccionar</option>
                        @foreach ($clases as $id => $descripcion)
                            <option value="{{ $id }}">{{ $descripcion }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="clase_id" />
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="SubClase:" class="text-white min-w-[80px]" />
                    <x-select wire:model="subclase_id" class="w-full sm:flex-1">
                        <option value="">Seleccionar</option>
                        @foreach ($subclases as $id => $descripcion)
                            <option value="{{ $id }}">{{ $descripcion }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="subclase_id" />
                </div>
            </div>

            <!-- Categoria -->
            <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <x-label value="Categoria:" class="text-white min-w-[80px]" />
                <x-select wire:model="categoria_id" class="w-full sm:flex-1">
                    <option value="">Seleccionar</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->identificacion }} -
                            {{ $categoria->descripcion }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="categoria_id" />
            </div>
            <div class="mt-4"></div>
        </div>
    </div>

    <!-- FORM VEHICULO (PARA CREAR UN VEHICULO) -->
    @livewire('form-vehiculo')

    <!-- ASEGURADORA -->
    <div class="max-w-5xl m-auto bg-accent rounded-lg shadow-md">
        <div class="mt-2 mb-6 px-8 py-2">
            <!-- Tipo vehiculo -->
            <div class="mt-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <x-label value="Tipo Vehiculo:" class="text-white min-w-[80px]" />
                <x-select wire:model="tipovehiculo_id" class="w-full sm:flex-1">
                    <option value="">Seleccionar</option>
                    @foreach ($tiposVehiculo as $id => $descripcion)
                        <option value="{{ $id }}">{{ $descripcion }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="tipovehiculo_id" />
            </div>
            <!-- Tipo documento , N° Documento -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="sm:col-span-2 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="Tipo Documento:" class="text-white min-w-[80px]" />
                    <x-select wire:model="tipodocumentoidentidad_id" class="w-full sm:flex-1">
                        <option value="">Seleccionar</option>
                        @foreach ($tiposDocumento as $id => $descripcion)
                            <option value="{{ $id }}">{{ $descripcion }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="tipodocumentoidentidad_id" />
                </div>
                <div class="flex flex-col w-full">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                        <x-label value="N° Documento:" class="text-white min-w-[80px]" />
                        <x-input type="text" class="w-full sm:flex-1" wire:model="numero_documento" />
                    </div>
                    <x-input-error for="numero_documento" />
                </div>
            </div>
            <!-- Dirección, Celular, Correo -->
            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                <!-- Dirección -->
                <div class="flex flex-col flex-[1.5]">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <x-label value="Dirección:" class="text-white min-w-[80px]" />
                        <x-input type="text" class="w-full" wire:model="direccion" />
                    </div>
                    <x-input-error for="direccion" />
                </div>
                <!-- Celular -->
                <div class="flex flex-col flex-1">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <x-label value="Celular:" class="text-white min-w-[80px]" />
                        <x-input type="tel" class="w-full" wire:model="celular" />
                    </div>
                    <x-input-error for="celular" />
                </div>
                <!-- Correo -->
                <div class="flex flex-col flex-[1.5]">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <x-label value="Correo:" class="text-white min-w-[80px]" />
                        <x-input type="email" class="w-full" wire:model="correo" />
                    </div>
                    <x-input-error for="correo" />
                </div>
            </div>
            <div class="mt-4"></div>
        </div>
    </div>

    <div class="max-w-5xl m-auto bg-accent rounded-lg shadow-md">
        <div class="mt-2 mb-6 px-8 py-2">
            <!-- Tipo poliza, N° poliza, FechaInicio, FechaFin -->
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="Tipo Póliza:" class="text-white min-w-[80px]" />
                    <x-input type="text" class="w-full sm:flex-1" wire:model="tipopoliza" />
                    <x-input-error for="tipopoliza" />
                </div>
                <div class="flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="N° Póliza:" class="text-white min-w-[80px]" />
                    <x-input type="text" class="w-full sm:flex-1" wire:model="num_poliza" />
                    <x-input-error for="num_poliza" />
                </div>
                <div class="flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="Fecha de Inicio:" class="text-white min-w-[80px]" />
                    <x-date-picker wire:model="fechaInicio" placeholder="Fecha de inicio..."
                        class="bg-gray-50 mx-2 border-orange-300 rounded-md outline-none ml-1 block w-full truncate" />
                    <x-input-error for="fechaInicio" />
                </div>
                <div class="flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-2">
                    <x-label value="Fecha de Fin:" class="text-white min-w-[80px]" />
                    <x-date-picker wire:model="fechaFin" placeholder="Fecha de inicio..."
                        class="bg-gray-50 mx-2 border-orange-300 rounded-md outline-none ml-1 block w-full truncate" />
                    <x-input-error for="fechaFin" />
                </div>
            </div>
            <!-- Aseguradora -->
            <div class="mt-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                <x-label value="Aseguradora:" class="text-white min-w-[80px]" />
                <x-select wire:model="aseguradora_id" class="w-full sm:flex-1">
                    <option value="">Seleccionar</option>
                    @foreach ($aseguradoras as $id => $descripcion)
                        <option value="{{ $id }}">{{ $descripcion }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="aseguradora_id" />
            </div>
            <div class="mt-4"></div>
        </div>
    </div>

    <!-- ENVIAR ALTA DE VEHICULO -->
    <div class="max-w-5xl m-auto bg-white rounded-lg shadow-md my-4 py-6 px-6">
        <div class="my-2 flex flex-col md:flex-row justify-evenly items-center">
            <div>
                <button wire:click="certificar" wire:loading.attr="disabled" wire.target="certificar"
                    class="hover:cursor-pointer border border-orange-500 focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-orange-500 hover:bg-orange-600 focus:outline-none rounded">
                    <p class="text-sm font-medium leading-none text-white">
                        <span wire:loading wire:target="certificar">
                            <i class="fas fa-spinner animate-spin"></i>
                            &nbsp;
                        </span>
                        &nbsp;Enviar Alta vehiculo
                    </p>
                </button>
            </div>
        </div>
    </div>
</div>
