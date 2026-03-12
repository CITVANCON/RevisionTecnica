<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InspeccionMaestra;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesInspeccionesMensual extends Component
{
    public $mes_seleccionado;

    public function mount()
    {
        // Por defecto, el mes actual en formato YYYY-MM
        $this->mes_seleccionado = Carbon::now()->format('Y-m');
    }

    public function render()
    {
        $fecha = Carbon::parse($this->mes_seleccionado);
        $nombreMes = $fecha->translatedFormat('F');
        $anio = $fecha->year;

        // Agrupamos por día del mes seleccionado
        $reporteMensual = InspeccionMaestra::query()
            ->select(
                'fecha_inspeccion',
                DB::raw('count(*) as total_certificados'),
                DB::raw('sum(monto_total) as monto_dia')
            )
            ->whereMonth('fecha_inspeccion', $fecha->month)
            ->whereYear('fecha_inspeccion', $anio)
            ->groupBy('fecha_inspeccion')
            ->orderBy('fecha_inspeccion', 'asc')
            ->get();

        // Totales finales para el balance
        $balance = [
            'total_certificados' => $reporteMensual->sum('total_certificados'),
            'ingreso_bruto' => $reporteMensual->sum('monto_dia'),
        ];

        return view('livewire.reportes-inspecciones-mensual', [
            'reporte' => $reporteMensual,
            'balance' => $balance,
            'nombreMes' => $nombreMes,
            'anio' => $anio
        ]);
    }
}
