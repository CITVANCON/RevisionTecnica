<div class="py-2">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <h1 class="text-2xl text-secondary font-bold italic text-center"><span class="text-none">📊</span>
            LINEA DE INSPECCION</h1>
        <p class="text-sm leading-relaxed text-gray-500">
            <strong>Clase:
            </strong>{{ $propuesta->finalizada->clase->descripcion . '/' . $propuesta->finalizada->subclase->descripcion }}
            <strong>Categoria: </strong>{{ $propuesta->vehiculo->categoria->identificacion }}
            <strong>Placa: </strong>{{ $propuesta->vehiculo->vehiculo->placa }}
            <strong>Orden: </strong>1
        </p>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-2">
            <!-- Botones principales -->
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

            <!-- Sección de opcion defectos -->
            @if ($seccionActiva === 'defectos')
                <div class="border-t">
                    <p class="text-center my-2 text-secondary">
                        Seleccione el tipo de defectos
                    </p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            A:Documentación
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            B:Dirección
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            C:Suspensión
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            D:Frenos
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            E:Estructura
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            F:Contaminantes
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            G:Neumáticos
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            H:Eléctrico
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            I:Accesorios
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            J:Ext/Int
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            K:GasCarburante
                        </x-secondary-button>
                        <x-secondary-button
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            L:Otros
                        </x-secondary-button>
                    </div>
                </div>
            @endif

            <!-- Sección de opcion mediciones -->
            @if ($seccionActiva === 'mediciones')
                <div class="border-t">
                    <p class="text-center my-2 text-secondary">
                        Seleccione el tipo de medición
                    </p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <x-secondary-button wire:click="mostrarSubseccion('frenos')"
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Frenos
                        </x-secondary-button>
                        <x-secondary-button wire:click="mostrarSubseccion('alineamiento')"
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Ruedas y ejes
                        </x-secondary-button>
                        <x-secondary-button wire:click="mostrarSubseccion('luces')"
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Luces
                        </x-secondary-button>
                        <x-secondary-button wire:click="mostrarSubseccion('suspension')"
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Suspensión
                        </x-secondary-button>
                        <x-secondary-button wire:click="mostrarSubseccion('sonometro')"
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            E. Sonoras
                        </x-secondary-button>
                        <x-secondary-button wire:click="mostrarSubseccion('gases')"
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            E. Gases
                        </x-secondary-button>
                        <x-secondary-button wire:click="mostrarSubseccion('angulogiro')"
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Áng. Giro
                        </x-secondary-button>
                        <x-secondary-button wire:click=""
                            class="flex items-center justify-center text-center bg-gray-500 hover:bg-orange-500">
                            Otros
                        </x-secondary-button>
                    </div>
                </div>
            @endif
        </div>
    </div>


    <!-- tabla de opcion fotos -->
    @if ($seccionActiva === 'fotos')
        @livewire('form-fotos', ['idPropuesta' => $idPropuesta])
    @endif

    <!-- tabla de opcion complementarias -->
    @if ($seccionActiva === 'complementaria')
        @livewire('form-complementaria', ['idPropuesta' => $idPropuesta])
    @endif

    <!-- tabla de opcion equipos -->
    @if ($seccionActiva === 'equipos')
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-4 max-w-md mx-auto">
            <table class="w-full table-auto border border-gray-200">
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium text-gray-700 text-left">Frenómetro</td>
                        <td class="px-4 py-2 text-gray-600 text-left">20090803</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium text-gray-700 text-left">Alineador</td>
                        <td class="px-4 py-2 text-gray-600 text-left">20090803</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium text-gray-700 text-left">Analizador Gases</td>
                        <td class="px-4 py-2 text-gray-600 text-left">20090803</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium text-gray-700 text-left">Opacímetro</td>
                        <td class="px-4 py-2 text-gray-600 text-left">20090803</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium text-gray-700 text-left">Regloscopio</td>
                        <td class="px-4 py-2 text-gray-600 text-left">0326</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium text-gray-700 text-left">Banco de Suspensión</td>
                        <td class="px-4 py-2 text-gray-600 text-left">20090803</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-700 text-left">Sonómetro</td>
                        <td class="px-4 py-2 text-gray-600 text-left">JB 2185116</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <!-- componentes de seccion mediciones -->
    <div class="mt-4">
        @if ($subseccionActiva === 'luces')
            @livewire('form-mediciones-luces', ['idPropuesta' => $idPropuesta], key('luces-' . $idPropuesta))
        @elseif ($subseccionActiva === 'frenos')
            @livewire('form-mediciones-frenos', ['idPropuesta' => $idPropuesta], key('frenos-' . $idPropuesta))
        @elseif ($subseccionActiva === 'alineamiento')
            @livewire('form-mediciones-alineador', ['idPropuesta' => $idPropuesta], key('alineador-' . $idPropuesta))
        @elseif ($subseccionActiva === 'suspension')
            @livewire('form-mediciones-suspension', ['idPropuesta' => $idPropuesta], key('suspension-' . $idPropuesta))
        @elseif ($subseccionActiva === 'sonometro')
            @livewire('form-mediciones-sonometro', ['idPropuesta' => $idPropuesta], key('sonometro-' . $idPropuesta))
        @elseif ($subseccionActiva === 'gases')
            @livewire('form-mediciones-gases', ['idPropuesta' => $idPropuesta], key('gases-' . $idPropuesta))
        @elseif ($subseccionActiva === 'angulogiro')
            @livewire('form-mediciones-angulogiro', ['idPropuesta' => $idPropuesta], key('angulogiro-' . $idPropuesta))
        @endif
    </div>
</div>
