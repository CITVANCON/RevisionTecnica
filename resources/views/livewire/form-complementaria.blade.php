<div class="py-2">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-2">
            @if ($datosComplementarios)
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Datos Complementarios</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <!-- Columna 1: Imagen -->
                    <div class="flex items-center justify-center border border-gray-300 rounded h-60 bg-gray-100">
                        <span class="text-gray-400">📷 Aquí irá la imagen (subida más adelante)</span>
                    </div>

                    <!-- Columna 2: Datos en x-inputs -->
                    <div class="col-span-2 space-y-4">
                        <div>
                            <x-label value="Tipo Complementaria"/>
                            <x-input type="text" class="mt-1 w-full" disabled
                                value="{{ $datosComplementarios->complementaria->descripcionTipo }}" />
                        </div>
                        <div>
                            <x-label value="Denominación"/>
                            <x-input type="text" class="mt-1 w-full" disabled
                                value="{{ $datosComplementarios->complementaria->denominacionCertificado }}"/>
                        </div>
                        <div>
                            <x-label value="Clase Autorización"/>
                            <x-input type="text" class="mt-1 w-full" disabled 
                                value="{{ $datosComplementarios->complementaria->descripcionAmbito }}"/>
                        </div>
                        <div>
                            <x-label value="Leyenda 1"/>
                            <x-input type="text" class="mt-1 w-full" disabled
                                value="{{ $datosComplementarios->complementaria->descripcionResiduoPeligroso }}"/>
                        </div>
                        <div>
                            <x-label value="Modalidad/Ambito"/>
                            <x-input type="text" class="mt-1 w-full" disabled 
                                value="{{ $datosComplementarios->complementaria->leyenda1 }}"/>
                        </div>
                        <div>
                            <x-label value="Leyenda 2"/>
                            <x-input type="text" class="mt-1 w-full" disabled
                                value="{{ $datosComplementarios->complementaria->leyenda2 }}"/>
                        </div>
                        <div>
                            <x-label value="Observación"/>
                            <textarea rows="2" class="mt-1 block w-full border-gray-300 rounded shadow-sm" readonly>
                                {{ $datosComplementarios->observacion }}</textarea>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-gray-500 italic">No hay datos complementarios registrados para esta propuesta.</p>
            @endif
        </div>
    </div>
</div>
