<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full shadow-lg">
        <div class="pb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl uppercase">
                        <i class="fa-solid fa-calendar-check mr-2 text-indigo-600"></i>Gestión de Egresos y Gastos
                    </h2>
                    <span class="text-xs font-medium text-gray-500">Control operativo diario y administrativo mensual del periodo:
                        <b class="text-indigo-600 uppercase">{{ $nombreMes }}</b>
                    </span>
                </div>
                <div class="mt-4 md:mt-0 px-2 flex flex-wrap gap-4 items-center justify-end">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="text-sm mr-2 font-semibold text-gray-600">Seleccionar Fecha:</span>
                        <input type="date" wire:model.live="fecha"
                            class="bg-gray-50 border-indigo-500 rounded-md outline-none focus:ring-0 text-sm">
                    </div>
                </div>
            </div>

            <div class="px-2 flex justify-end mb-2">
                <button wire:click="toggleFormulario"
                    class="flex items-center transition duration-300 font-bold text-xs
                    {{ $abrirFormulario ? 'text-red-500 hover:text-red-700' : 'text-indigo-600 hover:text-indigo-800' }}">
                    @if($abrirFormulario)
                        <i class="fas fa-times-circle mr-1"></i> Cancelar Registro
                    @else
                        <i class="fas fa-plus-circle mr-1"></i> Agregar Nuevo Gasto
                    @endif
                </button>
            </div>

            @if($abrirFormulario)
                <div x-data x-show="$wire.abrirFormulario" x-transition.duration.300ms
                    class="bg-white p-6 rounded-lg shadow-md border-gray-100 mb-8">
                    <form wire:submit.prevent="guardarGasto" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase">Tipo</label>
                            <select wire:model.live="tipo_egreso" class="w-full mt-1 bg-gray-50 border-indigo-400 rounded-md text-sm focus:ring-0">
                                <option value="DIARIO">DIARIO</option>
                                <option value="MENSUAL">MENSUAL</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase">Descripción / Concepto</label>
                            <input type="text" wire:model="descripcion" placeholder="Ej: Pago de Luz o Pasajes"
                                class="w-full mt-1 bg-gray-50 border-indigo-400 rounded-md text-sm focus:ring-0">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase">Monto (S/)</label>
                            <input type="number" step="0.01" wire:model="monto"
                                class="w-full mt-1 bg-gray-50 border-indigo-400 rounded-md text-sm font-bold text-indigo-700 focus:ring-0">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase">Pago</label>
                            <select wire:model="metodo_pago" class="w-full mt-1 bg-gray-50 border-indigo-400 rounded-md text-sm focus:ring-0">
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="TRANSFERENCIA">TRANSF.</option>
                                <option value="YAPE/PLIN">YAPE/PLIN</option>
                            </select>
                        </div>
                        <div class="md:col-span-1 flex items-end">
                            <x-button wire:loading.attr="disabled" wire:target="guardarGasto"
                                class="w-full justify-center bg-orange-500 hover:bg-orange-600 text-xs py-2 disabled:opacity-50 shadow-sm font-bold">
                                <span wire:loading.remove wire:target="guardarGasto text-white">REGISTRAR</span>
                                <span wire:loading wire:target="guardarGasto"><i class="fas fa-spinner fa-spin"></i></span>
                            </x-button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Tabla gastos operativos diarios -->
            <div class="space-y-4">
                <div class="flex justify-between items-center px-2">
                    <h3 class="font-bold text-gray-600 uppercase text-sm"><i class="fas fa-tools mr-2 text-orange-500"></i>Gastos Operativos (Diarios)</h3>
                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-bold text-xs border border-orange-200">
                        Total: S/ {{ number_format($gastosDiarios->sum('monto'), 2) }}
                    </span>
                </div>
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-[10px] font-semibold text-gray-600 uppercase">Fecha</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-[10px] font-semibold text-gray-600 uppercase">Concepto</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-[10px] font-semibold text-gray-600 uppercase">Monto</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($gastosDiarios as $g)
                            <tr class="text-sm hover:bg-gray-50">
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $g->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium text-gray-700 uppercase text-[11px]">{{ $g->descripcion }}</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900">S/ {{ number_format($g->monto, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button wire:click="eliminarGasto({{ $g->id }})"
                                            wire:confirm="¿Estás seguro de que deseas eliminar este registro?"
                                            class="text-red-400 hover:text-red-600 transition p-1">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="p-4 text-center text-xs text-gray-400 italic bg-gray-50">No hay gastos operativos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tabla gastos administrativos mensuales -->
            <div class="space-y-4">
                <div class="flex justify-between items-center px-2">
                    <h3 class="font-bold text-gray-600 uppercase text-sm"><i class="fas fa-building mr-2 text-indigo-500"></i>Gastos Administrativos (Mensuales)</h3>
                    <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold text-xs border border-indigo-200">
                        Total: S/ {{ number_format($gastosMensuales->sum('monto'), 2) }}
                    </span>
                </div>
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-[10px] font-semibold text-gray-600 uppercase">Fecha</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-[10px] font-semibold text-gray-600 uppercase">Concepto</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-[10px] font-semibold text-gray-600 uppercase">Monto</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($gastosMensuales as $g)
                            <tr class="text-sm hover:bg-gray-50">
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $g->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium text-gray-700 uppercase text-[11px]">{{ $g->descripcion }}</td>
                                <td class="px-4 py-3 text-right font-bold text-indigo-700">S/ {{ number_format($g->monto, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button wire:click="eliminarGasto({{ $g->id }})"
                                            wire:confirm="¿Estás seguro de que deseas eliminar este registro?"
                                            class="text-red-400 hover:text-red-600 transition p-1">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="p-4 text-center text-xs text-gray-400 italic bg-gray-50">No hay gastos administrativos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
