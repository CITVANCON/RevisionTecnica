<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full shadow-lg">
        <div class="pb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl uppercase">
                        <i class="fas fa-clipboard-check mr-2"></i>RESULTADO DE AUDITORIA CITV {{ $nombreMes }} {{ $anio }}
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

            <div class="px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal table-auto">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Fecha de Operación
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Certificados Emitidos
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Monto del Día (S/)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($reporte as $index => $fila)
                                    <tr class="border-b border-gray-100 hover:bg-indigo-50 transition" wire:key="aud-{{ $fila->fecha_inspeccion }}">
                                        <td class="px-4 py-3 font-mono text-gray-400 text-xs">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-sm">
                                            {{ \Carbon\Carbon::parse($fila->fecha_inspeccion)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full font-bold text-xs">
                                                {{ $fila->total_certificados }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-900">
                                            {{ number_format($fila->monto_dia, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50">
                                            <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                            No hay registros de inspecciones para el mes seleccionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($reporte->count() > 0)
                            <tfoot>
                                <tr class="bg-accent text-white border-t-2 border-secondary">
                                    <td colspan="2" class="px-6 py-4 text-right font-bold uppercase text-xs text-white">
                                        Balance Total del Mes:
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-[10px] text-white uppercase">Total Certificados</div>
                                        <div class="text-xl font-black">{{ $balance['total_certificados'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-[10px] text-white uppercase">Ingreso Bruto</div>
                                        <div class="text-xl font-black text-orange-500">
                                            S/ {{ number_format($balance['ingreso_bruto'], 2) }}
                                        </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="mt-4 flex justify-between items-center px-1">
                    <p class="text-[10px] text-gray-400 italic uppercase">
                        <i class="fas fa-info-circle mr-1"></i> Generado el {{ now()->format('d/m/Y H:i') }}
                    </p>
                    <button onclick="window.print()" class="text-xs font-bold text-gray-500 hover:text-indigo-600 transition">
                        <i class="fas fa-print mr-1"></i> Imprimir Auditoría
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
