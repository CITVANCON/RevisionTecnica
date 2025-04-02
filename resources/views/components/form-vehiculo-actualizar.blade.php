<div class="max-w-5xl m-auto  bg-white rounded-lg shadow-md" id="datosVehiculo">
    <div class="flex items-center justify-between bg-accent py-4 px-6 rounded-t-lg">
        <span class="text-lg font-semibold text-white">Datos del vehículo</span>
        <a class="px-3 py-1 text-sm font-bold text-gray-100 transition-colors duration-300 transform bg-gray-600 rounded cursor-pointer hover:bg-gray-500"
            tabindex="0" role="button">Editando...
        </a>
    </div>
    <div class="mt-2 mb-6 px-8 py-2">
        <div class="mb-2">
            <x-label value="Propietario:" />
            <x-input type="text" class="w-full" wire:model="vehiculo.propietario" maxlength="245" />
            <x-input-error for="vehiculo.propietario" />
        </div>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
            <div>
                <x-label value="Placa:" />
                <x-input list="vehiculos" type="text" class="w-full" wire:model="vehiculo.placa" />
                <x-input-error for="placa" />
            </div>
            <div>
                <x-label value="Categoria:" />
                <select wire:model="vehiculo.categoria"
                    class="bg-gray-50 border-indigo-500 rounded-md outline-none block w-full ">
                    <option value="">Seleccione</option>
                    <option value="NE">NE</option>
                    <option value="M1">M1</option>
                    <option value="M2">M2</option>
                    <option value="M3">M3</option>
                    <option value="N1">N1</option>
                    <option value="N2">N2</option>
                    <option value="N3">N3</option>
                    <option value="L1">L1</option>
                    <option value="L2">L2</option>
                    <option value="L3">L3</option>
                    <option value="L5">L5</option>
                    <option value="O1">O1</option>
                    <option value="O2">O2</option>
                    <option value="O3">O3</option>
                    <option value="O4">O4</option>
                    <option value="M1-C3">M1-C3</option>
                    <option value="M2-C1">M2-C1</option>
                    <option value="M2-C2">M2-C2</option>
                    <option value="M2-C3">M2-C3</option>
                    <option value="M3-C1">M3-C1</option>
                    <option value="M3-C2">M3-C2</option>
                    <option value="M3-C3">M3-C3</option>
                </select>
                <x-input-error for="categoria" />
            </div>
            <div>
                <x-label value="Marca:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.marca" />
                <x-input-error for="vehiculo.marca" />
            </div>
            <div>
                <x-label value="Modelo:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.modelo" />
                <x-input-error for="vehiculo.modelo" />
            </div>
            <div>
                <x-label value="Año de fabricación:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.anio_fabricacion" type="number"
                    inputmode="numeric" />
                <x-input-error for="vehiculo.anio_fabricacion" />
            </div>
            <div>
                <x-label value="Kilometraje:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.kilometraje" />
                <x-input-error for="vehiculo.kilometraje" />
            </div>
            <div>
                <x-label value="Combustible:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.combustible" />
                <x-input-error for="vehiculo.combustible" />
            </div>
            <div>
                <x-label value="VIN / N° Serie:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.vin_serie" />
                <x-input-error for="vehiculo.vin_serie" />
            </div>
            <div>
                <x-label value="N° Serie Motor:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.numero_motor" />
                <x-input-error for="vehiculo.numero_motor" />
            </div>
            <div>
                <x-label value="Carroceria:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.carroceria" />
                <x-input-error for="vehiculo.carroceria" />
            </div>
            <div>
                <x-label value="Marca carroceria:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.marca_carroceria"  />
                <x-input-error for="vehiculo.marca_carroceria" />
            </div>
            <div>
                <x-label value="Color:" />
                <x-input type="text" class="w-full" wire:model="vehiculo.color" />
                <x-input-error for="vehiculo.color" />
            </div>
            <div class="flex flex-row">
                <div class="w-1/2">
                    <x-label value="Ejes:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.ejes" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.ejes" />
                </div>
                <div class="w-1/2">
                    <x-label value="Ruedas:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.ruedas" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.ruedas" />
                </div>
            </div>
            <div class="flex flex-row">
                <div class="w-1/2">
                    <x-label value="Asientos:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.asientos" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.asientos" />
                </div>
                <div class="w-1/2">
                    <x-label value="Pasajeros:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.pasajeros" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.pasajeros" />
                </div>
            </div>
            <div class="flex flex-row w-full justify-center m-auto">
                <div class="w-1/3">
                    <x-label value="Largo:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.largo" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.largo" />
                </div>
                <div class="w-1/3">
                    <x-label value="Ancho:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.ancho" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.ancho" />
                </div>
                <div class="w-1/3">
                    <x-label value="Altura:" inputmode="numeric" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.alto" type="number" />
                    <x-input-error for="vehiculo.alto" />
                </div>
            </div>            
            <div class="flex flex-row w-full justify-center m-auto">
                <div class="w-1/3">
                    <x-label value="Peso Neto:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.peso_neto" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.peso_neto" />
                </div>
                <div class="w-1/3">
                    <x-label value="Peso Bruto:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.peso_bruto" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.peso_bruto" />
                </div>
                <div class="w-1/3">
                    <x-label value="Carga Util:" />
                    <x-input type="text" class="w-5/6" wire:model="vehiculo.peso_util" type="number"
                        inputmode="numeric" />
                    <x-input-error for="vehiculo.peso_util" />
                </div>
            </div>
        </div>
        <div class="mt-4  mb-2 flex flex-row justify-center items-center space-x-2">
            <a wire:click="$set('estado','cargado')"
                class="hover:cursor-pointer focus:ring-2 focus:ring-offset-2 focus:ring-amber-600 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-gray-400 hover:bg-gray-500 focus:outline-none rounded">
                <p class="text-sm font-medium leading-none text-white">Cancelar</p>
            </a>
            <a wire:click="actualizarVehiculo" wire:loading.attr="disabled" wire:target="actualizarVehiculo"
                class="hover:cursor-pointer focus:ring-2 focus:ring-offset-2 focus:ring-amber-600 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-green-400 hover:bg-green-500 focus:outline-none rounded">
                <p class="text-sm font-medium leading-none text-white">
                    <span wire:loading wire:target="actualizarVehiculo">
                        <i class="fas fa-spinner animate-spin"></i>
                        &nbsp;
                    </span>
                    Actualizar vehículo
                </p>
            </a>
        </div>

    </div>
</div>
