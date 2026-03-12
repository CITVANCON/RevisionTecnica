<?php

namespace App\Livewire;

use App\Models\InspeccionMaestra;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesInspeccionesEjecutivo extends Component
{
    // Filtros
    public $fecha_inicio;
    public $fecha_fin;

    // Propiedades para los totales de la última fila
    public $total_monto = 0;
    public $total_inspecciones = 0;

    public function mount()
    {
        // Por defecto, mostrar el mes actual
        $this->fecha_inicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        // 1. Consulta base con filtros
        $query = InspeccionMaestra::query()
            ->when($this->fecha_inicio && $this->fecha_fin, function($q) {
                $q->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
            })
            ->orderBy('fecha_inspeccion', 'asc')
            ->orderBy('hora_inicio', 'asc');

        // 2. Obtener TODOS los registros filtrados (sin paginación para el reporte detallado)
        $inspecciones = $query->get();

        // 3. Calcular sumatorias para la fila final de totales
        $this->total_monto = $inspecciones->sum('monto_total');
        $this->total_inspecciones = $inspecciones->count();

        // 4. Mantenemos el resumen por tipo de atención por si quieres usarlo arriba
        $resumenPorTipo = InspeccionMaestra::query()
            ->select('tipo_atencion', DB::raw('count(*) as cantidad'), DB::raw('sum(monto_total) as ingresos'))
            ->when($this->fecha_inicio && $this->fecha_fin, function($q) {
                $q->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
            })
            ->groupBy('tipo_atencion')
            ->get();

        return view('livewire.reportes-inspecciones-ejecutivo', [
            'inspecciones' => $inspecciones,
            'resumenPorTipo' => $resumenPorTipo
        ]);
    }

    /*public function render()
    {
        return view('livewire.reportes-inspecciones-ejecutivo');
    }*/
}
