<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full shadow-lg">
        <div class="pb-6">
            <!-- Titulo y filtro mes -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl uppercase">
                        <i class="fas fa-clipboard-check mr-2"></i>RESULTADO DE AUDITORIA CITV {{ $nombreMes }}
                        {{ $anio }}
                    </h2>
                    <span class="text-xs">Resumen consolidado de operaciones diarias y balance de ingresos</span>
                </div>
                <div class="flex gap-3 mt-4 md:mt-0 px-2">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2 font-medium text-gray-500">Periodo:</span>
                        <input type="month" wire:model.live="mes_seleccionado"
                            class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold uppercase">
                    </div>
                </div>
            </div>

            <!-- Resumen indicadores -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2 mb-6">
                <div class="bg-white p-4 rounded-lg border-l-4 border-indigo-500 shadow-sm">
                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Certificados Emitidos</span>
                    <span class="text-2xl font-black text-gray-800">{{ $balance['total_certificados'] }}</span>
                </div>
                <div class="bg-white p-4 rounded-lg border-l-4 border-green-500 shadow-sm">
                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Ingreso Bruto (S/)</span>
                    <span
                        class="text-2xl font-black text-green-600">{{ number_format($balance['ingreso_bruto'], 2) }}</span>
                </div>
                <div class="bg-white p-4 rounded-lg border-l-4 border-red-500 shadow-sm">
                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Egresos Totales (S/)</span>
                    <span
                        class="text-2xl font-black text-red-600">{{ number_format($balance['total_gastos'], 2) }}</span>
                </div>
                <div class="bg-white p-4 rounded-lg border-l-4 border-orange-500 shadow-md ring-2 ring-orange-50">
                    <span class="text-[10px] font-bold text-orange-600 uppercase block">Utilidad Neta (S/)</span>
                    <span
                        class="text-2xl font-black text-orange-700">{{ number_format($balance['ingreso_neto'], 2) }}</span>
                </div>
            </div>

            <!-- Tabla reporte mensual-->
            <div class="px-2">
                @if ($reporte->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal table-auto">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Certificados
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Efectivo
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Gastos
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Saldo efectivo
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            POS
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Saldo por Día
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Total por Día
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @foreach ($reporte as $index => $fila)
                                        <tr class="border-b border-gray-100 hover:bg-indigo-50 transition"
                                            wire:key="aud-{{ $fila->fecha_inspeccion }}">
                                            <td class="px-4 py-3 font-mono text-gray-400 text-xs">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-4 py-3 font-semibold text-sm">
                                                {{ \Carbon\Carbon::parse($fila->fecha_inspeccion)->translatedFormat('d \d\e M Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span
                                                    class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full font-bold text-xs">
                                                    {{ $fila->total_certificados }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center font-medium">
                                                {{ number_format($fila->monto_efectivo, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center font-medium text-red-600">
                                                {{ number_format($fila->monto_gastos, 2) }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-center font-bold {{ $fila->saldo_efectivo < 0 ? 'text-red-700' : 'text-gray-900' }}">
                                                {{ number_format($fila->saldo_efectivo, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center font-medium">
                                                {{ number_format($fila->monto_pos, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center font-black text-green-700">
                                                {{ number_format($fila->saldo_dia, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-bold text-gray-900">
                                                {{ number_format($fila->monto_dia, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-accent text-white border-t-2">
                                    <tr>
                                        <td colspan="2" class="px-4 py-4 text-right font-bold uppercase text-xs">
                                            Totales del Mes:
                                        </td>
                                        <td class="px-4 py-4 text-center font-bold text-sm">
                                            {{ $reporte->sum('total_certificados') }}
                                        </td>
                                        <td class="px-4 py-4 text-center font-bold text-sm">
                                            {{ number_format($reporte->sum('monto_efectivo'), 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-center font-bold text-sm text-red-300">
                                            {{ number_format($reporte->sum('monto_gastos'), 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-center font-bold text-sm text-green-300">
                                            {{ number_format($reporte->sum('saldo_efectivo'), 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-center font-bold text-sm text-blue-300">
                                            {{ number_format($reporte->sum('monto_pos'), 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-center font-bold text-sm">
                                            {{ number_format($reporte->sum('saldo_dia'), 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-right font-bold text-sm">
                                            {{ number_format($reporte->sum('monto_dia'), 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-center bg-gray-100 rounded-lg text-gray-500 italic">
                        No se encontraron operaciones registradas para el periodo {{ $nombreMes }}.
                    </div>
                @endif

                <!-- Footer -->

                <div
                    class="mt-4 flex flex-col md:flex-row justify-between items-center px-2 border-t border-gray-300 pt-2">
                    <p class="text-[10px] text-gray-500 uppercase font-medium">
                        <i class="fas fa-shield-alt mr-1"></i> Sistema de Gestión CITV - Auditoría de Ingresos
                    </p>
                    <button onclick="window.print()"
                        class="mt-2 md:mt-0 px-4 py-2 bg-gray-700 hover:bg-black text-white rounded text-xs font-bold transition flex items-center shadow-lg">
                        <i class="fas fa-print mr-2"></i> Generar Archivo de Auditoría
                    </button>

                </div>
                <div class="px-4 py-3 bg-blue-50 border-l-4 border-blue-400 mt-2 rounded-r-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-[11px] text-blue-700 leading-tight">
                                <strong>Nota sobre Transacciones Digitales:</strong> Los montos mostrados en la columna <strong>POS</strong> (Yape, Visa, Transferencia) representan el ingreso bruto de la operación. Tenga en cuenta que estos valores no incluyen los descuentos por comisiones de mantenimiento, pasarela de pago o impuestos bancarios aplicables por las entidades financieras.
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
