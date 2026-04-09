<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-4">
            <!-- Título y Filtros -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>Reporte Detallado de Inspecciones
                    </h2>
                    <span class="text-xs">Reporte de pagos, Control de Boletas y Certificados por dia</span>
                </div>
                {{-- 
                    <div class="flex gap-3 mt-4 md:mt-0 px-2">
                        <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                            <span class="text-sm mr-2 font-medium text-gray-500">Fecha:</span>
                            <input type="date" wire:model.live="fecha"
                                class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold">
                        </div>
                    </div>
                --}}
                <div class="flex flex-wrap gap-3 mt-4 md:mt-0 px-2 items-center">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100 h-12">
                        <span class="text-sm mr-2 font-medium text-gray-500">Fecha:</span>
                        <input type="date" wire:model.live="fecha"
                            class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold">
                    </div>
                    <div wire:click="abrirAuditoria({{ $efectivo_neto }}, {{ $total_tarjetas }})" 
                        class="flex bg-gray-50 p-2 rounded-md shadow-sm border-l-4 {{ $cierreActual && $cierreActual->estado == 'cuadrado' ? 'border-indigo-500' : 'border-red-500' }} items-center justify-between hover:bg-white cursor-pointer transition border-gray-100 h-12 min-w-[140px]">
                        <div class="mr-3">
                            <span class="text-[10px] text-gray-500 font-bold uppercase block leading-none">Auditoría</span>
                            <span class="text-xs font-black uppercase {{ $cierreActual && $cierreActual->estado == 'cuadrado' ? 'text-indigo-600' : 'text-red-500' }}">
                                {{ $cierreActual ? $cierreActual->estado : 'PENDIENTE' }}
                            </span>
                        </div>
                        <i class="fas fa-clipboard-check {{ $cierreActual && $cierreActual->estado == 'cuadrado' ? 'text-indigo-400' : 'text-gray-400' }} fa-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Resumen General -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2 mb-2">
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex items-center justify-between">
                    <span class="text-sm text-gray-500 font-medium">Total Recaudado</span>
                    <span class="text-xl font-bold text-gray-800">S/ {{ number_format($total_monto, 2) }}</span>
                    <i class="fas fa-coins text-green-400 fa-lg"></i>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-orange-400 flex items-center justify-between">
                        <span class="text-sm text-gray-500 font-medium">En Caja</span>
                        <span class="text-xl font-black text-gray-800">S/ {{ number_format($efectivo_neto, 2) }}</span>
                        <i class="fas fa-cash-register text-orange-400 fa-lg"></i>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-400 flex items-center justify-between">
                        <span class="text-sm text-gray-500 font-medium">Comisiones</span>
                        <span class="text-xl font-black text-gray-800">S/ {{ number_format($total_comisiones, 2) }}</span>
                        <i class="fas fa-hand-holding-usd text-blue-400 fa-lg"></i>
                </div>
                <div Onclick="window.print()" class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-400 flex items-center justify-between hover:bg-gray-50 cursor-pointer transition">
                    <span class="text-sm text-gray-600 font-medium">Imprimir Reporte</span>
                    <i class="fas fa-print text-gray-500 fa-lg"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2 mb-2">
                @foreach ($resumenPagos as $metodo => $datos)
                    @php
                        $color = match ($metodo) {
                            'EFECTIVO' => 'green',
                            'YAPE' => 'indigo',
                            'VISA' => 'blue',
                            'TRANSFERENCIA' => 'orange',
                            default => 'gray',
                        };
                    @endphp
                    <div
                        class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-{{ $color }}-500 flex items-center justify-between">
                        <span class="text-sm text-gray-500 font-medium">{{ $metodo ?: 'No Definido' }}</span>
                        <span class="text-xl font-bold text-gray-800">S/ {{ number_format($datos['total'], 2) }}</span>
                        <span class="text-xs text-gray-400">{{ $datos['cantidad'] }} servicios</span>
                    </div>
                @endforeach
            </div>

            <!-- Tabla de Inspecciones (Ingresos) -->
            <div class="px-2">
                @if ($inspecciones->count() > 0)
                    <h3 class="text-lg font-bold text-gray-700 mb-2 flex items-center">
                        Inspecciones Realizadas
                    </h3>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal table-auto">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Item
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Placa
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Tipo de Atención
                                        </th>
                                        <th
                                            class="px-3 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Categ / R
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Monto (S/)
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            N° Formato
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Pago / Comprob
                                        </th>
                                        <th
                                            class="px-4 py-3 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Comisión
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($inspecciones as $item)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition
                                            {{ $item->fecha_anulacion ? 'bg-red-50 italic text-gray-400' : '' }}
                                            {{ $item->estado_inspeccion === 'Anulada' ? 'bg-yellow-50 text-yellow-700' : '' }}
                                            {{ $item->resultado_estado === 'D' && !$item->fecha_anulacion ? 'bg-blue-50 text-blue-700' : '' }}"
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
                                            <td class="px-4 py-3 w-62 uppercase text-[11px] text-gray-700 font-medium">
                                                {{ $item->tipo_atencion }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-center uppercase text-indigo-700 font-bold text-sm">
                                                {{ $item->categoria_vehiculo }}
                                                @if ($item->es_reinspeccion === 'S')
                                                    <span class="block text-[9px] text-orange-600 font-black mt-0.5">REINSP.</span>
                                                @endif
                                                @if ($item->resultado_estado === 'D')
                                                    <span class="block text-[9px] text-blue-600 font-black mt-0.5">DESAPRO.</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right font-bold {{ $item->estado_inspeccion === 'Anulada' || $item->fecha_anulacion ? 'text-gray-400' : 'text-gray-950' }} decimal-align">
                                                {{ number_format($item->monto_total, 2) }}
                                            </td>
                                            <td class="px-4 py-3 font-mono text-sm text-gray-600">
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
                                                {{ $item->comision_monto > 0 ? 'S/ ' . number_format($item->comision_monto, 2) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-accent text-white font-bold">
                                        <td colspan="5"
                                            class="px-4 py-4 text-right uppercase tracking-wider text-xs">
                                            TOTALES GENERALES ({{ $inspecciones->whereNull('fecha_anulacion')->where('estado_inspeccion', '!=', 'Anulada')->count() }} VÁLIDAS DE {{ $inspecciones->count() }} REGISTROS)
                                        </td>
                                        <td class="px-4 py-4 text-right text-orange-500 text-base">
                                            S/ {{ number_format($total_monto, 2) }}
                                        </td>
                                        <td colspan="3" class="px-4 py-4 bg-accent"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-center bg-gray-100 rounded-lg text-gray-500 italic">
                        No se encontraron registros para el dia seleccionado.
                    </div>
                @endif
            </div>

            <!-- Tabla de Gastos (Egresos)-->
            <div class="mt-4 px-2">
                @if ($gastos->count() > 0)
                    <h3 class="text-lg font-bold text-gray-700 mb-2 flex items-center">
                        Gastos Operativos del Día
                    </h3>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 border-b-2 border-gray-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Item</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Descripción</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Método Pago</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase">Monto (S/)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach ($gastos as $gasto)
                                    <tr class="border-b border-gray-50 hover:bg-red-50/30 transition"
                                        wire:key="gasto-{{ $gasto->id }}">
                                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 uppercase font-medium">
                                            {{ $gasto->descripcion }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 rounded-full text-[10px] font-bold {{ $gasto->metodo_pago == 'EFECTIVO' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $gasto->metodo_pago }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-red-600">
                                            {{ number_format($gasto->monto, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-accent text-white font-bold">
                                    <td colspan="3" class="px-4 py-3 text-right text-xs uppercase">
                                        Total Gastos Diarios
                                    </td>
                                    <td class="px-4 py-4 text-right text-orange-500 text-base">
                                        S/ {{ number_format($total_gastos, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center bg-gray-100 rounded-lg text-gray-500 italic">
                        No se registraron gastos operativos para esta fecha.
                    </div>
                @endif
            </div>

            <div class="text-xs text-gray-400 mt-4 italic px-1">
                * Las filas en rojo itálico corresponden a inspecciones anuladas. <br>
                * Las filas en amarillo no culminaron con la inspección. <br>
                * Las filas en azul corresponden a inspecciones desaprobadas.
            </div>

        </div>
    </div>

    <!-- Modal de Auditoría y Conciliación -->
    <x-dialog-modal wire:model.live="mostrarModalAuditoria">
        <x-slot name="title">
            Confirmar Conciliación del Día: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-3 rounded border">
                    <span class="text-xs font-bold text-gray-500 uppercase">Efectivo en Caja (Neto)</span>
                    <div class="text-xl font-bold">S/ {{ number_format($formAuditoria['efectivo_esperado'], 2) }}</div>
                    @hasanyrole('Administrador del sistema|Auditoria')
                        <div class="mt-2">
                            <x-label value="Monto Real Depositado/Contado" />
                            <x-input type="number" step="0.01" class="w-full" wire:model="formAuditoria.efectivo_real" />
                        </div>
                    @endhasanyrole
                </div>
                <div class="bg-gray-50 p-3 rounded border">
                    <span class="text-xs font-bold text-gray-500 uppercase">Tarjetas/Yape (Sistema)</span>
                    <div class="text-xl font-bold">S/ {{ number_format($formAuditoria['pos_esperado'], 2) }}</div>
                    @hasanyrole('Administrador del sistema|Auditoria')
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <x-label value="Monto Real POS" class="text-[10px]" />
                                <x-input type="number" step="0.01" class="w-full text-sm" wire:model.live="formAuditoria.pos_real" />
                            </div>
                            <div>
                                <x-label value="Comisión POS (-)" class="text-[10px] text-red-500" />
                                <x-input type="number" step="0.01" class="w-full text-sm border-red-200" wire:model.live="formAuditoria.comision_pos" />
                            </div>
                        </div>
                        <div class="mt-2 bg-blue-100 p-2 rounded flex justify-between items-center">
                            <span class="text-xs font-bold text-blue-800 uppercase">Neto a Banco:</span>
                            <span class="text-lg font-black text-blue-900">S/ {{ number_format($formAuditoria['monto_neto_pos'], 2) }}</span>
                        </div>
                    @endhasanyrole
                </div>
            </div>

            @hasanyrole('Administrador del sistema|Auditoria')
                <div class="mt-4">
                    <x-label value="Estado de Auditoría" />
                    <select wire:model="formAuditoria.estado" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="pendiente">Pendiente</option>
                        <option value="cuadrado">Cuadrado (Conforme)</option>
                        <option value="observado">Observado (Descuadre)</option>
                    </select>
                </div>
            @endhasanyrole

            <div class="mt-4">
                <x-label value="Observaciones o Notas de Auditoría" />
                <textarea wire:model="formAuditoria.observacion" class="w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('mostrarModalAuditoria', false)">Cerrar</x-secondary-button>
            <x-button class="ml-3" wire:click="guardarAuditoria">Guardar Cierre</x-button>
        </x-slot>
    </x-dialog-modal>

</div>
