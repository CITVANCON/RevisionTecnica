<div class="container mx-auto py-12">
    <div class="bg-gray-200 p-8 rounded-xl w-full">
        <div class="pb-6">
            <!-- Titulo y encabezado -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div class="px-2">
                    <h2 class="text-gray-600 font-semibold text-2xl">
                        <i class="fas fa-desktop mr-2"></i>Monitor de Asistencia en Tiempo Real
                    </h2>
                    <span class="text-xs">Control y seguimiento de asistencia del personal</span>
                </div>
                <div class="mt-4 md:mt-0 px-2">
                    <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                        <span class="relative flex h-2 w-2 mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="text-xs font-semibold text-green-600">Sistema Sincronizado</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRÁFICOS Y RESUMEN -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- GRÁFICO RESUMEN -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 lg:col-span-1">
                <h3 class="text-xs font-semibold text-gray-600 uppercase mb-4 tracking-wider flex items-center justify-between">
                    <span><i class="fas fa-chart-pie mr-1 text-indigo-500"></i> Resumen de Hoy</span>
                    @if (count($listaAusentes) > 0)
                        <button wire:click="$set('mostrarModalAusentes', true)" type="button"
                            class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full text-[10px] font-bold normal-case border border-red-100">
                            Ver {{ count($listaAusentes) }} Sin Marcar →
                        </button>
                    @endif
                </h3>
                <div wire:ignore class="relative mb-4">
                    <div style="height: 200px;">
                        <canvas id="chartAsistencia"></canvas>
                    </div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-4">
                        <span class="text-2xl font-black text-gray-800">{{ $dataGrafico['asistencias'] }}</span>
                        <span class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Presentes</span>
                    </div>
                </div>
            </div>

            <!-- CUMPLIMIENTO POR ROL -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 lg:col-span-2">
                <h3 class="text-xs font-semibold text-gray-600 uppercase mb-4 tracking-wider flex items-center">
                    <i class="fas fa-users mr-1 text-indigo-500"></i> Cumplimiento por Rol
                </h3>
                <div class="space-y-4 overflow-y-auto h-[220px] pr-2">
                    @foreach ($asistenciaPorRol as $rol)
                        <div>
                            <div class="flex justify-between text-[10px] font-bold uppercase mb-1.5">
                                <span class="text-gray-600">{{ $rol['nombre'] }}</span>
                                <span class="text-indigo-600">{{ $rol['porcentaje'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="{{ $rol['color'] }} h-2 rounded-full transition-all duration-1000"
                                    style="width: {{ $rol['porcentaje'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- TABLA DETALLE DE MARCACIONES + ACTIVIDAD RECIENTE -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- TABLA DETALLE -->
            <div class="lg:col-span-2">
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100">
                    <div class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 flex items-center justify-between">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-list-check mr-1 text-indigo-500"></i> Detalle de Marcaciones
                        </h3>
                        <div class="flex bg-gray-50 items-center p-2 rounded-md shadow-sm border border-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                            <input class="bg-gray-50 outline-none block rounded-md w-full border-none focus:ring-0 text-sm"
                                type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar colaborador...">
                        </div>
                    </div>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Colaborador
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Entrada
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Salida
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tardanza
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Horario
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asistencias as $asistencia)
                                <tr class="border-b border-gray-200 bg-white text-sm" wire:key="asistencia-{{ $asistencia->id }}">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ substr($asistencia->usuario->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-gray-900 font-bold uppercase">{{ $asistencia->usuario->name }}</p>
                                                <p class="text-gray-400 text-[10px] font-semibold">{{ $asistencia->usuario->dni }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold text-gray-600">
                                            {{ $asistencia->hora_entrada ? $asistencia->hora_entrada->format('H:i') : '--:--' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold text-gray-600">
                                            {{ $asistencia->hora_salida ? $asistencia->hora_salida->format('H:i') : '--:--' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm
                                            {{ $asistencia->estado == 'Puntual'
                                                ? 'bg-green-100 text-green-700 border border-green-200'
                                                : 'bg-red-100 text-red-700 border border-red-200' }}">
                                            {{ $asistencia->estado }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right text-xs font-bold {{ $asistencia->minutos_tardanza > 0 ? 'text-red-600' : 'text-gray-300' }}">
                                        {{ $asistencia->minutos_tardanza }} <span class="text-[10px]">min</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @php
                                            $asignacion = $asistencia->usuario->horariosAsignados->first();
                                            $detalleHoy = $asignacion?->horario?->detalles?->first();
                                        @endphp
                                        @if ($detalleHoy)
                                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg border border-indigo-100 text-[11px]">
                                                {{ Carbon\Carbon::parse($detalleHoy->hora_entrada)->format('H:i') }}
                                                -
                                                {{ Carbon\Carbon::parse($detalleHoy->hora_salida)->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="bg-yellow-50 text-yellow-700 px-3 py-1 rounded-lg border border-yellow-100 text-[10px] font-bold uppercase">
                                                Sin horario
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ACTIVIDAD RECIENTE -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 h-full">
                    <h3 class="text-xs font-semibold text-gray-600 uppercase mb-4 tracking-wider flex items-center">
                        <i class="fas fa-clock-rotate-left mr-1 text-indigo-500"></i> Actividad Reciente
                    </h3>
                    <div class="space-y-4 relative">
                        <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                        @forelse($actividadReciente as $item)
                            @php
                                $esSalida = !is_null($item->hora_salida);
                            @endphp
                            <div class="flex items-start gap-4 relative z-10">
                                <div class="w-4 h-4 rounded-full border-4 {{ $esSalida ? 'border-orange-200 bg-orange-500' : 'border-indigo-100 bg-indigo-600' }}">
                                </div>
                                <div class="flex-1 -mt-1">
                                    <p class="text-[11px] font-bold text-gray-800 uppercase leading-none">
                                        {{ $item->usuario->name }}</p>
                                    <p class="text-[9px] text-gray-400 mt-1 uppercase">
                                        {{ $esSalida ? 'Marcó Salida' : 'Marcó Entrada' }} •
                                        <span class="font-bold text-gray-600">
                                            {{ $esSalida ? $item->hora_salida->format('H:i A') : $item->hora_entrada->format('H:i A') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-gray-400 py-10 italic">Sin actividad reciente</p>
                        @endforelse
                    </div>

                    <!-- Resumen Crítico -->
                    <div class="mt-6 p-4 bg-indigo-600 rounded-lg text-white">
                        <p class="text-[9px] font-black uppercase opacity-50 tracking-tighter">Resumen Crítico</p>
                        <p class="text-sm font-bold mt-1 text-white">{{ $stats['tardanzas'] }} Tardanzas detectadas</p>
                        <div class="mt-3 w-full bg-white/10 h-1 rounded-full">
                            <div class="bg-white h-1 rounded-full"
                                style="width: {{ $stats['total'] > 0 ? ($stats['tardanzas'] / $stats['total']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver ausentes -->
    <x-dialog-modal wire:model.live="mostrarModalAusentes" maxWidth="md">
        <x-slot name="title">
            {{ __('Personal No Registrado') }}
        </x-slot>
        <x-slot name="content">
            <div class="space-y-2">
                @foreach ($listaAusentes as $ausente)
                    <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                        <div class="w-9 h-9 bg-red-100 rounded-full flex items-center justify-center text-red-600 text-xs font-bold">
                            {{ strtoupper(substr($ausente->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800 uppercase">{{ $ausente->name }}</p>
                            <p class="text-[10px] text-gray-400 font-semibold">DNI: {{ $ausente->dni }}</p>
                        </div>
                        <span class="text-[10px] bg-red-100 text-red-600 px-2.5 py-1 rounded-full font-bold border border-red-200">
                            Falta
                        </span>
                    </div>
                @endforeach
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('mostrarModalAusentes', false)" wire:loading.attr="disabled">
                {{ __('Cerrar') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var asistenciaChart;

        function initChart() {
            const ctx = document.getElementById('chartAsistencia').getContext('2d');
            asistenciaChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Presentes', 'Ausentes'],
                    datasets: [{
                        data: [{{ $dataGrafico['asistencias'] }}, {{ $dataGrafico['ausencias'] }}],
                        backgroundColor: ['#4f46e5', '#fee2e2'],
                        hoverOffset: 4,
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        document.addEventListener('livewire:load', () => initChart());
        window.addEventListener('contentChanged', () => {
            asistenciaChart.data.datasets[0].data = [@this.dataGrafico.asistencias, @this.dataGrafico.ausencias];
            asistenciaChart.update();
        });
    </script>
</div>
