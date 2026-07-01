<?php

namespace App\Livewire;

use App\Models\InspeccionExtra;
use App\Models\InspeccionMaestra;
use Illuminate\Support\Carbon;
use Livewire\Component;

class DashboardCitv extends Component
{
    public $stats = [];
    public $operativo = [];
    public $financiero = [];

    public $graficoIngresos = [];

    public $graficoServicios = [];

    public $ultimasInspecciones = [];

    protected Carbon $hoy;

    // Datos base (se cargan una sola vez)
    private float $ingresosLey = 0;
    private float $ingresosExtras = 0;

    private int $cantidadLey = 0;
    private int $cantidadExtras = 0;

    public function mount()
    {
        $this->hoy = Carbon::today();

        $this->cargarDatosBase();

        $this->cargarKpis();
        $this->cargarResumenOperativo();
        $this->cargarResumenFinanciero();
        $this->cargarGraficoIngresos();
        $this->cargarGraficoServicios();
        $this->cargarUltimasInspecciones();
    }

    public function render()
    {
        return view('livewire.dashboard-citv');
    }

    private function cargarDatosBase()
    {
        $maestras = $this->queryMaestras();
        $extras = $this->queryExtras();

        $this->ingresosLey = (clone $maestras)->sum('monto_total');
        $this->ingresosExtras = (clone $extras)->sum('monto_total');

        $this->cantidadLey = (clone $maestras)->count();
        $this->cantidadExtras = (clone $extras)->count();
    }

    // CONSULTAS BASE
    private function queryMaestras()
    {
        return InspeccionMaestra::whereDate('fecha_inspeccion', $this->hoy)
            ->whereNull('fecha_anulacion')
            ->where('estado_inspeccion', '!=', 'Anulada');
    }

    private function queryExtras()
    {
        return InspeccionExtra::whereDate('fecha_inspeccion', $this->hoy)
            ->where('estado', '!=', 'Anulada');
    }

    // KPIs PRINCIPALES
    private function cargarKpis()
    {
        $aprobados = $this->queryMaestras()
            ->where('resultado_estado', 'A')
            ->count();

        $anulados =
            InspeccionMaestra::whereDate('fecha_inspeccion', $this->hoy)
                ->whereNotNull('fecha_anulacion')
                ->count()
            +
            InspeccionExtra::whereDate('fecha_inspeccion', $this->hoy)
                ->where('estado', 'Anulada')
                ->count();

        $this->stats = [

            'ingresos' => $this->ingresosLey + $this->ingresosExtras,

            'inspecciones' => $this->cantidadLey + $this->cantidadExtras,

            'aprobados' => $aprobados,

            'anulados' => $anulados,

        ];
    }

    // RESUMEN OPERATIVO
    private function cargarResumenOperativo()
    {
        $reinspecciones = $this->queryMaestras()
            ->where('es_reinspeccion', true)
            ->count();

        $ticketPromedio =
            ($this->cantidadLey + $this->cantidadExtras) > 0
            ? ($this->ingresosLey + $this->ingresosExtras) / ($this->cantidadLey + $this->cantidadExtras)
            : 0;

        $this->operativo = [

            'ley' => $this->cantidadLey,

            'extras' => $this->cantidadExtras,

            'reinspecciones' => $reinspecciones,

            'ticket' => $ticketPromedio,

        ];
    }

    // RESUMEN FINANCIERO
    private function cargarResumenFinanciero()
    {
        $this->financiero = [

            'ley' => $this->ingresosLey,

            'extras' => $this->ingresosExtras,

            'total' => $this->ingresosLey + $this->ingresosExtras,

        ];
    }

    private function cargarGraficoIngresos()
    {
        // Fecha inicial (últimos 30 días)
        $fechaInicio = $this->hoy->copy()->subDays(29);

        // Ingresos de Inspecciones Maestras
        $maestras = InspeccionMaestra::selectRaw("
                DATE(fecha_inspeccion) as fecha,
                SUM(monto_total) as total
            ")
            ->whereBetween('fecha_inspeccion', [$fechaInicio, $this->hoy])
            ->whereNull('fecha_anulacion')
            ->where('estado_inspeccion', '!=', 'Anulada')
            ->groupByRaw('DATE(fecha_inspeccion)')
            ->pluck('total', 'fecha')
            ->toArray();

        // Ingresos de Servicios Extras
        $extras = InspeccionExtra::selectRaw("
                DATE(fecha_inspeccion) as fecha,
                SUM(monto_total) as total
            ")
            ->whereBetween('fecha_inspeccion', [$fechaInicio, $this->hoy])
            ->where('estado', '!=', 'Anulada')
            ->groupByRaw('DATE(fecha_inspeccion)')
            ->pluck('total', 'fecha')
            ->toArray();

        // Consolidar información para ApexCharts
        $this->graficoIngresos = [];

        for ($fecha = $fechaInicio->copy(); $fecha->lte($this->hoy); $fecha->addDay()) {

            $key = $fecha->format('Y-m-d');

            $this->graficoIngresos[] = [
                'fecha' => $fecha->format('d/m'),
                'total' => ($maestras[$key] ?? 0) + ($extras[$key] ?? 0),
            ];
        }
    }

    private function cargarGraficoServicios()
    {
        // Inspecciones de Ley
        $maestras = InspeccionMaestra::selectRaw("
                tipo_atencion as nombre,
                COUNT(*) as total
            ")
            ->whereDate('fecha_inspeccion', $this->hoy)
            ->whereNull('fecha_anulacion')
            ->where('estado_inspeccion', '!=', 'Anulada')
            ->groupBy('tipo_atencion')
            ->get();

        // Servicios Extras
        $extras = InspeccionExtra::join(
                'tipos_servicios_extras',
                'inspecciones_extras.tipo_servicio_id',
                '=',
                'tipos_servicios_extras.id'
            )
            ->selectRaw("
                tipos_servicios_extras.nombre_servicio as nombre,
                COUNT(*) as total
            ")
            ->whereDate('inspecciones_extras.fecha_inspeccion', $this->hoy)
            ->where('inspecciones_extras.estado', '!=', 'Anulada')
            ->groupBy('tipos_servicios_extras.nombre_servicio')
            ->get();

        // Unificar ambos resultados
        $servicios = [];

        foreach ($maestras as $item) {

            $servicios[$item->nombre] = ($servicios[$item->nombre] ?? 0) + $item->total;
        }

        foreach ($extras as $item) {

            $servicios[$item->nombre] = ($servicios[$item->nombre] ?? 0) + $item->total;
        }

        // Formato para ApexCharts
        $this->graficoServicios = [];

        foreach ($servicios as $nombre => $cantidad) {

            $this->graficoServicios[] = [

                'label' => $nombre,

                'value' => $cantidad,

            ];
        }
    }

    private function cargarUltimasInspecciones()
    {
        // Inspecciones de Ley
        $maestras = InspeccionMaestra::selectRaw("
                TIME(hora_inicio) as hora,
                placa_vehiculo as placa,
                tipo_atencion as servicio,
                CASE
                    WHEN resultado_estado = 'A' THEN 'APROBADO'
                    WHEN resultado_estado = 'D' THEN 'DESAPROBADO'
                    ELSE 'EN PROCESO'
                END as resultado,
                monto_total as monto
            ")
            ->whereDate('fecha_inspeccion', $this->hoy)
            ->whereNull('fecha_anulacion');

        // Servicios Extras
        $extras = InspeccionExtra::join(
                'vehiculos',
                'inspecciones_extras.vehiculo_id',
                '=',
                'vehiculos.id'
            )
            ->join(
                'tipos_servicios_extras',
                'inspecciones_extras.tipo_servicio_id',
                '=',
                'tipos_servicios_extras.id'
            )
            ->selectRaw("
                hora_inspeccion as hora,
                vehiculos.placa as placa,
                tipos_servicios_extras.nombre_servicio as servicio,
                resultado_final as resultado,
                monto_total as monto
            ")
            ->whereDate('inspecciones_extras.fecha_inspeccion', $this->hoy)
            ->where('inspecciones_extras.estado', '!=', 'Anulada');

        $this->ultimasInspecciones = $maestras
            ->unionAll($extras)
            ->orderByDesc('hora')
            ->limit(5)
            ->get();
    }
}
