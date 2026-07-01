    @props(['id', 'type' => 'line', 'height' => 350, 'title' => '', 'data' => []])

    @php
        // Procesamos los datos según el tipo de gráfico
        $categories = [];
        $series = [];

        if ($type === 'line' || $type === 'bar') {
            // Formato para gráficos lineales/barras: requiere mapear categorías y empaquetar en 'data'
            $categories = collect($data)->pluck('fecha')->toArray();
            $series = [
                [
                    'name' => 'Total',
                    'data' => collect($data)->pluck('total')->toArray(),
                ],
            ];
        } elseif ($type === 'donut' || $type === 'pie') {
            // Formato para torta/donut: las series son un array simple de números y las categorías van al legend (labels)
            $categories = collect($data)->pluck('label')->toArray();
            $series = collect($data)->pluck('value')->toArray();
        }
    @endphp

    <div wire:ignore class="bg-white rounded-xl shadow p-6">
        @if ($title)
            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                {{ $title }}
            </h2>
        @endif

        <div id="{{ $id }}"></div>
    </div>

    @once
        <script>
            window.dashboardCharts = window.dashboardCharts || {};
        </script>
    @endonce

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let id = @json($id);
            let type = @json($type);

            if (window.dashboardCharts[id]) {
                window.dashboardCharts[id].destroy();
            }

            let options = {
                chart: {
                    type: type,
                    height: {{ $height }},
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: false
                    }
                },
                series: @json($series),
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#16a34a', '#3b82f6', '#ef4444', '#f59e0b', '#8b5cf6'],
                grid: {
                    borderColor: '#e5e7eb'
                },
                tooltip: {
                    theme: 'light'
                },
                legend: {
                    position: 'top'
                }
            };

            // Ajuste de opciones estructurales según el tipo de gráfico de ApexCharts
            if (type === 'donut' || type === 'pie') {
                options.labels = @json($categories);
            } else {
                options.xaxis = {
                    categories: @json($categories)
                };
            }

            window.dashboardCharts[id] = new ApexCharts(
                document.querySelector("#" + id),
                options
            );

            window.dashboardCharts[id].render();
        });
    </script>
