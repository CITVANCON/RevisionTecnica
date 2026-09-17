<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <!-- Titulo y encabezado -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-calendar-alt mr-2"></i>Asignar Horario a Personal
                    </h2>
                    <span class="text-xs">Vincular horarios laborales a colaboradores</span>
                </div>
            </div>

            <!-- Formulario de Asignación -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- TRABAJADOR -->
                    <div class="col-span-1">
                        <x-label for="user_id" value="{{ __('1. TRABAJADOR') }}" />
                        <select wire:model.defer="user_id"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Seleccione un trabajador</option>
                            @foreach($usuarios as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="user_id" class="mt-2" />
                    </div>
                    <!-- HORARIO -->
                    <div class="col-span-1">
                        <x-label for="horario_id" value="{{ __('2. HORARIO MAESTRO') }}" />
                        <select wire:model.defer="horario_id"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">Seleccione horario</option>
                            @foreach($horarios as $horario)
                                <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="horario_id" class="mt-2" />
                    </div>
                    <!-- FECHA DE INICIO -->
                    <div class="col-span-1">
                        <x-label for="fecha_inicio" value="{{ __('3. FECHA DE INICIO') }}" />
                        <x-input type="date" class="w-full" wire:model.defer="fecha_inicio" />
                        <x-input-error for="fecha_inicio" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="guardarAsignacion"
                        wire:loading.attr="disabled"
                        class="bg-orange-500 px-6 py-4 rounded-md text-white font-semibold tracking-wide cursor-pointer hover:bg-orange-600 transition">
                        <span wire:loading.remove wire:target="guardarAsignacion">Vincular Horario &nbsp;<i class="fas fa-link"></i></span>
                        <span wire:loading wire:target="guardarAsignacion"><i class="fas fa-spinner fa-spin mr-1"></i> Procesando...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Horarios Vigentes -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Colaborador
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Horario Asignado
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Fecha Inicio
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($horariosAsignados as $item)
                        <tr class="border-b border-gray-200 bg-white text-sm" wire:key="asignacion-{{ $item->id }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <p class="text-gray-900 font-bold uppercase">{{ $item->usuario->name }}</p>
                                        <p class="text-gray-400 text-xs font-semibold">DNI: {{ $item->usuario->dni }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-gray-700 text-xs font-medium italic">{{ $item->horario->nombre }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <p class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm bg-green-100 text-green-700 border border-green-200">
                                    <i class="fas fa-circle text-[6px] mr-1"></i> Activo
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md text-xs">
                                No hay horarios asignados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
