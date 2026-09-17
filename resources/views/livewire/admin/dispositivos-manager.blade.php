<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <!-- Titulo y encabezado -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-laptop mr-2"></i>Autorizar Estación de Trabajo
                    </h2>
                    <span class="text-xs">Gestión de dispositivos autorizados para marcar asistencia</span>
                </div>
            </div>

            <!-- Formulario de Registro -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="nombre_estacion" value="{{ __('Nombre de la Estación') }}" />
                        {{--<x-input type="text" placeholder="Ej: PC-RECEPCION-01" wire:model.defer="nombre_estacion" class="w-full" />--}}
                        <x-input type="text" placeholder="Ej: PC-RECEPCION-01" wire:model="nombre_estacion" class="w-full" />
                        <x-input-error for="nombre_estacion" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="descripcion_ubicacion" value="{{ __('Ubicación / Taller') }}" />
                        {{--<x-input type="text" wire:model.defer="descripcion_ubicacion" class="w-full" />--}}
                        <x-input type="text" wire:model="descripcion_ubicacion" class="w-full" />
                        <x-input-error for="descripcion_ubicacion" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="autorizarEstaEstacion"
                        wire:loading.attr="disabled"
                        class="bg-orange-500 px-6 py-4 rounded-md text-white font-semibold tracking-wide cursor-pointer hover:bg-orange-600 transition">
                        <span wire:loading.remove wire:target="autorizarEstaEstacion">Registrar y Autorizar &nbsp;<i class="fas fa-shield-halved"></i></span>
                        <span wire:loading wire:target="autorizarEstaEstacion"><i class="fas fa-spinner fa-spin mr-1"></i> Procesando...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Dispositivos Autorizados -->
        @if ($dispositivos->count())
            <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estación
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                SO / Navegador
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Última IP
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dispositivos as $dis)
                            <tr class="border-b border-gray-200 bg-white text-sm" wire:key="dispositivo-{{ $dis->id }}">
                                <td class="px-5 py-4">
                                    <p class="text-gray-900 font-bold uppercase">{{ $dis->nombre_estacion }}</p>
                                    <p class="text-gray-400 text-xs">{{ $dis->descripcion_ubicacion }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-gray-700 text-xs font-medium">{{ $dis->sistema_operativo }} / {{ $dis->navegador }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-gray-700 text-xs font-mono">{{ $dis->ultima_ip }}</p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm
                                        {{ $dis->esta_activo
                                            ? 'bg-green-100 text-green-700 border border-green-200'
                                            : 'bg-red-100 text-red-700 border border-red-200' }}">
                                        {{ $dis->esta_activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button wire:click="desactivar({{ $dis->id }})"
                                        class="py-2 px-3 rounded-md bg-red-500 font-bold text-white hover:bg-red-600 transition text-xs">
                                        <i class="fas fa-ban mr-1"></i> Revocar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $dispositivos->links() }}
            </div>
        @else
            <div class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                No hay dispositivos autorizados registrados.
            </div>
        @endif
    </div>

    <script>
        window.addEventListener('save-device-token', event => {
            // En Livewire 3 los parámetros con nombre vienen en event.detail.token o event.detail[0].token
            const token = event.detail?.token ?? event.detail[0]?.token;
            
            if (token) {
                localStorage.setItem('citvancon_device_token', token);
                alert('¡Sello digital instalado en este navegador con éxito!');
                location.reload();
            } else {
                console.error('Token no recibido:', event.detail);
                alert('Error: No se pudo obtener el sello de autorización.');
            }
        });
    </script>
</div>
