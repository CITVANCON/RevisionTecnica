<div class="py-2">
    <div class="max-w-7xl mx-auto text-center sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-2">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Fotos Reglamentarias</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 text-center">
                {{-- Columna Izquierda --}}
                <div class="flex flex-col items-center justify-center border border-gray-300 rounded w-60 h-52 bg-gray-100 mx-auto">
                    @if ($fotoIzquierda)
                        <img alt="foto izquierda" class="object-cover w-36 h-36 rounded-lg"
                            src="{{ Storage::url($fotoIzquierda->ruta) }}">
                        <a class="mt-2 hover:text-indigo-400 cursor-pointer"
                            wire:click="deleteFile({{ $fotoIzquierda->id }})">
                            <i class="fas fa-trash"></i>
                        </a>
                    @else
                        <span class="text-gray-400">📷 Aquí irá la imagen (Izquierda)</span>
                    @endif
                </div>

                {{-- Columna Centro --}}
                <div class="flex flex-col items-center justify-center border border-gray-300 rounded w-60 h-52 bg-gray-100 mx-auto">
                    @if ($fotoCentro)
                        <img alt="foto centro" class="object-cover w-36 h-36 rounded-lg"
                            src="{{ Storage::url($fotoCentro->ruta) }}">
                        <a class="mt-2 hover:text-indigo-400 cursor-pointer"
                            wire:click="deleteFile({{ $fotoCentro->id }})">
                            <i class="fas fa-trash"></i>
                        </a>
                    @else
                        <span class="text-gray-400">📷 Aquí irá la imagen (Centro)</span>
                    @endif
                </div>

                {{-- Columna Derecha --}}
                <div class="flex flex-col items-center justify-center border border-gray-300 rounded w-60 h-52 bg-gray-100 mx-auto">
                    @if ($fotoDerecha)
                        <img alt="foto derecha" class="object-cover w-36 h-36 rounded-lg"
                            src="{{ Storage::url($fotoDerecha->ruta) }}">
                        <a class="mt-2 hover:text-indigo-400 cursor-pointer"
                            wire:click="deleteFile({{ $fotoDerecha->id }})">
                            <i class="fas fa-trash"></i>
                        </a>
                    @else
                        <span class="text-gray-400">📷 Aquí irá la imagen (Derecha)</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
