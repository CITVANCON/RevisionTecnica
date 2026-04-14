<div class="max-w-5xl m-auto bg-white rounded-lg shadow-md mb-6">
    {{-- Cabecera Estilo Institucional --}}
    <div class="flex items-center justify-between bg-accent py-4 px-6 rounded-t-lg">
        <span class="font-semibold text-white">Protocolo de Certificación de Hermeticidad</span>
        <div class="flex gap-2">
             <span class="text-sm text-white self-center">Apto: A | Observado: O | NO Apto: N.A</span>
        </div>
    </div>

    <div class="px-8 py-6">
        {{-- Tabla de Inspección --}}
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-secondary uppercase text-[10px] border-b">
                        <th class="p-3 w-48">Elemento</th>
                        <th class="p-3 text-center">Deformidad</th>
                        <th class="p-3 text-center">Fisura o Rotura</th>
                        <th class="p-3 text-center">Óxido</th>
                        <th class="p-3 text-center">Resequedad</th>
                        <th class="p-3 text-center">Lubricación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $filas = [
                            'tapa' => 'Tapa', 'compuerta' => 'Compuerta', 'tolva' => 'Tolva',
                            'sellos' => 'Sellos', 'bisagras' => 'Bisagras', 'pistones' => 'Pistones',
                            'mangueras' => 'Mangueras y/o Líneas', 'remaches' => 'Remaches'
                        ];
                        $criterios = [
                            'deformidad' => 'deformidad', 'fisura' => 'fisura', 
                            'oxido' => 'oxido', 'resequedad' => 'resequedad', 'lubricacion' => 'lubricacion'
                        ];
                    @endphp

                    @foreach ($filas as $key => $label)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="p-3 font-semibold text-secondary text-xs bg-gray-50/50">{{ $label }}</td>
                            @foreach ($criterios as $critKey => $critLabel)
                                <td class="p-1">
                                    <select wire:model="hermeticidad.{{ $key }}_{{ $critKey }}" 
                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-[11px] p-1
                                        @if($hermeticidad[$key.'_'.$critKey] == 'O') text-red-600 font-bold border-red-300 @endif">
                                        <option value="A">A</option>
                                        <option value="O">O</option>
                                        <option value="NA">N.A</option>
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Prueba de Esfuerzo --}}
            <div class="space-y-4">
                <h4 class="font-bold text-secondary border-b pb-1 uppercase text-xs flex items-center gap-2">
                    <i class="fas fa-stopwatch"></i> Prueba de Esfuerzo
                </h4>
                <div>
                    <x-label value="Tiempo de Prueba:" />
                    <x-input type="text" wire:model="hermeticidad.tiempo_prueba" class="w-full" placeholder="Ej: 5:00 minutos" />
                </div>
                <div class="flex gap-4 text-[10px] text-gray-500 italic mt-2">
                    <span class="bg-green-100 px-2 py-1 rounded"><strong>A:</strong> APTO</span>
                    <span class="bg-red-100 px-2 py-1 rounded"><strong>O:</strong> OBSERVADO</span>
                    <span class="bg-gray-100 px-2 py-1 rounded"><strong>NA:</strong> NO APLICA</span>
                </div>
            </div>

            {{-- Cuantificación --}}
            <div class="space-y-4">
                <h4 class="font-bold text-secondary border-b pb-1 uppercase text-xs flex items-center gap-2">
                    <i class="fas fa-calculator"></i> Cuantificación de Elementos
                </h4>
                
                <div class="grid grid-cols-5 gap-2 items-end">
                    <div class="text-[10px] font-bold text-gray-400 pb-2 uppercase text-center border-r">Campo</div>
                    <div class="text-[10px] font-bold text-secondary pb-2 uppercase text-center">Bisagras</div>
                    <div class="text-[10px] font-bold text-secondary pb-2 uppercase text-center">Pistones</div>
                    <div class="text-[10px] font-bold text-secondary pb-2 uppercase text-center">Líneas</div>
                    <div class="text-[10px] font-bold text-secondary pb-2 uppercase text-center">Remaches</div>

                    {{-- Fila Número --}}
                    <div class="text-xs font-bold text-secondary self-center border-r pr-2">Número</div>
                    <x-input type="number" wire:model="hermeticidad.cant_bisagras" class="w-full text-center p-1" />
                    <x-input type="number" wire:model="hermeticidad.cant_pistones" class="w-full text-center p-1" />
                    <x-input type="number" wire:model="hermeticidad.cant_mangueras" class="w-full text-center p-1" />
                    <x-input type="number" wire:model="hermeticidad.cant_remaches" class="w-full text-center p-1" />

                    {{-- Fila Faltantes --}}
                    <div class="text-xs font-bold text-red-600 self-center border-r pr-2">Faltantes</div>
                    <x-input type="number" wire:model="hermeticidad.faltas_bisagras" class="w-full text-center p-1 border-red-200 focus:ring-red-500" />
                    <x-input type="number" wire:model="hermeticidad.faltas_pistones" class="w-full text-center p-1 border-red-200 focus:ring-red-500" />
                    <x-input type="number" wire:model="hermeticidad.faltas_mangueras" class="w-full text-center p-1 border-red-200 focus:ring-red-500" />
                    <x-input type="number" wire:model="hermeticidad.faltas_remaches" class="w-full text-center p-1 border-red-200 focus:ring-red-500" />
                </div>
            </div>
        </div>
    </div>
</div>