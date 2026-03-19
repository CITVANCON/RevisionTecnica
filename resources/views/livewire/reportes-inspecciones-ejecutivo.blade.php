<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <!-- Título y Filtros -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>Reporte Detallado de Inspecciones
                    </h2>
                    <span class="text-xs">Reporte de pagos, Control de Boletas y Certificados por Rango de Fechas</span>
                </div>
                <div class="flex gap-3 mt-4 md:mt-0 px-2">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2 font-medium text-gray-500">Desde:</span>
                        <input type="date" wire:model.live="fecha_inicio"
                            class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold">
                    </div>
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2 font-medium text-gray-500">Hasta:</span>
                        <input type="date" wire:model.live="fecha_fin"
                            class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold">
                    </div>
                </div>
            </div>
            <!-- Resumen General -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-2 mb-2">
                <div
                    class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex items-center justify-between">
                    <span class="text-sm text-gray-500 font-medium">Total Recaudado</span>
                    <span class="text-xl font-bold text-gray-800">S/ {{ number_format($total_monto, 2) }}</span>
                </div>
                <div
                    class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500 flex items-center justify-between">
                    <span class="text-sm text-gray-500 font-medium">Total Inspecciones</span>
                    <span class="text-xl font-bold text-gray-800">{{ $total_inspecciones }}</span>
                </div>
                <div
                    class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-400 flex items-center justify-between hover:bg-gray-50 cursor-pointer transition">
                    <span class="text-sm text-gray-600 font-medium">Imprimir Reporte</span>
                    <i class="fas fa-print text-gray-500 fa-lg"></i>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-2 mb-6">
                @foreach($resumenPagos as $metodo => $datos)
                    @php
                        $color = match($metodo) {
                            'EFECTIVO' => 'green', 'YAPE' => 'indigo', 'VISA' => 'blue', 'TRANSFERENCIA' => 'orange', default => 'gray'
                        };
                    @endphp
                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-{{ $color }}-500 flex items-center justify-between">
                        <span class="text-sm text-gray-500 font-medium">{{ $metodo ?: 'No Definido' }}</span>
                        <span class="text-xl font-bold text-gray-800">S/ {{ number_format($datos['total'], 2) }}</span>
                        <span class="text-xs text-gray-400">{{ $datos['cantidad'] }} servicios</span>
                    </div>

                @endforeach
            </div>

            <!-- Tabla de Inspecciones -->
            <div class="px-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal table-auto">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Item
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Placa
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Tipo de Atención
                                    </th>
                                    <th class="px-3 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Categ / R
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Monto (S/)
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        N° Formato
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Pago / Comprob
                                    </th>
                                    <th class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        Comisión
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse ($inspecciones as $item)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition {{ $item->fecha_anulacion ? 'bg-red-50 italic text-gray-400' : '' }}"
                                        wire:key="rep-{{ $item->id }}">
                                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($item->fecha_inspeccion)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 font-bold uppercase text-gray-900">
                                            {{ $item->placa_vehiculo }}
                                        </td>
                                        <td class="px-4 py-3 w-62 uppercase text-[10px] text-gray-700 font-medium">
                                            {{ $item->tipo_atencion }}
                                        </td>
                                        <td class="px-4 py-3 text-center uppercase text-indigo-700 font-bold text-sm">
                                            {{ $item->categoria_vehiculo }}
                                            @if($item->es_reinspeccion === 'S')
                                                <span class="block text-[9px] text-orange-600 font-black mt-0.5">REINSP.</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-950 decimal-align">
                                            {{ number_format($item->monto_total, 2) }}
                                        </td>
                                        <td class="px-4 py-3 font-mono text-[12px] text-gray-600">
                                            {{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="text-xs font-bold text-gray-800">
                                                {{ $item->metodo_pago ?? 'SN' }}
                                            </div>
                                            <div class="text-[11px] text-indigo-600">
                                                {{ $item->nro_comprobante ?? 'NN' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 font-medium">
                                            {{ $item->comision_monto > 0 ? 'S/ '.number_format($item->comision_monto, 2) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-10 text-center bg-gray-50 text-gray-500">
                                            <i class="fas fa-search fa-2x mb-3 text-gray-300"></i><br>
                                            No se encontraron registros para el rango de fechas seleccionado.
                                        </td>
                                    </tr>
                                @endforelse

                                @if ($inspecciones->count() > 0)
                                    <tr class="bg-accent text-white font-bold border-t-2 border-secondary">
                                        <td colspan="5"
                                            class="px-4 py-4 text-right uppercase tracking-wider text-xs text-white">
                                            Totales Generales ({{ $total_inspecciones }} registros)
                                        </td>
                                        <td class="px-4 py-4 uppercase text-xs text-white">
                                            --
                                        </td>
                                        <td class="px-4 py-4 text-right text-orange-500 text-base">
                                            S/ {{ number_format($total_monto, 2) }}
                                        </td>
                                        <td colspan="2" class="px-4 py-4 bg-accent">
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-xs text-gray-400 mt-3 italic px-1">
                    * Las filas en rojo itálico corresponden a inspecciones anuladas.
                </div>
            </div>

        </div>
    </div>
</div>
