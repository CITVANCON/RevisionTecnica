<?php

namespace App\Livewire;

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

    public function render()
    {
        // Consulta base con filtro de fechas
        $query = InspeccionMaestra::query()
            ->when($this->fecha_inicio && $this->fecha_fin, function($q) {
                $q->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
            });

        // 1. Resumen de Totales
        $stats = [
            'total_ingresos' => $query->sum('monto_total'),
            'total_inspecciones' => $query->count(),
            'aprobados' => $query->clone()->where('resultado_estado', 'A')->count(),
            'anulados' => $query->clone()->whereNotNull('fecha_anulacion')->count(),
        ];

        // 2. Data para Gráfico: Inspecciones por Tipo de Atención
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
    }

}
