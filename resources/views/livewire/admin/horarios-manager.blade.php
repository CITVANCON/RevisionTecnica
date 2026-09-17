<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <!-- Titulo y encabezado -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-clock mr-2"></i>Gestión de Horarios
                    </h2>
                    <span class="text-xs">Configuración de horarios laborales y turnos</span>
                </div>
                <div class="mt-4 md:mt-0 px-2">
                    <button wire:click="create"
                        class="bg-orange-500 px-6 py-4 rounded-md text-white font-semibold tracking-wide cursor-pointer hover:bg-orange-600 transition">
                        Nuevo Horario &nbsp;<i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Horarios -->
        @if ($horarios->count())
            <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($horarios as $h)
                            <tr class="border-b border-gray-200 bg-white text-sm" wire:key="horario-{{ $h->id }}">
                                <td class="px-5 py-4">
                                    <p class="text-gray-900 font-bold uppercase">{{ $h->nombre }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-gray-700 text-xs">{{ $h->descripcion }}</p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm
                                        {{ $h->activo ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                        {{ $h->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button wire:click="edit({{ $h->id }})"
                                        class="py-2 px-3 rounded-md bg-lime-500 font-bold text-white hover:bg-lime-600 transition">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $horarios->links() }}
            </div>
        @else
            <div class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                No hay horarios registrados.
            </div>
        @endif
    </div>

    <!-- Modal de Configuración de Horario -->
    <x-dialog-modal wire:model="isModalOpen" maxWidth="4xl">
        <x-slot name="title">
            {{ __('Configuración de Horario') }}
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <x-label for="nombre" value="{{ __('Nombre del Horario') }}" />
                    <x-input type="text" class="w-full" wire:model.defer="nombre" placeholder="Ej: Administrativo, Operarios..." />
                    <x-input-error for="nombre" class="mt-2" />
                </div>
                <div>
                    <x-label for="descripcion" value="{{ __('Descripción Corta') }}" />
                    <x-input type="text" class="w-full" wire:model.defer="descripcion" />
                </div>
            </div>

            <!-- Horario detallado por día -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Día
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Laborable
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Entrada
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Salida
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tolerancia
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descanso
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $index => $det)
                            <tr class="border-b border-gray-200 bg-white text-sm">
                                <td class="px-5 py-4">
                                    <span class="text-gray-900 font-bold">{{ $det['nombre_dia'] }}</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <input type="checkbox" wire:model="detalles.{{$index}}.es_laborable"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                </td>
                                @if($detalles[$index]['es_laborable'])
                                    <td class="px-2 py-2 text-center">
                                        <x-input type="time" class="p-1 text-xs w-full" wire:model.defer="detalles.{{$index}}.hora_entrada" />
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <x-input type="time" class="p-1 text-xs w-full" wire:model.defer="detalles.{{$index}}.hora_salida" />
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <x-input type="number" class="p-1 text-xs w-full" wire:model.defer="detalles.{{$index}}.tolerancia_tardanza" />
                                    </td>
                                    <td class="px-2 py-2 text-center">
                                        <div class="flex gap-1">
                                            <x-input type="time" class="p-1 text-xs w-full" wire:model.defer="detalles.{{$index}}.hora_descanso_inicio" />
                                            <x-input type="time" class="p-1 text-xs w-full" wire:model.defer="detalles.{{$index}}.hora_descanso_fin" />
                                        </div>
                                    </td>
                                @else
                                    <td colspan="4" class="px-5 py-4 text-center text-gray-400 italic text-xs">No laborable / Descanso</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isModalOpen',false)" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>
            <x-button class="ml-3" wire:click="save" wire:loading.attr="disabled">
                <i class="fas fa-save mr-2"></i> {{ __('Guardar Horario') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
