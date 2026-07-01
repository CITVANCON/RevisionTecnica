<div class="space-y-6">
    <!-- Bienvenida -->
    <div class="bg-gradient-to-r from-green-600 via-green-700 to-emerald-700 rounded-xl shadow-lg text-white p-8">

        <h1 class="text-3xl font-bold">
            ¡Hola, {{ Auth::user()->name }}! 👋
        </h1>

        <p class="mt-2 text-green-100">
            Bienvenido al Sistema de Gestión del Centro de Inspección Técnica Vehicular.
        </p>

    </div>

    <!-- Indicadores -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-600">
            <p class="text-gray-500 text-sm">
                Ingresos Hoy
            </p>
            <h2 class="text-3xl font-bold mt-2">
                S/. {{ number_format($stats['ingresos'], 2) }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">
                Servicios Realizados
            </p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $stats['inspecciones'] }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-emerald-600">
            <p class="text-gray-500 text-sm">
                Aprobados
            </p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $stats['aprobados'] }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <p class="text-gray-500 text-sm">
                Anulados
            </p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $stats['anulados'] }}
            </h2>
        </div>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 xl:grid-rows-2 gap-6">
        <!-- Grafico de Ingresos -->
        <div class="xl:col-span-2 xl:row-span-2 flex flex-col">
            <x-charts.chart id="graficoIngresos" type="line" :data="$graficoIngresos" title="Ingresos últimos 30 días" height="440"/>
        </div>
        <!-- Resumen Operativo -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-6">
                    Resumen Operativo
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span>Inspecciones de Ley</span>
                        <span class="font-bold">{{ $operativo['ley'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Servicios Extras</span>
                        <span class="font-bold">{{ $operativo['extras'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Reinspecciones</span>
                        <span class="font-bold">{{ $operativo['reinspecciones'] }}</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-between pt-4 border-t border-gray-100 mt-4">
                <span class="font-medium text-gray-600">Ticket Promedio</span>
                <span class="font-bold text-green-600">
                    S/. {{ number_format($operativo['ticket'], 2) }}
                </span>
            </div>
        </div>
        <!-- Resumen Financiero -->
        <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-6">
                    Resumen Financiero
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span>Ingresos Ley</span>
                        <span class="font-bold text-green-600">
                            S/. {{ number_format($financiero['ley'], 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ingresos Servicios Extras</span>
                        <span class="font-bold text-green-600">
                            S/. {{ number_format($financiero['extras'], 2) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex justify-between text-lg pt-4 border-t border-gray-200 mt-4">
                <span class="font-semibold text-gray-800">
                    Total
                </span>
                <span class="font-bold text-green-700">
                    S/. {{ number_format($financiero['total'], 2) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Ultimas Inspecciones -->        
        <div class="bg-white rounded-xl shadow xl:col-span-2">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700">
                    Últimas Inspecciones
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Hora
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Placa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                Servicio
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase">
                                Resultado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase">
                                Monto
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($ultimasInspecciones as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    {{ date('H:i', strtotime($item->hora)) }}
                                </td>
                                <td class="px-6 py-4 font-semibold">
                                    {{ $item->placa }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->servicio }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $color = str_contains(strtoupper($item->resultado),'APRO')
                                            ? 'bg-green-100 text-green-700'
                                            : (str_contains(strtoupper($item->resultado),'DES')
                                                ? 'bg-red-100 text-red-700'
                                                : 'bg-yellow-100 text-yellow-700');
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                        {{ $item->resultado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-green-700">
                                    S/. {{ number_format($item->monto,2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    No existen inspecciones registradas hoy.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Doughnut -->
        <x-charts.chart id="graficoServicios" type="donut" :data="$graficoServicios" title="Distribución de Servicios" />       
    </div>    

</div>
