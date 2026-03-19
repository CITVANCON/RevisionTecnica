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
        //$this->fecha_inicio = Carbon::today()->format('Y-m-d');
        //$this->fecha_fin = Carbon::today()->format('Y-m-d');
        $this->fecha_inicio = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $query = InspeccionMaestra::query()
            ->when($this->fecha_inicio && $this->fecha_fin, function($q) {
                $q->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
            })
            ->orderBy('fecha_inspeccion', 'asc');

        $inspecciones = $query->get();

        // Totales Generales
        $this->total_monto = $inspecciones->sum('monto_total');
        $this->total_inspecciones = $inspecciones->count();

        // NUEVO: Resumen para Arqueo de Caja
        $resumenPagos = $inspecciones->groupBy('metodo_pago')
            ->map(function ($row) {
                return [
                    'cantidad' => $row->count(),
                    'total' => $row->sum('monto_total')
                ];
            });

        return view('livewire.reportes-inspecciones-ejecutivo', [
            'inspecciones' => $inspecciones,
            'resumenPagos' => $resumenPagos
        ]);
    }

}
