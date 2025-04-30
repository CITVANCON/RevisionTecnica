<div class="py-6">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <h1 class="text-2xl text-secondary font-bold italic text-center py-2"><span class="text-none">📊</span>
            LINEA DE INSPECCION</h1>
        <p class="text-sm leading-relaxed text-gray-500">
            <strong>Clase:
            </strong>{{ $propuesta->finalizada->clase->descripcion . '/' . $propuesta->finalizada->subclase->descripcion }}
            <strong>Categoria: </strong>{{ $propuesta->vehiculo->categoria->identificacion }}
            <strong>Placa: </strong>{{ $propuesta->vehiculo->vehiculo->placa }}
            <strong>Orden: </strong>1
        </p>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-4">
            {{-- Botones principales --}}
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                <x-secondary-button wire:click="mostrarSeccion('defectos')"
                    class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500 w-full">
                    Defectos
                </x-secondary-button>
                <x-secondary-button wire:click="mostrarSeccion('mediciones')"
                    class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500 w-full">
                    Mediciones
                </x-secondary-button>
                <x-secondary-button wire:click="mostrarSeccion('fotos')"
                    class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500 w-full">
                    Fotos
                </x-secondary-button>
                <x-secondary-button wire:click="mostrarSeccion('complementaria')"
                    class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500 w-full">
                    Complementaria
                </x-secondary-button>
                <x-secondary-button wire:click="mostrarSeccion('equipos')"
                    class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500 w-full">
                    Equipos
                </x-secondary-button>
                <x-secondary-button wire:click="mostrarSeccion('otros')"
                    class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500 w-full">
                    Otros
                </x-secondary-button>
            </div>

            {{-- Sección de mediciones específicas --}}
            @if ($seccionActiva === 'mediciones')
                <div class="border-t mt-2">
                    <p class="text-center my-4 text-secondary">
                        Seleccione el tipo de medición
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Frenos
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Alineamiento
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Luces
                        </x-secondary-button>
                    </div>
                </div>
            @endif

            {{-- Sección de complementarias --}}
            @if ($seccionActiva === 'complementaria')
                <div class="border-t mt-2">
                    <p class="text-center my-4 text-secondary">
                        Seleccione complementarias
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Leyenda 1
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Leyenda 2
                        </x-secondary-button>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
