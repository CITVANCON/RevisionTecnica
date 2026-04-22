<div class="container mx-auto py-8 px-4">
    <div class="bg-gray-200 p-6 rounded-2xl shadow-xl border border-gray-200">        
        <!-- Encabezado y Controles -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-3">
                        <i class="fas fa-hand-holding-usd"></i>
                    </span>
                    Reporte de Comisiones
                </h2>
                <p class="text-sm text-gray-500">Periodo: {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <input type="text" wire:model.live="search" placeholder="Buscar placa o formato..." 
                    class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full lg:w-64">                
                <div class="flex bg-gray-50 border rounded-lg overflow-hidden">
                    <input type="date" wire:model.live="fecha_inicio" class="bg-transparent border-none text-xs focus:ring-0">
                    <span class="flex items-center text-gray-400 px-1">al</span>
                    <input type="date" wire:model.live="fecha_fin" class="bg-transparent border-none text-xs focus:ring-0">
                </div>
                <button onclick="window.print()" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                    <i class="fas fa-print mr-2"></i> Imprimir
                </button>
            </div>
        </div>
        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <span class="text-xs text-indigo-600 font-bold uppercase tracking-wider">Total Comisiones</span>
                <div class="text-2xl font-black text-indigo-900">S/ {{ number_format($stats['total_comisiones'], 2) }}</div>
            </div>
            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                <span class="text-xs text-emerald-600 font-bold uppercase tracking-wider">Inspecciones OK</span>
                <div class="text-2xl font-black text-emerald-900">{{ $stats['cantidad'] }}</div>
            </div>
            <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                <span class="text-xs text-amber-600 font-bold uppercase tracking-wider">Comisión Promedio</span>
                <div class="text-2xl font-black text-amber-900">S/ {{ number_format($stats['promedio'], 2) }}</div>
            </div>
            <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                <span class="text-xs text-red-600 font-bold uppercase tracking-wider">Anuladas</span>
                <div class="text-2xl font-black text-red-900">{{ $stats['anuladas'] }}</div>
            </div>
        </div>

        <!-- Tabla de Comisiones -->
        @if ($inspComision && $inspComision->count() > 0)
            <div id="printArea" class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-bold border-b">
                            <th class="px-4 py-4 text-left">#</th>
                            <th class="px-4 py-4 text-left">Fecha</th>
                            <th class="px-4 py-4 text-left">Placa</th>
                            <th class="px-4 py-4 text-left">Servicio / Categoría</th>
                            <th class="px-4 py-4 text-right">Monto (S/)</th>
                            <th class="px-4 py-4 text-left">N° Formato / Pago</th>
                            <th class="px-4 py-4 text-center">Comisión</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($inspComision as $item)
                            <tr class="{{ $item['clase_fila'] }} hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-xs font-mono text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item['fecha'])->format('d/m/y') }}</td>
                                <td class="px-4 py-3 font-bold text-gray-800">{{ $item['placa'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-xs font-medium text-gray-700 uppercase">{{ $item['servicio'] }}</div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-gray-400 font-bold">{{ $item['categoria'] }}</span>
                                        @if($item['badge'])
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-orange-100 text-orange-600 uppercase">{{ $item['badge'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-700">
                                    {{ number_format($item['monto'], 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-600 font-mono">{{ $item['formato'] }}</div>
                                    <div class="text-[10px] text-indigo-500 font-bold uppercase">{{ $item['metodo_pago'] }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-gray-100 px-3 py-1 rounded-full font-bold text-gray-700">
                                        S/ {{ number_format($item['comision'], 2) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-accent text-white font-bold">
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-right text-sm uppercase">Total Acumulado en Comisiones:</td>
                            <td class="px-4 py-3 text-center text-lg">S/ {{ number_format($stats['total_comisiones'], 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center p-12 bg-white rounded-xl border border-dashed border-gray-300">
                <i class="fas fa-folder-open text-gray-200 text-5xl mb-4"></i>
                <p class="text-gray-500 font-medium italic">No se encontraron registros con comisión para los criterios seleccionados.</p>
            </div>
        @endif
    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            #printArea, #printArea * { visibility: visible; }
            #printArea { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</div>