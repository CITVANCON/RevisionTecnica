<div class="max-w-5xl m-auto bg-white rounded-lg shadow-md mb-6">
    {{-- Cabecera Estilo Institucional --}}
    <div class="flex items-center justify-between bg-accent py-4 px-6 rounded-t-lg">
        <span class="font-semibold text-white uppercase text-sm tracking-wider">Protocolo de Prueba de Opacidad</span>
        <div class="flex gap-2">
             <span class="text-sm text-white self-center">Límite Permitido: {{ $opacidad['limite_permitido'] }} k m⁻¹</span>
        </div>
    </div>

    <div class="px-8 py-6">
        {{-- Tabla de Inspección de Ciclos --}}
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-secondary uppercase text-[10px] border-b">
                        <th class="p-3 w-32">Medicion</th>
                        <th class="p-3 text-center">Valor (k m⁻¹)</th>
                        <th class="p-3 text-center">Temperatura (°C)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach([1, 2, 3, 4] as $i)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="p-3 font-semibold text-secondary text-xs bg-gray-50/50">CICLO N° 0{{ $i }}</td>
                            <td class="p-2">
                                <x-input type="number" step="0.001" 
                                    wire:model.live="opacidad.ciclo{{$i}}_k" 
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-center text-[11px] p-1" />
                            </td>
                            <td class="p-2">
                                <x-input type="number" step="0.1" 
                                    wire:model.live="opacidad.ciclo{{$i}}_t" 
                                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-center text-[11px] p-1" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50/80 font-bold border-t-2">
                        <td class="p-3 text-right text-secondary uppercase text-[10px]">Valor K (Media Aritmetica):</td>
                        <td class="p-3 text-center">
                            <span class="text-sm {{ $opacidad['promedio_k'] <= $opacidad['limite_permitido'] ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($opacidad['promedio_k'], 2) }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            @if($opacidad['promedio_k'] <= $opacidad['limite_permitido'])
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-[10px] uppercase">Apto</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded text-[10px] uppercase">No Apto</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Información Adicional Estilo Hermeticidad --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <h4 class="font-bold text-secondary border-b pb-1 uppercase text-xs flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Notas de la Prueba
                </h4>
                <p class="text-[11px] text-gray-500 italic">
                    La opacidad se mide en el coeficiente de absorción de luz (k). 
                    El vehículo debe mantener una temperatura de operación óptima durante los ciclos.
                </p>
                <div class="flex gap-4 text-[10px] text-gray-500 italic mt-2">
                    <span class="bg-blue-100 px-2 py-1 rounded text-blue-700"><strong>K:</strong> Coeficiente de Extinción</span>
                    <span class="bg-orange-100 px-2 py-1 rounded text-orange-700"><strong>T:</strong> Temperatura de Aceite/Motor</span>
                </div>
            </div>

            {{-- Resumen de Resultado --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 flex flex-col justify-center items-center">
                <span class="text-[10px] uppercase font-bold text-gray-400 mb-1">Resultado de Opacidad</span>
                @if($opacidad['promedio_k'] <= $opacidad['limite_permitido'])
                    <div class="text-green-600 font-bold text-xl flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> APTO
                    </div>
                @else
                    <div class="text-red-600 font-bold text-xl flex items-center gap-2">
                        <i class="fas fa-times-circle"></i> NO APTO
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>