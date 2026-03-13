<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-clipboard-check mr-2"></i>Gestión de Inspecciones Maestras
                    </h2>
                    <span class="text-xs">Monitoreo de resultados de inspecciones vehiculares y certificados MTC</span>
                </div>
                <div class="mt-4 md:mt-0 px-2">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span>Mostrar:</span>
                        <select wire:model.live="cant"
                            class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none ml-1 block">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                        <span>Entradas</span>
                    </div>
                </div>
            </div>

            <div class="w-full items-center md:flex md:justify-between space-y-4 md:space-y-0 md:space-x-4 px-2">
                <div class="flex flex-wrap gap-3">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span>Estado:</span>
                        <select wire:model.live="resultado_estado"
                            class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none ml-1 block text-sm">
                            <option value="">Seleccionar</option>
                            <option value="APROBADO">Aprobado</option>
                            <option value="OBSERVADO">Observado</option>
                            <option value="RECHAZADO">Rechazado</option>
                        </select>
                    </div>
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span>Fecha:</span>
                        <input type="date" wire:model.live="fecha_inicio"
                            class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none focus:ring-0 text-sm">
                    </div>
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span>Hasta:</span>
                        <input type="date" wire:model.live="fecha_fin"
                            class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none focus:ring-0 text-sm">
                    </div>
                </div>

                <div class="flex bg-gray-50 items-center lg:w-2/6 p-2 rounded-md shadow-sm border border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                    <input class="bg-gray-50 outline-none block rounded-md w-full border-none focus:ring-0"
                        type="text" wire:model.live.debounce.300ms="placa_vehiculo"
                        placeholder="Buscar por placa (Ej: ABC123)...">
                </div>
            </div>
        </div>

        <!-- Tabla de Inspecciones -->
        @if ($inspecciones->count())
            <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Vehículo / Categoría
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Servicio
                            </th>
                            {{--
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                                Fecha de Inspección
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                                Horario
                            </th>
                            --}}
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Fecha / Horario
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estado / R
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Certificado MTC
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Monto
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inspecciones as $item)
                            <tr class="border-b border-gray-200 bg-white text-sm" wire:key="inspeccion-{{ $item->id }}">
                                <td class="px-5 py-4">
                                    <span class="px-2 py-2 rounded-full bg-indigo-100 text-indigo-700">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-gray-900 font-bold uppercase">{{ $item->placa_vehiculo }}</p>
                                            <p class="text-indigo-600 text-xs font-semibold">
                                                {{ $item->categoria_vehiculo }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-gray-700 text-xs font-medium uppercase">
                                        {{ $item->tipo_atencion }}
                                    </p>
                                    <span class="text-xs text-gray-400 italic">Servicio</span>
                                </td>
                                {{--
                                <td class="px-5 py-4">
                                    <p class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($item->fecha_inspeccion)->format('d/m/Y') }}</p>
                                    <p class="text-gray-400 text-xs italic">ID Local: {{ $item->id_inspeccion_local }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    <div class="text-xs">
                                        <span class="block"><b class="text-gray-400 uppercase">Inicio:</b>
                                            {{ $item->hora_inicio }}</span>
                                        <span class="block mt-1"><b class="text-gray-400 uppercase">Fin:</b>
                                            {{ $item->hora_fin }}</span>
                                    </div>
                                </td>
                                --}}
                                <td class="px-5 py-4">
                                    <p class="text-gray-900 font-medium">
                                        {{ \Carbon\Carbon::parse($item->fecha_inspeccion)->format('d/m/Y') }}
                                    </p>
                                    <div class="text-[11px] text-gray-500 mt-1">
                                        {{ $item->hora_inicio }} - {{ $item->hora_fin }}
                                    </div>
                                </td>
                                {{--
                                <td class="px-5 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full font-bold text-xs
                                        {{ $item->resultado_estado == 'APROBADO'
                                            ? 'bg-green-100 text-green-700'
                                            : ($item->resultado_estado == 'OBSERVADO'
                                                ? 'bg-orange-100 text-orange-700'
                                                : 'bg-red-100 text-red-700') }}">
                                        {{ $item->resultado_estado }}
                                    </span>
                                </td>
                                --}}
                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-row items-center justify-center gap-2">
                                        <span class="px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm
                                            {{ $item->resultado_estado == 'A' ? 'bg-green-100 text-green-700 border border-green-200' :
                                            ($item->resultado_estado == 'D' ? 'bg-red-100 text-red-700 border border-red-200' :
                                            'bg-yellow-100 text-yellow-700 border border-yellow-200') }}">
                                            {{ $item->resultado_estado }}
                                        </span>

                                        @if($item->es_reinspeccion === 'S')
                                            <span class="px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm bg-orange-200 text-orange-700 border border-orange-300" title="Reinspección">
                                                R
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-xs">
                                        <p class="font-bold text-gray-700">
                                            {{ $item->numero_certificado_mtc ?? 'SIN CERTIFICADO' }}</p>
                                        <p class="text-gray-400">
                                            {{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <p class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-bold">
                                        S/{{ number_format($item->monto_total, 2) }}
                                    </p>
                                    {{--<span class="text-xs text-green-600 font-semibold uppercase">Pagado</span>--}}
                                </td>
                                <td class="px-5 py-4 text-right" x-data="{ open: false }">
                                    <div class="relative inline-block text-left">
                                        <button @click="open = !open" @click.away="open = false"
                                            class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition focus:outline-none">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" x-cloak
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50 py-1">

                                            <button
                                                class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white transition">
                                                <i class="fas fa-eye w-5 mr-2 text-blue-500"></i> Ver Detalles
                                            </button>

                                            <button
                                                class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white transition">
                                                <i class="fas fa-print w-5 mr-2 text-gray-500"></i> Imprimir Constancia
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $inspecciones->links() }}
            </div>
        @else
            <div class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                No hay inspecciones registradas con los criterios seleccionados.
            </div>
        @endif
    </div>
</div>
