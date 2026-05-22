<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div id="printArea">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        📞 Reporte Detallado de Inspecciones
                    </h2>
                    <span class="text-xs">Control de clientes para seguimiento de call center</span>
                </div>
                <!-- FILTROS -->
                <div class="flex flex-wrap gap-3 mt-4 md:mt-0 px-2 items-center">
                    <!-- busqueda -->
                    <input type="text" wire:model.live="search" placeholder="Buscar placa o cliente..."
                        class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full lg:w-64">
                    <!-- estado -->
                    <select wire:model.live="filtro"
                        class="border-gray-300 rounded-lg text-sm ring-indigo-500 border-indigo-500 bg-white">
                        <option value="vencidos">Todos Vencidos</option>
                        <option value="7dias">Por vencer 7 días</option>
                        <option value="15dias">Por vencer 15 días</option>
                        <option value="30dias">Por vencer 30 días</option>
                    </select>
                    <!-- Rango de Fechas -->
                    <div class="flex bg-gray-50 border rounded-lg overflow-hidden">
                        <input type="date" wire:model.live="fecha_inicio"
                            class="bg-transparent border-none text-xs focus:ring-0">
                        <span class="flex items-center text-gray-400 px-1">al</span>
                        <input type="date" wire:model.live="fecha_fin"
                            class="bg-transparent border-none text-xs focus:ring-0">
                    </div>
                    <button wire:click="exportar" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                        <i class="fas fa-file-excel mr-2"></i> Excell
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="px-2">
                @if ($inspecciones->count() > 0)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100 text-xs text-gray-600 uppercase">
                                        <th class="px-4 py-3 text-left">Item</th>
                                        <th class="px-4 py-3 text-left">Placa</th>                                        
                                        <th class="px-4 py-3 text-left">Atencion</th>
                                        <th class="px-4 py-3 text-left">Cat</th>
                                        <th class="px-4 py-3 text-left">Cliente</th>
                                        <th class="px-4 py-3 text-left">Celular</th>
                                        <th class="px-4 py-3 text-left">Vencimiento</th>
                                        <th class="px-4 py-3 text-left">Días</th>
                                        <th class="px-4 py-3 text-left">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($inspecciones as $item)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-2 font-bold">{{ $item->placa_vehiculo }}</td>                                            
                                            <td class="px-4 py-2">{{ $item->tipo_atencion }}</td>
                                            <td class="px-4 py-2">{{ $item->categoria_vehiculo }}</td>
                                            <td class="px-4 py-2">{{ $item->propietario_nombre }}</td>
                                            <td class="px-4 py-2">{{ $item->propietario_celular }}</td>
                                            <td class="px-4 py-2">
                                                {{ \Carbon\Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-2">
                                                {{ $item->dias }} días
                                            </td>
                                            <td class="px-4 py-2">
                                                @if($item->dias > 0)
                                                    <span class="text-red-600 font-bold">VENCIDO</span>
                                                @else
                                                    <span class="text-green-600 font-bold">VIGENTE</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4">
                        {{ $inspecciones->links() }}
                    </div>
                @else
                    <div class="p-4 text-center bg-gray-100 rounded-lg text-gray-500 italic">
                        No se encontraron inspecciones vencidas.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
