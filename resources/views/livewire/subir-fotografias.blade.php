<div {{-- wire:init="loadSubirFotografias" --}} wire:loading.attr="disabled" wire:target="render">
    <div class="container mx-auto py-12">
        <x-table>
            @if (count($propuestas))
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal rounded-md">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Num Propuesta
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Placa
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($propuestas as $key => $item)
                                        <tr>
                                            <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                                                <div class="flex items-center">
                                                    <p class="text-gray-900 ">
                                                        {{ $item->num_propuesta }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                                                <div class="flex items-center">
                                                    <p class="text-gray-900 ">
                                                        {{ $item->vehiculo->vehiculo->placa }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 border-b border-gray-200 bg-white text-sm">
                                                <div class="flex justify-end">

                                                    <a wire:click="edit({{ $item->id }})"
                                                        class="py-2 px-3 text-center rounded-md bg-lime-300 font-bold text-white cursor-pointer hover:bg-lime-400">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a wire:click=""
                                                        class="py-2 px-3 text-center ml-2 rounded-md bg-indigo-300 font-bold text-white cursor-pointer hover:bg-indigo-400">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if ($propuestas->hasPages())
                    <div>
                        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-2 overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                <div class="px-5 py-5 bg-white border-t">
                                    {{ $propuestas->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                    No se encontro ningun registro.
                </div>
            @endif
        </x-table>
    </div>


    {{-- MODAL PARA SUBIR FOTOGRAFIAS AL MODELO InspeccionFoto CAPTURANDO EL ID DE PROPUESTA --}}
    <x-dialog-modal wire:model="editando" wire:loading.attr="disabled" wire:target="deleteFile">
        <x-slot name="title" class="font-bold">
            @if ($propuestaSeleccionada)
                <h1>Editar Propuesta # {{$propuestaSeleccionada->num_propuesta}} del vehiculo {{$propuestaSeleccionada->vehiculo->vehiculo->placa}}</h1>
            @endif

        </x-slot>

        <x-slot name="content">
            {{--
            <div class="mb-4">
                <x-label value="Fotos:" />
                <x-input wire:model="fotosnuevas" type="file" accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff"
                    multiple id="fotos-{{ $identificador }}" class="w-full" />
                <x-input-error for="fotosnuevas" />
                <x-input-error for="fotosnuevas.*" />
            </div>
            --}}
            <div class="mb-4">
                <x-label value="Foto Izquierda:" />
                <x-input type="file" wire:model="fotoIzquierda" accept="image/*" />
                <x-input-error for="fotoIzquierda" />
            </div>

            <div class="mb-4">
                <x-label value="Foto Centro:" />
                <x-input type="file" wire:model="fotoCentro" accept="image/*" />
                <x-input-error for="fotoCentro" />
            </div>

            <div class="mb-4">
                <x-label value="Foto Derecha:" />
                <x-input type="file" wire:model="fotoDerecha" accept="image/*" />
                <x-input-error for="fotoDerecha" />
            </div>


            <!-- Procesar fotografias -->
            <div wire:loading wire:target="fotoIzquierda,fotoCentro,fotoDerecha,deleteFileUpload"
                class="my-4 w-full px-6 py-4 text-center font-bold bg-indigo-200 rounded-md">
                Procesando sus imagenes, espere un momento...
            </div>
            <!-- Galeria de fotografias -->
            <h1 class="pt-2  font-semibold sm:text-lg text-gray-900">
                Galeria de fotos:
            </h1>
            <hr />
            @if (count($files) || $fotoIzquierda || $fotoCentro || $fotoDerecha)
                <section class="mt-4 overflow-hidden border-dotted border-2 text-gray-700 " {{-- wire:model="fotoIzquierda,fotoCentro,fotoDerecha" --}}>
                    <div class="container px-5 py-2 mx-auto lg:px-32">
                        <div class="flex flex-wrap -m-1 md:-m-2">
                            {{-- Imágenes guardadas previamente --}}
                            @foreach ($files as $fil)
                                <div class="flex flex-wrap w-1/3 ">
                                    <div class="w-full p-1 items-center justify-center">
                                        <img alt="gallery"
                                            class="mx-auto flex object-cover object-center w-36 h-36 rounded-lg"
                                            src="{{ Storage::url($fil->ruta) }}">
                                        <a class="flex" wire:click="deleteFile({{ $fil->id }})">
                                            <i class="fas fa-trash mt-1 mx-auto hover:text-indigo-400"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Foto Izquierda seleccionada --}}
                            @if ($fotoIzquierda)
                                <div class="flex flex-wrap w-1/3">
                                    <div class="w-full p-1 items-center justify-center">
                                        <img alt="preview"
                                            class="mx-auto object-cover w-36 h-36 rounded-lg border-2 border-lime-500"
                                            src="{{ $fotoIzquierda->temporaryUrl() }}">
                                        <a class="flex" wire:click=""><i
                                                class="fas fa-trash mt-1 mx-auto hover:text-orange-500"></i></a>
                                    </div>
                                </div>
                            @endif
                            {{-- Foto Centro seleccionada --}}
                            @if ($fotoCentro)
                                <div class="flex flex-wrap w-1/3">
                                    <div class="w-full p-1 items-center justify-center">
                                        <img alt="preview"
                                            class="mx-auto object-cover w-36 h-36 rounded-lg border-2 border-lime-500"
                                            src="{{ $fotoCentro->temporaryUrl() }}">
                                        <a class="flex" wire:click=""><i
                                                class="fas fa-trash mt-1 mx-auto hover:text-orange-500"></i></a>
                                    </div>
                                </div>
                            @endif
                            {{-- Foto Derecha seleccionada --}}
                            @if ($fotoDerecha)
                                <div class="flex flex-wrap w-1/3">
                                    <div class="w-full p-1 items-center justify-center">
                                        <img alt="preview"
                                            class="mx-auto object-cover w-36 h-36 rounded-lg border-2 border-lime-500"
                                            src="{{ $fotoDerecha->temporaryUrl() }}">
                                        <a class="flex" wire:click=""><i
                                                class="fas fa-trash mt-1 mx-auto hover:text-orange-500"></i></a>
                                    </div>
                                </div>
                            @endif
                            {{--
                            @if (count($fotoIzquierda))
                                @foreach ($fotoIzquierda as $key => $otro)
                                    <div class="flex flex-wrap w-1/3 ">
                                        <div class="w-full p-1 items-center justify-center">
                                            <img alt="gallery"
                                                class="mx-auto flex object-cover object-center w-36 h-36 rounded-lg shadow-lg border-2 border-lime-500 hover:shadow-lime-500/50 "
                                                src="{{ $otro->temporaryUrl() }}">
                                            <a class="flex" wire:click="deleteFileUpload({{ $key }})"><i
                                                    class="fas fa-trash mt-1 mx-auto hover:text-indigo-400"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            --}}
                        </div>
                    </div>
                </section>
            @else
                <section class="h-full overflow-auto p-8 w-full h-full flex flex-col">
                    <ul id="" class="flex flex-1 flex-wrap -m-1">
                        <li id=""
                            class="h-full w-full text-center flex flex-col items-center justify-center items-center">
                            <img class="mx-auto w-32"
                                src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png"
                                alt="no data" />
                            <span class="text-small text-gray-500">Aun no seleccionaste ningún archivo</span>
                        </li>
                    </ul>
                </section>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('editando',false)" class="mx-2">
                Cancelar
            </x-secondary-button>
            <x-button wire:click="guardarFotos" wire:loading.attr="disabled" wire:target="guardarFotos">
                Actualizar
            </x-button>
        </x-slot>

    </x-dialog-modal>

</div>
