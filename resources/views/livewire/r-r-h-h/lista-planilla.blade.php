<div class="container mx-auto py-12">
    <div class="bg-gray-200 rounded-lg shadow-sm p-4">
        <div class="items-center pb-6 md:block sm:block">
            <div class="px-2 w-64 mb-4 md:w-full">
                <h2 class="text-gray-600 font-semibold text-2xl">
                    <i class="fas fa-file-signature mr-2"></i>Gestión de Planillas
                </h2>
                <span class="text-xs">Control de planillas de remuneraciones</span>
            </div>
            <div class="w-full items-center md:flex md:justify-between space-x-2">
                <div class="flex items-center space-x-2">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md">
                        <span>Mostrar</span>
                        <select wire:model.live="cant" class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                        <span>Entradas</span>
                    </div>

                    <div class="flex bg-gray-50 items-center p-2 rounded-md">
                        <input type="date" wire:model.live="periodoSeleccionado" class="bg-gray-50 mx-2 border-indigo-500 rounded-md outline-none block">
                    </div>
                </div>

                <div class="flex bg-gray-50 items-center lg:w-2/6 p-2 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    <input class="bg-gray-50 outline-none block rounded-md border-none focus:ring-0 w-full" type="text" wire:model.live="search" placeholder="Buscar trabajador o DNI...">
                </div>

                    <button wire:click="$dispatch('abrir-modal-planilla')" class="bg-orange-500 px-6 py-3 rounded-md text-white font-semibold hover:bg-orange-600 transition flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> Crear Planilla
                    </button>
            </div>
        </div>

        @if ($planillas->count())
            <div class="overflow-x-auto bg-white rounded-lg">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-left text-xs font-semibold uppercase text-gray-600 border-b">
                            <th class="px-4 py-3 text-center">FECHA PERIODO</th>
                            <th class="px-4 py-3">TRABAJADOR</th>
                            <th class="px-4 py-3 text-right">SUELDO BASE</th>
                            <th class="px-4 py-3 text-right text-green-600">EXTRAS</th>
                            <th class="px-4 py-3 text-right text-red-600">DSCTOS.</th>
                            <th class="px-4 py-3 text-right font-bold text-blue-700">TOTAL NETO</th>
                            <th class="px-4 py-3 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-200">
                        @foreach($planillas as $item)
                            <tr>
                                <td class="px-4 py-3 text-center font-bold text-gray-600">
                                    {{ $item->periodo->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $item->contrato->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->contrato->cargo }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">S/ {{ number_format($item->sueldo_base, 2) }}</td>
                                <td class="px-4 py-3 text-right text-green-600">
                                    + S/ {{ number_format($item->horas_extras + $item->movilidad + $item->otros_ingresos + $item->asignacion_familiar, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-red-600">
                                    - S/ {{ number_format($item->otros_descuentos, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right font-black text-blue-800 text-base">
                                    S/ {{ number_format($item->total_pagado, 2) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center space-x-3">
                                        <button class="text-blue-500 hover:text-blue-700"><i class="fas fa-file-pdf"></i></button>
                                        <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $planillas->links() }}
            </div>
        @else
            <div class="px-6 py-4 text-center font-bold bg-accent rounded-md text-white shadow-inner">
                <i class="fas fa-info-circle mr-2"></i> No se encontraron registros para el periodo seleccionado.
            </div>
        @endif
    </div>

    @livewire('r-r-h-h.crear-planilla')
</div>
