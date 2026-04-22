<?php

namespace App\Livewire;

use App\Models\CierreDiario;
use App\Models\Gasto;
use App\Models\InspeccionExtra;
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
    }*/
    
    public function render()
    {
        $rango = [$this->fecha_inicio, $this->fecha_fin];

        // 1. Estadísticas de Inspecciones MAESTRAS (Ley)
        $statsMaestras = InspeccionMaestra::query()
            ->select(
                DB::raw("SUM(CASE WHEN fecha_anulacion IS NULL AND estado_inspeccion != 'Anulada' THEN monto_total ELSE 0 END) as ingresos"),
                DB::raw("COUNT(CASE WHEN fecha_anulacion IS NULL AND estado_inspeccion != 'Anulada' THEN 1 END) as validas"),
                DB::raw("COUNT(CASE WHEN resultado_estado = 'A' AND fecha_anulacion IS NULL AND estado_inspeccion != 'Anulada' THEN 1 END) as aprobados"),
                DB::raw("COUNT(CASE WHEN fecha_anulacion IS NOT NULL OR estado_inspeccion = 'Anulada' THEN 1 END) as anulados"),
                DB::raw("SUM(CASE WHEN fecha_anulacion IS NOT NULL OR estado_inspeccion = 'Anulada' THEN monto_total ELSE 0 END) as monto_anulado")
            )
            ->whereBetween('fecha_inspeccion', $rango)
            ->first();

        // 2. Estadísticas de Inspecciones EXTRAS (Servicios Adicionales)
        // Aquí también especificamos la tabla en el CASE WHEN para evitar errores futuros
        $statsExtras = InspeccionExtra::query()
            ->select(
                DB::raw("SUM(CASE WHEN inspecciones_extras.estado != 'Anulada' THEN monto_total ELSE 0 END) as ingresos"),
                DB::raw("COUNT(CASE WHEN inspecciones_extras.estado != 'Anulada' THEN 1 END) as validas"),
                DB::raw("COUNT(CASE WHEN inspecciones_extras.estado = 'Anulada' THEN 1 END) as anulados"),
                DB::raw("SUM(CASE WHEN inspecciones_extras.estado = 'Anulada' THEN monto_total ELSE 0 END) as monto_anulado")
            )
            ->whereBetween('fecha_inspeccion', $rango)
            ->first();

        // 3. Consolidación de Totales Financieros
        $totalIngresos = ($statsMaestras->ingresos ?? 0) + ($statsExtras->ingresos ?? 0);
        $totalValidas = ($statsMaestras->validas ?? 0) + ($statsExtras->validas ?? 0);
        $totalAnulados = ($statsMaestras->anulados ?? 0) + ($statsExtras->anulados ?? 0);
        $totalMontoAnulado = ($statsMaestras->monto_anulado ?? 0) + ($statsExtras->monto_anulado ?? 0);
        
        $totalComisiones = CierreDiario::whereBetween('fecha', $rango)->sum('comision_pos');
        $totalGastos = Gasto::whereBetween('fecha', $rango)->sum('monto');

        $stats = [
            'total_ingresos' => $totalIngresos,
            'total_inspecciones' => $totalValidas,
            'aprobados' => $statsMaestras->aprobados ?? 0, 
            'anulados' => $totalAnulados,
            'monto_anulado' => $totalMontoAnulado,
            'total_comisiones' => $totalComisiones,
            'total_gastos' => $totalGastos,
            'utilidad_real' => $totalIngresos - $totalComisiones - $totalGastos,
        ];

        // 4. Distribución Unificada por Tipo de Servicio
        // Parte A: Inspecciones Maestras
        $porTipoMaestras = InspeccionMaestra::query()
            ->select(
                'tipo_atencion as nombre', 
                DB::raw('count(*) as total'), 
                DB::raw('sum(monto_total) as ingresos')
            )
            ->whereBetween('fecha_inspeccion', $rango)
            ->whereNull('fecha_anulacion')
            ->where('estado_inspeccion', '!=', 'Anulada')
            ->groupBy('tipo_atencion');

        // Parte B: Inspecciones Extras (CORRECCIÓN DE AMBIGÜEDAD AQUÍ)
        $porTipo = InspeccionExtra::query()
            ->join('tipos_servicios_extras', 'inspecciones_extras.tipo_servicio_id', '=', 'tipos_servicios_extras.id')
            ->select(
                'tipos_servicios_extras.nombre_servicio as nombre', 
                DB::raw('count(*) as total'), 
                DB::raw('sum(monto_total) as ingresos')
            )
            ->whereBetween('fecha_inspeccion', $rango)
            ->where('inspecciones_extras.estado', '!=', 'Anulada') // <-- TABLA ESPECIFICADA
            ->groupBy('tipos_servicios_extras.nombre_servicio')
            ->union($porTipoMaestras)
            ->get()
            ->sortByDesc('ingresos');

        return view('livewire.reportes-inspecciones', [
            'stats' => $stats,
            'porTipo' => $porTipo
        ]);
    }
}
