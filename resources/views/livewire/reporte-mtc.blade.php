<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-4">
            <!-- Encabezado y titulo -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl uppercase">
                        <i class="fas fa-file-contract mr-2 text-indigo-600"></i>Reporte Mensual MTC
                    </h2>
                    <span class="text-xs">Consolidado de inspecciones aprobadas, desaprobadas y anuladas</span>
                </div>
                
                <div class="flex gap-3 mt-4 md:mt-0 px-2">
                    <!-- MES -->
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2 font-medium text-gray-500">Mes:</span>
                        <select wire:model.live="mes" class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold uppercase">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- AÑO -->
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2 font-medium text-gray-500">Año:</span>
                        <select wire:model.live="anio" class="bg-gray-50 border-none outline-none focus:ring-0 text-sm text-indigo-700 font-bold">
                            @foreach(range(now()->year - 2, now()->year + 1) as $a)
                                <option value="{{ $a }}">{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- IMPRIMIR -->
                    <div onclick="window.print()" class="flex bg-white items-center px-4 py-2 rounded-md shadow-sm border border-gray-300 hover:bg-gray-100 cursor-pointer transition">
                        <i class="fas fa-print text-gray-600"></i>
                    </div>
                    <!-- PDF -->
                    <div wire:click="descargarPdf" wire:loading.attr="disabled"
                        class="flex bg-red-500 text-white items-center px-4 py-2 rounded-md shadow-sm border border-red-500 hover:bg-red-600 cursor-pointer transition">
                        <i wire:loading wire:target="descargarPdf" class="fas fa-spinner fa-spin mr-2"></i>
                        <i wire:loading.remove wire:target="descargarPdf" class="fas fa-file-pdf"></i>
                        
                        <span wire:loading.remove wire:target="descargarPdf"></span>
                        <span wire:loading wire:target="descargarPdf">Generando...</span>
                    </div>
                    <!-- EXCELL -->
                    <div {{--wire:click="descargarExcell" wire:loading.attr="disabled"--}}
                        class="flex bg-green-500 text-white items-center px-4 py-2 rounded-md shadow-sm border border-green-500 hover:bg-green-600 cursor-pointer transition">
                        <i wire:loading wire:target="descargarExcell" class="fas fa-spinner fa-spin mr-2"></i>
                        <i wire:loading.remove wire:target="descargarExcell" class="fas fa-file-excel"></i>
                        
                        <span wire:loading.remove wire:target="descargarExcell"></span>
                        <span wire:loading wire:target="descargarExcell">Generando...</span>
                    </div>
                </div>
            </div>

            <!-- Resumen de indicadores -->            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2 mb-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-bold">Aprobados</span>
                        <span class="text-2xl font-black text-gray-800">{{ $inspeccionados->count() }}</span>
                    </div>
                    <i class="fas fa-check-circle text-green-400 fa-2x"></i>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-bold">Desaprobados</span>
                        <span class="text-2xl font-black text-gray-800">{{ $desaprobados->count() }}</span>
                    </div>
                    <i class="fas fa-times-circle text-red-400 fa-2x"></i>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-gray-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-bold">Anulados</span>
                        <span class="text-2xl font-black text-gray-800">{{ $anulados->count() }}</span>
                    </div>
                    <i class="fas fa-ban text-gray-400 fa-2x"></i>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-orange-500 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-bold">En Proceso / Otros</span>
                        <span class="text-2xl font-black text-gray-800">{{ $huerfanos->count() }}</span>
                    </div>
                    <i class="fas fa-pause-circle text-orange-400 fa-2x"></i>
                </div>
            </div>

            

            <!-- Tabla inspeccionados -->
            <div class="px-2 mb-10">
                @if ($inspeccionados->count() > 0)
                    <h3 class="text-sm font-bold text-gray-600 mb-3 flex items-center uppercase tracking-wider">
                        <i class="fas fa-table mr-2 text-green-500"></i> Tabla Vehículos Inspeccionados
                    </h3>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal table-auto">
                                <thead>
                                    <tr class="bg-gray-100 border-b-2 border-gray-200 text-[10px] text-gray-600 font-bold uppercase">
                                        <th class="px-3 py-3 text-left">Fecha ingreso</th>
                                        <th class="px-3 py-3 text-left">N° de placa</th>
                                        <th class="px-3 py-3 text-left">Serie Hoja</th>
                                        <th class="px-3 py-3 text-center">Resultado</th>
                                        <th class="px-3 py-3 text-left">Tipo Servicio Brindado</th>
                                        <th class="px-3 py-3 text-left">Tipo Servicio Destinado</th>
                                        <th class="px-3 py-3 text-center">Categoría</th>
                                        <th class="px-3 py-3 text-left">Fecha Próx. Certif.</th>
                                        <th class="px-3 py-3 text-left">Número Formato</th>
                                        <th class="px-3 py-3 text-center">Re insp.</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[11px]">
                                    @foreach ($inspeccionados as $item)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                            <td class="px-3 py-3">{{ $item->fecha_inspeccion?->format('d/m/Y') }}</td>
                                            <td class="px-3 py-3 font-bold text-gray-900 uppercase tracking-wider">{{ $item->placa_vehiculo }}</td>
                                            <td class="px-3 py-3 font-mono">{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                                            <td class="px-3 py-3 text-center font-bold text-green-600">{{ $item->resultado_estado }}</td>
                                            <td class="px-3 py-3 uppercase text-[9px]">{{ $item->tipo_inspeccion }}</td>
                                            <td class="px-3 py-3 uppercase text-[9px] font-medium">{{ $item->tipo_atencion }}</td>
                                            <td class="px-3 py-3 text-center font-bold text-indigo-700">{{ $item->categoria_vehiculo }}</td>
                                            <td class="px-3 py-3">{{ $item->fecha_vencimiento }}</td>
                                            <td class="px-3 py-3 font-mono">{{ $item->numero_certificado_mtc }}</td>
                                            <td class="px-3 py-3 text-center font-bold">{{ $item->es_reinspeccion }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-center bg-gray-100 rounded-lg text-gray-500 italic">
                        Sin registros de aprobados.
                    </div>
                @endif
            </div>

            <!-- Tabla desaprobados -->
            <div class="px-2 mt-4 mb-10">
                @if ($desaprobados->count() > 0)
                    <h3 class="text-sm font-bold text-gray-600 mb-3 flex items-center uppercase tracking-wider">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i> Tabla Vehículos Desaprobados
                    </h3>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal table-auto text-[11px]">
                                <thead>
                                    <tr class="bg-red-50 border-b-2 border-red-100 text-[10px] text-red-700 font-bold uppercase">
                                        <th class="px-4 py-3 text-left">F. Inspec</th>
                                        <th class="px-4 py-3 text-left">Placa</th>
                                        <th class="px-4 py-3 text-right">P. Bruto</th>
                                        <th class="px-4 py-3 text-center">Categoria</th>
                                        <th class="px-4 py-3 text-left">N Inf.</th>
                                        <th class="px-4 py-3 text-left">Grave (Códigos)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($desaprobados as $item)
                                        <tr class="border-b border-gray-100 hover:bg-red-50/30 transition">
                                            <td class="px-4 py-3">{{ $item->fecha_inspeccion?->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 font-bold text-gray-900">{{ $item->placa_vehiculo }}</td>
                                            <td class="px-4 py-3 text-right">{{ number_format($item->peso_bruto_v, 2) }}</td>
                                            <td class="px-4 py-3 text-center font-bold text-indigo-700">{{ $item->categoria_vehiculo }}</td>
                                            <td class="px-4 py-3 font-mono">{{ $item->numero_certificado_mtc }}</td>
                                            <td class="px-4 py-3 text-red-600 font-medium">{{ $item->codigos_defectos }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-center bg-gray-100 rounded-lg text-gray-500 italic">
                        Sin registros de desaprobados.
                    </div>
                @endif
            </div>

            <!-- Tabla anulados -->
            <div class="px-2 mt-4 mb-10">
                @if ($anulados->count() > 0)
                    <h3 class="text-sm font-bold text-gray-600 mb-3 flex items-center uppercase tracking-wider">
                        <i class="fas fa-trash-alt mr-2 text-gray-500"></i> Tabla Vehículos Anulados
                    </h3>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal table-auto text-[11px]">
                                <thead>
                                    <tr class="bg-gray-800 text-white border-b-2 border-gray-900 text-[10px] font-bold uppercase">
                                        <th class="px-4 py-3 text-left">ITEM</th>
                                        <th class="px-4 py-3 text-left">N° DE FORMATO</th>
                                        <th class="px-4 py-3 text-left">N° DE CALCOMANIA</th>
                                        <th class="px-4 py-3 text-left">TIPO DE ERROR</th>
                                        <th class="px-4 py-3 text-left">ACCION TOMADA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anulados as $item)
                                        <tr class="border-b border-gray-100 bg-gray-50 hover:bg-gray-100 italic transition">
                                            <td class="px-4 py-3 text-gray-500 font-mono">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 font-bold">{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                                            <td class="px-4 py-3 font-bold">{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                                            <td class="px-4 py-3 text-orange-700">ERROR DE IMPRESION</td>
                                            <td class="px-4 py-3 font-medium">SD‐388‐0018102</td>
                                        </tr>                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="p-4 text-center bg-gray-100 rounded-lg text-gray-500 italic">
                        No hay certificados anulados en este periodo.
                    </div>
                @endif
            </div>

            {{-- 
            <div class="mt-8 px-2">
                    <h3 class="text-sm font-bold text-orange-600 mb-3 flex items-center uppercase tracking-wider">
                        <i class="fas fa-microscope mr-2"></i> Registros Observados (No incluidos en el reporte)
                    </h3>
                    <div class="bg-orange-50 rounded-lg shadow-sm border border-orange-200 overflow-hidden">
                        <table class="min-w-full text-[11px]">
                            <thead class="bg-orange-100 text-orange-800 uppercase font-bold">
                                <tr>
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Placa</th>
                                    <th class="px-4 py-2 text-left">Estado Inspección</th>
                                    <th class="px-4 py-2 text-left">Resultado</th>
                                    <th class="px-4 py-2 text-left">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($huerfanos as $h)
                                    <tr class="border-b border-orange-100 text-orange-900">
                                        <td class="px-4 py-2">{{ $h->id }}</td>
                                        <td class="px-4 py-2 font-bold">{{ $h->placa_vehiculo }}</td>
                                        <td class="px-4 py-2">{{ $h->estado_inspeccion }}</td>
                                        <td class="px-4 py-2">{{ $h->resultado_estado ?: 'SIN RESULTADO' }}</td>
                                        <td class="px-4 py-2 italic text-xs">Probablemente incompleto o en proceso.</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
            --}}
            

            <div class="text-[10px] text-gray-400 mt-6 italic px-1">
                * Este reporte se genera automáticamente basado en los registros de inspección del sistema. Los campos marcados como "Serie Hoja" y
                 "Calcomanía" utilizan la concatenación de serie y correlativo según requerimiento.
            </div>
        </div>
    </div>
</div>