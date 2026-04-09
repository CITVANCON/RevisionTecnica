<?php

namespace App\Livewire;

use App\Models\CierreDiario;
use App\Models\Gasto;
use App\Models\InspeccionMaestra;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesInspecciones extends Component
{
    public $fecha_inicio;
    public $fecha_fin;

    public function mount()
    {
        // Por defecto, mostrar el mes actual
        $this->fecha_inicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin = Carbon::now()->format('Y-m-d');
    }

    /*public function render()
    {
        // 1. Obtener Stats de Inspecciones en una sola consulta
        $inspeccionesStats = InspeccionMaestra::query()
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(monto_total) as bruto'),
                DB::raw("COUNT(CASE WHEN resultado_estado = 'A' THEN 1 END) as aprobados"),
                DB::raw("COUNT(CASE WHEN fecha_anulacion IS NOT NULL THEN 1 END) as anulados"),
                // Calculamos cuánto dinero se dejó de percibir por anulaciones
                DB::raw("SUM(CASE WHEN fecha_anulacion IS NOT NULL THEN monto_total ELSE 0 END) as monto_anulado")
            )
            ->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin])
            ->first();

        // 2. Obtener Gastos del periodo (Diarios + Mensuales que caigan en el rango)
        $totalGastos = Gasto::query()
            ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha_fin])
            ->sum('monto');

        $stats = [
            'total_ingresos' => $inspeccionesStats->bruto ?? 0,
            'total_inspecciones' => $inspeccionesStats->total ?? 0,
            'aprobados' => $inspeccionesStats->aprobados ?? 0,
            'anulados' => $inspeccionesStats->anulados ?? 0,
            'monto_anulado' => $inspeccionesStats->monto_anulado ?? 0,
            'utilidad_estimada' => ($inspeccionesStats->bruto ?? 0) - $totalGastos,
            'total_gastos' => $totalGastos
        ];

        // 3. Distribución por Tipo de Atención
        $porTipo = InspeccionMaestra::query()
            ->select('tipo_atencion', DB::raw('count(*) as total'), DB::raw('sum(monto_total) as ingresos'))
            ->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin])
            ->groupBy('tipo_atencion')
            ->orderBy('ingresos', 'desc')
            ->get();

        return view('livewire.reportes-inspecciones', [
            'stats' => $stats,
            'porTipo' => $porTipo
        ]);
    }*/

    public function render()
    {
        // 1. Obtener Stats de Inspecciones (Filtrando dinero real vs anulado)
        $inspeccionesStats = InspeccionMaestra::query()
            ->select(
                // Total de registros (incluye todo para saber volumen de trabajo)
                DB::raw('COUNT(*) as total_registros'),

                // Solo sumamos el monto de las que NO están anuladas
                //DB::raw("SUM(CASE WHEN fecha_anulacion IS NULL THEN monto_total ELSE 0 END) as ingresos_reales"),
                // Conteo de válidas para productividad
                //DB::raw("COUNT(CASE WHEN fecha_anulacion IS NULL THEN 1 END) as total_validas"),
                //DB::raw("COUNT(CASE WHEN resultado_estado = 'A' AND fecha_anulacion IS NULL THEN 1 END) as aprobados"),
                //DB::raw("COUNT(CASE WHEN fecha_anulacion IS NOT NULL THEN 1 END) as anulados"),
                //DB::raw("SUM(CASE WHEN fecha_anulacion IS NOT NULL THEN monto_total ELSE 0 END) as monto_anulado")

                // INGRESOS REALES: Solo si NO tiene fecha de anulación Y el estado NO es 'Anulada'
                DB::raw("SUM(CASE WHEN fecha_anulacion IS NULL AND estado_inspeccion != 'Anulada' THEN monto_total ELSE 0 END) as ingresos_reales"),
                
                // CONTEO VÁLIDAS: Productividad real
                DB::raw("COUNT(CASE WHEN fecha_anulacion IS NULL AND estado_inspeccion != 'Anulada' THEN 1 END) as total_validas"),
                
                // APROBADOS: Solo de las vigentes
                DB::raw("COUNT(CASE WHEN resultado_estado = 'A' AND fecha_anulacion IS NULL AND estado_inspeccion != 'Anulada' THEN 1 END) as aprobados"),
                
                // ANULADOS: Si tiene fecha de anulación O el estado dice 'Anulada'
                DB::raw("COUNT(CASE WHEN fecha_anulacion IS NOT NULL OR estado_inspeccion = 'Anulada' THEN 1 END) as anulados"),
                
                // MONTO ANULADO: Dinero que no entró a caja
                DB::raw("SUM(CASE WHEN fecha_anulacion IS NOT NULL OR estado_inspeccion = 'Anulada' THEN monto_total ELSE 0 END) as monto_anulado")
            )
            ->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin])
            ->first();

        // 2. Obtener la suma de comisiones de POS registradas en Auditoría (CierreDiario)
        // Buscamos los cierres que caen en el rango de fechas seleccionado
        $totalComisionesAuditadas = CierreDiario::query()
            ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha_fin])
            ->sum('comision_pos');

        // 2. Obtener Gastos del periodo
        $totalGastos = Gasto::query()
            ->whereBetween('fecha', [$this->fecha_inicio, $this->fecha_fin])
            ->sum('monto');

        // 4. Calcular Ingresos Operativos (Ingresos - Comisiones - Gastos)
        $ingresosReales = $inspeccionesStats->ingresos_reales ?? 0;
        $utilidadReal = $ingresosReales - $totalComisionesAuditadas - $totalGastos;

        $stats = [
            'total_ingresos' => $ingresosReales ?? 0,
            'total_inspecciones' => $inspeccionesStats->total_validas ?? 0,
            'aprobados' => $inspeccionesStats->aprobados ?? 0,
            'anulados' => $inspeccionesStats->anulados ?? 0,
            'monto_anulado' => $inspeccionesStats->monto_anulado ?? 0,
            'total_comisiones' => $totalComisionesAuditadas ?? 0,
            //'utilidad_estimada' => ($inspeccionesStats->ingresos_reales ?? 0) - $totalGastos,
            'total_gastos' => $totalGastos,
            'utilidad_real' => $utilidadReal,
        ];

        // 3. Distribución por Tipo de Atención (SOLO NO ANULADAS)
        // Así la participación se calcula sobre lo que realmente generó dinero
        $porTipo = InspeccionMaestra::query()
            ->select(
                'tipo_atencion',
                DB::raw('count(*) as total'),
                DB::raw('sum(monto_total) as ingresos')
            )
            ->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin])

            ->whereNull('fecha_anulacion')
            ->where('estado_inspeccion', '!=', 'Anulada')

            ->groupBy('tipo_atencion')
            ->orderBy('ingresos', 'desc')
            ->get();

        return view('livewire.reportes-inspecciones', [
            'stats' => $stats,
            'porTipo' => $porTipo
        ]);
    }
}
