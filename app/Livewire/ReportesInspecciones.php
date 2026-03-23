<?php

namespace App\Livewire;

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
        // Consulta base con filtro de fechas
        $query = InspeccionMaestra::query()
            ->when($this->fecha_inicio && $this->fecha_fin, function($q) {
                $q->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
            });

        //Resumen de Totales
        $stats = [
            'total_ingresos' => $query->sum('monto_total'),
            'total_inspecciones' => $query->count(),
            'aprobados' => $query->clone()->where('resultado_estado', 'A')->count(),
            'anulados' => $query->clone()->whereNotNull('fecha_anulacion')->count(),
        ];

        // Data para Gráfico: Inspecciones por Tipo de Atención
        $porTipo = InspeccionMaestra::query()
            ->select('tipo_atencion', DB::raw('count(*) as total'), DB::raw('sum(monto_total) as ingresos'))
            ->when($this->fecha_inicio && $this->fecha_fin, function($q) {
                $q->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
            })
            ->groupBy('tipo_atencion')
            ->get();

        return view('livewire.reportes-inspecciones', [
            'stats' => $stats,
            'porTipo' => $porTipo
        ]);
    }*/

    public function render()
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
    }
}
