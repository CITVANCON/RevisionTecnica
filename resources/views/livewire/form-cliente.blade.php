<div>
    @switch($estado)
        @case('nuevo')
            <x-form-cliente-habilitado :$tipo_cliente :$tipo_documento />
        @break

        @case('cargado')
            <x-form-cliente-deshabilitado :$tipo_cliente :$m_tipo_documento :$m_numero_documento :$m_nombre_razon_social :$m_direccion :$m_telefono :$m_email />
        @break

        @case('editando')
            <x-form-cliente-actualizar :$tipo_cliente :$tipo_documento :$numero_documento />
        @break
    @endswitch

    <x-dialog-modal wire:model="busqueda">
        <x-slot name="title">
            <h1 class="font-medium">Clientes Encontrados</h1>
        </x-slot>
        <x-slot name="content">
            @if ($clientes)
                <p class="text-indigo-900">Se encontraron <span
                        class="px-2 bg-indigo-400 rounded-full">{{ $clientes->count() }}</span> registros</p>
                <div class="my-5">
                    @foreach ($clientes as $cli)
                        <div class="flex justify-between items-center border-b py-3 px-2 hover:bg-slate-100 transition">
                            <div class="inline-flex items-center space-x-2">
                                <i class="fas fa-user text-indigo-500"></i>
                                <div class="font-bold">{{ $cli->numero_documento }}</div>
                                <div>{{ $cli->nombre_razon_social }}</div>
                            </div>
                            <i wire:click="seleccionaCliente({{ $cli->id }})"
                                class="fas fa-plus-circle fa-lg cursor-pointer text-indigo-600"></i>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('busqueda',false)">Cancelar</x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
