<div class="container mx-auto py-12 px-2">
    <div class="bg-gray-200 rounded-lg shadow-sm p-4">
        <div class="items-center pb-6 md:block sm:block">
            <div class="px-2 w-64 mb-4 md:w-full">
                <h2 class="text-gray-600 font-semibold text-2xl">
                    <i class="fas fa-file-signature mr-2"></i>Gestión de Planillas
                </h2>
                <span class="text-xs">Control de planillas de remuneraciones</span>
            </div>

            <div class="w-full items-center md:flex md:justify-between">
                <div class="flex items-center space-x-2">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md mb-4">
                        <span class="text-sm">Mostrar</span>
                        <select wire:model.live="cant" class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none text-sm">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="flex bg-gray-50 items-center p-2 rounded-md mb-4">
                        <select wire:model.live="periodoSeleccionado" class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none text-sm">
                            <option value="">-- SELECCIONAR --</option>
                            @foreach($listaPeriodos as $p)
                                <option value="{{ $p->periodo->format('Y-m-d') }}">
                                    Periodo: {{ $p->periodo->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex bg-gray-50 items-center lg:w-2/6 p-2 rounded-md mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    <input class="bg-gray-50 outline-none block rounded-md border-none focus:ring-0 w-full text-sm" type="text" wire:model.live="search" placeholder="Buscar trabajador...">
                </div>

                <div class="mb-4">
                    <button wire:click="$dispatch('abrir-modal-planilla')" class="bg-orange-500 px-6 py-3 rounded-md text-white font-semibold hover:bg-orange-600 transition flex items-center shadow-sm">
                        <i class="fas fa-plus-circle mr-2"></i> Crear Planilla
                    </button>
                </div>

            </div>
        </div>

        @if ($planillas->count())
            <div class="overflow-x-auto bg-white rounded-lg border border-gray-300">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-left text-xs font-bold uppercase text-gray-500 border-b whitespace-nowrap">
                            <th class="px-3 py-4">TRABAJADOR</th>
                            <th class="px-2 py-4 text-center">CELULAR</th>
                            <th class="px-2 py-4 text-center">INGRESO</th>
                            <th class="px-2 py-4 text-right">BASE</th> <th class="px-2 py-4 text-right text-green-600">EXTRAS</th>
                            <th class="px-2 py-4 text-right text-red-600">DSCTOS.</th>
                            <th class="px-2 py-4">PLANILLA</th>
                            <th class="px-2 py-4">OBSERVACIÓN</th>
                            <th class="px-2 py-4 text-center">N° CUENTA</th>
                            <th class="px-3 py-4 text-right text-blue-700">TOTAL NETO</th>
                            <th class="px-2 py-4 text-center">PAGADO</th>
                            <th class="px-3 py-4 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($planillas as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3">
                                    <div class="text-sm font-bold text-gray-900 leading-tight">{{ $item->contrato->user->name }}</div>
                                    <div class="text-[11px] uppercase text-gray-400 font-semibold tracking-tighter">{{ $item->contrato->cargo }}</div>
                                </td>
                                <td class="px-2 py-3 text-center text-xs text-gray-500">
                                    {{ $item->contrato->user->celular ?? '-' }}
                                </td>
                                <td class="px-2 py-3 text-center text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->contrato->fecha_ingreso)->format('d/m/y') }}
                                </td>
                                <td class="px-2 py-3 text-right text-xs whitespace-nowrap">
                                    S/ {{ number_format($item->sueldo_base, 2) }}
                                </td>
                                <td class="px-2 py-3 text-right text-xs text-green-600 whitespace-nowrap">
                                    +{{ number_format($item->horas_extras + $item->movilidad + $item->otros_ingresos + $item->asignacion_familiar, 2) }}
                                </td>
                                <td class="px-2 py-3 text-right text-xs text-red-600 whitespace-nowrap">
                                    -{{ number_format($item->otros_descuentos, 2) }}
                                </td>
                                <td class="px-2 py-3">
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded border text-[9px] font-bold uppercase block" title="{{ $item->planilla }}">
                                        {{ $item->planilla ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3">
                                    <span class="text-gray-400 italic text-xs truncate " title="{{ $item->observacion }}">
                                        {{ $item->observacion ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-center font-mono text-[12px] text-gray-600">
                                    {{ $item->contrato->user->numero_cuenta ?? '-' }}
                                </td>
                                <td class="px-3 py-3 text-right font-black text-blue-800 text-sm whitespace-nowrap">
                                    S/ {{ number_format($item->total_pagado, 2) }}
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <x-checkbox
                                        wire:click="togglePago({{ $item->id }})"
                                        :checked="$item->estado_pago"
                                        class="w-4 h-4 text-indigo-600"
                                    />
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <div class="flex justify-center space-x-1">
                                        <button wire:click="$dispatch('abrir-modal-archivos', { id: {{ $item->id }} })"
                                            class="py-1 px-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition shadow-sm">
                                            <i class="fa-solid fa-folder text-[11px]"></i>
                                        </button>
                                        {{--
                                        <button class="py-1 px-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition shadow-sm">
                                            <i class="fa-solid fa-pencil text-[11px]"></i>
                                        </button>
                                        --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 border-t-2 border-gray-300">
                            <td colspan="9" class="px-4 py-4 text-right">
                                <span class="text-gray-500 font-bold uppercase text-xs">Total Periodo:</span>
                            </td>
                            <td colspan="3" class="px-4 py-4 text-left">
                                <span class="text-xl font-black text-blue-900 border-b-4 border-blue-200">
                                    S/ {{ number_format($totalGeneral, 2) }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-4">
                {{ $planillas->links() }}
            </div>
        @else
            <div class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                No hay contratos registrados con el criterio.
            </div>
        @endif
    </div>

    @livewire('r-r-h-h.crear-planilla')
    @livewire('r-r-h-h.planilla-archivos')
</div>
