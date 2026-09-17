<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <!-- Titulo y encabezado -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-chart-bar mr-2"></i>Reportes de Asistencia
                    </h2>
                    <span class="text-xs">Consulta y exportación de reportes de asistencia del personal</span>
                </div>
                <div class="mt-4 md:mt-0 px-2 flex gap-3">
                    <button wire:click="exportExcel" wire:loading.attr="disabled"
                        class="bg-green-500 px-6 py-4 rounded-md text-white font-semibold tracking-wide cursor-pointer hover:bg-green-600 transition">
                        <i class="fas fa-file-excel mr-2"></i> Excel
                    </button>
                    <button wire:click="exportPDF"
                        class="bg-red-500 px-6 py-4 rounded-md text-white font-semibold tracking-wide cursor-pointer hover:bg-red-600 transition">
                        <i class="fas fa-file-pdf mr-2"></i> PDF
                    </button>
                </div>
            </div>

            <!-- Filtros de búsqueda -->
            <div class="w-full items-center md:flex md:justify-between space-y-4 md:space-y-0 md:space-x-4 px-2">
                <div class="flex flex-wrap gap-3">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span>Desde:</span>
                        <input type="date" wire:model.live="fechaInicio"
                            class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none ml-1 block text-sm">
                    </div>
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span>Hasta:</span>
                        <input type="date" wire:model.live="fechaFin"
                            class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none ml-1 block text-sm">
                    </div>
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span>Estado:</span>
                        <select wire:model.live="estado"
                            class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none ml-1 block text-sm">
                            <option value="">Todos</option>
                            <option value="Puntual">Puntual</option>
                            <option value="Tardanza">Tardanza</option>
                            <option value="Incompleto">Incompleto</option>
                        </select>
                    </div>
                </div>

                @unless(auth()->user()->hasRole('inspector'))
                    <div class="flex bg-gray-50 items-center lg:w-2/6 p-2 rounded-md shadow-sm border border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        <input class="bg-gray-50 outline-none block rounded-md w-full border-none focus:ring-0 text-sm"
                            type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por DNI o nombre...">
                    </div>
                @else
                    <div class="flex bg-indigo-50 items-center p-2 rounded-md shadow-sm border border-indigo-100">
                        <span class="text-indigo-600 font-bold text-xs uppercase px-2">
                            Mostrando mis asistencias personales
                        </span>
                    </div>
                @endunless
            </div>
        </div>

        <!-- Tabla de Reportes -->
        @if ($reportes->count())
            <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Colaborador
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Entrada / Salida
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tardanza
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Trabajado
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                H. Extras
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportes as $item)
                            <tr class="border-b border-gray-200 bg-white text-sm" wire:key="reporte-{{ $item->id }}">
                                <td class="px-5 py-4">
                                    <span class="text-gray-900 font-bold">{{ $item->fecha->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-gray-900 font-bold uppercase">{{ $item->usuario->name }}</p>
                                            <p class="text-gray-400 text-[10px] font-semibold">DNI: {{ $item->usuario->dni }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="text-xs font-bold">
                                        <span class="text-indigo-600">{{ $item->hora_entrada ? $item->hora_entrada->format('H:i') : '--:--' }}</span>
                                        <span class="mx-1 text-gray-300">|</span>
                                        <span class="text-orange-600">{{ $item->hora_salida ? $item->hora_salida->format('H:i') : '--:--' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm
                                        {{ $item->estado == 'Puntual'
                                            ? 'bg-green-100 text-green-700 border border-green-200'
                                            : 'bg-red-100 text-red-700 border border-red-200' }}">
                                        {{ $item->estado }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center text-xs font-bold {{ $item->minutos_tardanza > 0 ? 'text-red-600' : 'text-gray-300' }}">
                                    {{ $item->minutos_tardanza }} min
                                </td>
                                <td class="px-5 py-4 text-center text-xs font-bold text-gray-500">
                                    {{ $item->minutos_trabajados ? number_format($item->minutos_trabajados / 60, 2) : '0.00' }} hrs
                                </td>
                                <td class="px-5 py-4 text-right text-xs font-bold text-indigo-700">
                                    {{ $item->horas_extras_minutos > 0 ? number_format($item->horas_extras_minutos / 60, 2) . ' hrs' : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reportes->links() }}
            </div>
        @else
            <div class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                No se encontraron registros para los filtros seleccionados.
            </div>
        @endif
    </div>
</div>
