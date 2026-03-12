<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-chart-line mr-2"></i>Reportes de Inspecciones
                    </h2>
                    <span class="text-xs">Análisis de ingresos y productividad operativa</span>
                </div>

                <div class="flex gap-3 mt-4 md:mt-0 px-2">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2">Desde:</span>
                        <input type="date" wire:model.live="fecha_inicio" class="bg-gray-50 border-none outline-none focus:ring-0 text-sm">
                    </div>
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2">Hasta:</span>
                        <input type="date" wire:model.live="fecha_fin" class="bg-gray-50 border-none outline-none focus:ring-0 text-sm">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 px-2 mb-8">
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Ingresos Totales</p>
                            <p class="text-xl font-bold text-gray-800">S/ {{ number_format($stats['total_ingresos'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Inspecciones</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['total_inspecciones'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Tasa Aprobación</p>
                            <p class="text-xl font-bold text-gray-800">
                                {{ $stats['total_inspecciones'] > 0 ? number_format(($stats['aprobados'] / $stats['total_inspecciones']) * 100, 1) : 0 }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <i class="fas fa-ban fa-2x"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Anulaciones</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['anulados'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                        <h3 class="text-gray-700 font-bold text-sm uppercase">Distribución por Tipo de Atención</h3>
                    </div>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Tipo de Atención</th>
                                <th class="px-5 py-3 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Cantidad</th>
                                <th class="px-5 py-3 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase">Total Ingresos</th>
                                <th class="px-5 py-3 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Participación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porTipo as $tipo)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="px-5 py-4">
                                        <p class="text-gray-900 font-semibold">{{ $tipo->tipo_atencion ?? 'No Definido' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">
                                            {{ $tipo->total }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <p class="text-gray-900 font-bold">S/ {{ number_format($tipo->ingresos, 2) }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $porcentaje = ($tipo->ingresos / max($stats['total_ingresos'], 1)) * 100;
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                        <p class="text-[10px] text-right mt-1 text-gray-500">{{ number_format($porcentaje, 1) }}%</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
