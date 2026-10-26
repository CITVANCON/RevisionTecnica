<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-4" id="printArea">            
            <!-- Título y Filtros -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-clipboard-check mr-2"></i>Auditoría y Conciliación de Placas
                    </h2>
                    <span class="text-xs text-gray-500">Cruce automatizado entre Línea (Cámara) y Sistema (Inspecciones Maestras)</span>
                </div>
                
                <div class="flex flex-wrap gap-3 mt-4 md:mt-0 px-2 items-center">
                    <!-- Selector de Fecha Estilizado -->
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100 h-12">
                        <span class="text-sm mr-2 font-medium text-gray-500">Fecha:</span>
                        <input type="date" wire:model.live="fecha"
                            class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold">
                    </div>
                    <!-- Botón para Abrir Tarifario -->
                    <button wire:click="abrirModalTarifario" class="flex bg-indigo-50 px-4 py-2 rounded-md shadow-sm border border-indigo-200 h-12 items-center gap-2 hover:bg-indigo-100 text-indigo-700 transition font-medium text-sm">
                        <i class="fas fa-table-list text-indigo-500"></i>
                        <span>Ver Tarifario</span>
                    </button>
                    <!-- Botón de Imprimir -->
                    <div onclick="window.print()" class="flex bg-gray-50 px-4 py-2 rounded-md shadow-sm border border-gray-100 h-12 items-center gap-2 hover:bg-white cursor-pointer transition">
                        <span class="text-sm text-gray-600 font-medium">Imprimir Reporte</span>
                        <i class="fas fa-print text-gray-500 fa-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de Resumen (Estilo Ejecutivo con Borde Izquierdo) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 font-bold uppercase block">Vehículos Conformes</span>
                        <span class="text-2xl font-bold text-gray-800">{{ count($coincidentes) }}</span>
                    </div>
                    <i class="fas fa-check-circle text-green-400 fa-2x"></i>
                </div>                
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-rose-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 font-bold uppercase block">En Línea sin Certificado</span>
                        <span class="text-2xl font-black text-rose-600">{{ count($soloEnLinea) }}</span>
                    </div>
                    <i class="fas fa-exclamation-triangle text-rose-400 fa-2x"></i>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-amber-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 font-bold uppercase block">En Sistema sin Cámara</span>
                        <span class="text-2xl font-black text-amber-600">{{ count($soloEnSistema) }}</span>
                    </div>
                    <i class="fas fa-search text-amber-400 fa-2x"></i>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-indigo-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 font-bold uppercase block">Alertas de Tarifas</span>
                        <span class="text-2xl font-black text-indigo-600">{{ count($auditoriaTarifas) }}</span>
                    </div>
                    <i class="fas fa-coins text-indigo-400 fa-2x"></i>
                </div>
            </div>

            <!-- TABLA 1: VEHÍCULOS CONFORMES (Coincidentes) -->
            <div class="px-2 mb-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i> Vehículos Conformes (Cámara y Sistema coinciden)
                </h3>
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal table-auto">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-100">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Placa</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Hora de Captura (Línea)</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Estado de Inspección</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($coincidentes as $item)
                                <tr class="border-b border-gray-100 hover:bg-green-50/30 transition">
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-bold uppercase text-green-700">{{ $item['placa'] }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $item['hora_camara'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs rounded font-semibold bg-emerald-100 text-emerald-800">
                                            {{ $item['resultado'] }}
                                        </span>
                                        
                                        <!-- Indicador de Tarifa -->
                                        @if($item['auditoria_tarifa']['estado'] == 'correcto')
                                            <span class="block text-[10px] text-green-600 font-bold mt-1">S/ {{ number_format($item['monto'], 2) }} (Tarifa OK)</span>
                                        @else
                                            <span class="block text-[10px] text-rose-600 font-black mt-1" title="{{ $item['auditoria_tarifa']['mensaje'] }}">
                                                S/ {{ number_format($item['monto'], 2) }} (⚠️ {{ strtoupper($item['auditoria_tarifa']['estado']) }})
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center bg-gray-50 text-gray-400 italic">No hay vehículos conformes registrados para la fecha seleccionada.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TABLA 3: ALERTA AMARILLA (Certificado sin paso por cámara) -->
            <div class="px-2 mb-4">
                <h3 class="text-lg font-bold text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-search text-amber-500 mr-2"></i> Certificados Emitidos sin Registro de Cámara (Posible registro manual / fantasma)
                </h3>
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal table-auto">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-100">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Placa en Sistema</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Hora de Inspección</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Propietario</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Resultado</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($soloEnSistema as $item)
                                <tr class="border-b border-gray-100 hover:bg-amber-50/30 transition">
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-bold uppercase text-amber-700">{{ $item['placa'] }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $item['hora_inicio'] }}</td>
                                    <td class="px-4 py-3 uppercase text-xs text-gray-700 font-medium">{{ $item['propietario'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs rounded font-semibold {{ $item['resultado'] == 'APROBADO' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $item['resultado'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center bg-gray-50 text-gray-400 italic">No hay discrepancias en esta categoría para la fecha seleccionada.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TABLA 2: ALERTA ROJA (En línea sin certificado) -->
            <div class="px-2 mb-6">
                <h3 class="text-lg font-bold text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-exclamation-triangle text-rose-500 mr-2"></i> Vehículos Detectados en Línea pero SIN Certificado (Posible ingreso sin registro)
                </h3>
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal table-auto">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-100">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Placa Detectada</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Fecha / Hora de Captura</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Nivel de Confianza OCR</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($soloEnLinea as $item)
                                <tr class="border-b border-gray-100 hover:bg-rose-50/30 transition">
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-bold uppercase text-rose-600">{{ $item['placa'] }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $item['hora_captura'] }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-800">{{ number_format($item['confianza'] * 100, 1) }}%</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center bg-gray-50 text-gray-400 italic">No hay discrepancias en esta categoría para la fecha seleccionada.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(count($auditoriaTarifas) > 0)
                <div class="px-2 mb-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-file-invoice-dollar text-indigo-600 mr-2"></i> Auditoría Financiera: Discrepancias en Tarifas Cobradas
                    </h3>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal table-auto">
                                <thead>
                                    <tr class="border-b-2 border-gray-200 bg-gray-100">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Placa</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Categoría</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tipo Atencion</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Monto Cobrado</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Detalle de Alerta</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach($auditoriaTarifas as $alerta)
                                    <tr class="border-b border-gray-100 hover:bg-indigo-50/50 transition">
                                        <td class="px-4 py-3 font-bold uppercase text-indigo-700">{{ $alerta['placa'] }}</td>
                                        <td class="px-4 py-3 text-gray-700 font-semibold">{{ $alerta['categoria'] }}</td>
                                        <td class="px-4 py-3 text-gray-700 font-semibold">{{ $alerta['tipo_atencion'] }}</td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-900">S/ {{ number_format($alerta['monto'], 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded font-bold {{ $alerta['tipo'] == 'subcobro' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ strtoupper($alerta['tipo']) }}: {{ $alerta['mensaje'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Notas al pie -->
            <div class="text-xs text-gray-500 mt-4 italic px-2">
                * <strong class="text-green-700">Vehículos Conformes:</strong> Casos donde la cámara y el sistema coinciden correctamente.<br>
                * <strong class="text-rose-600">Alerta Roja:</strong> Vehículos capturados físicamente en la línea pero que no registran trámite en el sistema.<br>
                * <strong class="text-amber-600">Alerta Amarilla:</strong> Certificados emitidos en el sistema sin evidencia visual previa de la cámara de la planta.
            </div>

        </div>
    </div>

    <!-- Modal para Consultar el Tarifario Oficial -->
    <x-dialog-modal wire:model.live="mostrarTarifarioModal">
        <x-slot name="title">
            <div class="flex items-center gap-2 font-bold">
                <i class="fas fa-file-invoice-dollar"></i> Matriz del Tarifario Oficial de CITV ANCÓN S.A.C.
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="max-h-[60vh] overflow-y-auto">
                <table class="min-w-full leading-normal table-auto border border-gray-200">
                    <thead class="sticky top-0 bg-gray-100 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-3 py-2 text-center">Cat.</th>
                            <th class="px-3 py-2 text-left">Tipo de Servicio / Atención</th>
                            <th class="px-3 py-2 text-right">Tarifa Base (Mín.)</th>
                            <th class="px-3 py-2 text-right">Precio Máx. (Con recargo)</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-200">
                        @foreach($listaTarifarios as $t)
                        <tr class="hover:bg-indigo-50/50">
                            <td class="px-3 py-2 text-center font-bold text-indigo-700 bg-gray-50">{{ $t->categoria_vehiculo }}</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $t->tipo_atencion }}</td>
                            <td class="px-3 py-2 text-right font-bold text-green-700">S/ {{ number_format($t->precio_minimo, 2) }}</td>
                            <td class="px-3 py-2 text-right font-semibold text-gray-600">S/ {{ number_format($t->precio_maximo, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('mostrarTarifarioModal', false)">
                Cerrar Ventana
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Estilo de impresión adaptado -->
    <style>
        @media print {
            body * { visibility: hidden; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</div>