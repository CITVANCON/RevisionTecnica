<div class="max-w-5xl m-auto  bg-white rounded-lg shadow-md" id="datosVehiculo">
    <div class="flex items-center justify-between bg-accent py-4 px-6 rounded-t-lg">
        <span class="text-lg font-semibold text-white">Datos del vehículo</span>
        <i class="fas fa-check-circle fa-lg"></i>
    </div>
    <div class="mt-2 mb-6 px-8 py-2">
        <div class="mb-2">
            <x-label value="Propietario:" />
            <x-input type="text" class="w-full" wire:model="m_propietario" maxlength="245" disabled />
            <x-input-error for="propietario" />
        </div>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
            <div>
                <x-label value="Placa:" />
                <x-input type="text" class="w-full" wire:model="m_placa" maxlength="7" disabled />
                <x-input-error for="placa" />
            </div>
            {{--
            <div>
                <x-label value="Categoria:" />
                <select wire:model="m_categoria"
                    class="bg-gray-50 border-indigo-500 rounded-md outline-none block w-full " disabled>
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
            --}}
            <div>
                <x-label value="Marca:" />
                <x-input type="text" class="w-full" wire:model="m_marca" disabled />
                <x-input-error for="marca" />
            </div>
            <div>
                <x-label value="Modelo:" />
                <x-input type="text" class="w-full" wire:model="m_modelo" disabled />
                <x-input-error for="modelo" />
            </div>
            <div>
                <x-label value="Año de fabricación:" />
                <x-input type="text" class="w-full" wire:model="m_anio_fabricacion" type="number" disabled />
                <x-input-error for="anio_fabricacion" />
            </div>
            <div>
                <x-label value="Kilometraje:" />
                <x-input type="text" class="w-full" wire:model="m_kilometraje" />
                <x-input-error for="kilometraje" />
            </div>
            <div>
                <x-label value="Combustible:" />
                <x-input type="text" class="w-full" wire:model="m_combustible" disabled />
                <x-input-error for="combustible" />
            </div>
            <div>
                <x-label value="VIN / N° Serie:" />
                <x-input type="text" class="w-full" wire:model="m_vin_serie" disabled />
                <x-input-error for="vin_serie" />
            </div>
            <div>
                <x-label value="N° Serie Motor:" />
                <x-input type="text" class="w-full" wire:model="m_numero_motor" disabled />
                <x-input-error for="numero_motor" />
            </div>

            <div>
                <x-label value="Carroceria:" />
                <x-input type="text" class="w-full" wire:model="m_carroceria" disabled />
                <x-input-error for="carroceria" />
            </div>
            <div>
                <x-label value="Marca carroceria:" />
                <x-input type="text" class="w-full" wire:model="m_marca_carroceria" disabled />
                <x-input-error for="marca_carroceria" />
            </div>
            <div>
                <x-label value="Color:" />
                <x-input type="text" class="w-full" wire:model="m_color" disabled />
                <x-input-error for="color" />
            </div>
            <div class="flex flex-row">
                <div class="w-1/2">
                    <x-label value="Ejes:" />
                    <x-input type="text" class="w-5/6" wire:model="m_ejes" type="number" disabled />
                    <x-input-error for="ejes" />
                </div>
                <div class="w-1/2">
                    <x-label value="Ruedas:" />
                    <x-input type="text" class="w-5/6" wire:model="m_ruedas" type="number" disabled />
                    <x-input-error for="ruedas" />
                </div>
            </div>
            <div class="flex flex-row">
                <div class="w-1/2">
                    <x-label value="Asientos:" />
                    <x-input type="text" class="w-5/6" wire:model="m_asientos" type="number" disabled />
                    <x-input-error for="asientos" />
                </div>
                <div class="w-1/2">
                    <x-label value="Pasajeros:" />
                    <x-input type="text" class="w-5/6" wire:model="m_pasajeros" type="number"
                        disabled />
                    <x-input-error for="pasajeros" />
                </div>
            </div>
            <div class="flex flex-row w-full justify-center m-auto">
                <div class="w-1/3">
                    <x-label value="Largo:" />
                    <x-input type="text" class="w-5/6" wire:model="m_largo" type="number" disabled />
                    <x-input-error for="largo" />
                </div>
                <div class="w-1/3">
                    <x-label value="Ancho:" />
                    <x-input type="text" class="w-5/6" wire:model="m_ancho" type="number" disabled />
                    <x-input-error for="ancho" />
                </div>
                <div class="w-1/3">
                    <x-label value="Altura:" />
                    <x-input type="text" class="w-5/6" wire:model="m_alto" type="number" disabled />
                    <x-input-error for="alto" />
                </div>
            </div>
            
            <div class="flex flex-row w-full justify-center m-auto">
                <div class="w-1/3">
                    <x-label value="Peso Neto:" />
                    <x-input type="text" class="w-5/6" wire:model="m_peso_neto" type="number" disabled />
                    <x-input-error for="peso_neto" />
                </div>
                <div class="w-1/3">
                    <x-label value="Peso Bruto:" />
                    <x-input type="text" class="w-5/6" wire:model="m_peso_bruto" type="number"
                        disabled />
                    <x-input-error for="peso_bruto" />
                </div>
                <div class="w-1/3">
                    <x-label value="Carga Util:" />
                    <x-input type="text" class="w-5/6" wire:model="m_peso_util" type="number"
                        disabled />
                    <x-input-error for="peso_util" />
                </div>
            </div>
        </div>
        <div class="mt-4  mb-2 flex flex-row justify-center items-center">
            <a wire:click="$set('estado','editando')"
                class="hover:cursor-pointer focus:ring-2 focus:ring-offset-2 focus:ring-amber-600 sm:mt-0 inline-flex items-center justify-center px-6 py-3 bg-indigo-400 hover:bg-indigo-500 focus:outline-none rounded">
                <p class="text-sm font-medium leading-none text-white">Editar vehículo</p>
            </a>
        </div>
    </div>
</div>
