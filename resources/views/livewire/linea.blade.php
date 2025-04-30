<div class="block justify-center pt-4 pb-4 px-6">
    <h1 class="text-center text-xl my-4 font-bold text-secondary">MOSTRAR INSPECCION LINEA</h1>

    <div class="overflow-x-auto rounded-xl shadow">
        <table class="min-w-full bg-white border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-accent text-white">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Fecha</th>
                    <th class="px-4 py-2 border">N° Propuesta</th>
                    <th class="px-4 py-2 border">Placa</th>
                    <th class="px-4 py-2 border">Linea</th>
                    <th class="px-4 py-2 border">Orden</th>
                    <th class="px-4 py-2 border">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($propuestas as $index => $propuesta)
                    <tr class="text-center">
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">
                            {{ \Carbon\Carbon::parse($propuesta->fecha_creacion)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border">{{ $propuesta->num_propuesta }}</td>
                        <td class="px-4 py-2 border">{{ $propuesta->vehiculo->vehiculo->placa }}</td>
                        <td class="px-4 py-2 border">L1</td>
                        <td class="px-4 py-2 border">U</td>
                        <td class="px-4 py-2 border">
                            {{-- 
                            <button wire:click="seleccionarPropuesta({{ $propuesta->id }})"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded">
                                <i class="fas fa-edit"></i>
                            </button>
                            --}}
                            <a href="{{ route('editar-lineainspeccion', ['idPropuesta' => $propuesta->id]) }}"
                                class="py-2 px-2 text-center items-center rounded-md bg-orange-500 font-bold text-white cursor-pointer hover:bg-orange-600 hover:animate-pulse">
                                <i class="fas fa-folder-plus"></i>
                                <span
                                    class="group-hover:opacity-100 transition-opacity bg-gray-800 px-1 text-sm text-gray-100 rounded-md absolute left-1/2-translate-x-1/2 translate-y-full opacity-0 m-4 mx-auto z-100">
                                    Vaucher
                                </span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 border text-center text-gray-500">No hay registros
                            disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
