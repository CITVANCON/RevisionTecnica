<?php

namespace App\Livewire;

use App\Models\Gasto;
use App\Models\InspeccionMaestra;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesInspeccionesEjecutivo extends Component
{
    // Filtro único
    public $fecha;
    // Totales de Inspecciones (Ingresos)
    public $total_monto = 0;
    public $total_inspecciones = 0;
    public $total_comisiones = 0;
    // Totales de Gastos (Egresos)
    public $total_gastos = 0;

    public function mount()
    {
        $this->fecha = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        // Ingresos
        $queryInspecciones = InspeccionMaestra::query();
        if ($this->fecha) {
            $queryInspecciones->whereDate('fecha_inspeccion', $this->fecha);
        }
        $inspecciones = $queryInspecciones->orderBy('id_inspeccion_local', 'asc')->get();

        //Crear una colección filtrada que NO tenga las anuladas para los totales
        // fecha_anulacion == null significa que NO está anulada
        $inspeccionesActivas = $inspecciones->whereNull('fecha_anulacion');

        //$this->total_monto = $inspecciones->sum('monto_total');
        //$this->total_inspecciones = $inspecciones->count();
        //$this->total_comisiones = $inspecciones->sum('comision_monto');
        $this->total_monto = $inspeccionesActivas->sum('monto_total');
        $this->total_inspecciones = $inspeccionesActivas->count();
        $this->total_comisiones = $inspeccionesActivas->sum('comision_monto');

        // Resumen de Pagos para los indicadores
        $resumenPagos = $inspeccionesActivas->groupBy('metodo_pago')
            ->map(fn($row) => [
                'cantidad' => $row->count(),
                'total' => $row->sum('monto_total')
            ]);

        // Egresos diarios
        $gastos = Gasto::diarios($this->fecha)->orderBy('id', 'asc')->get();
        $this->total_gastos = $gastos->sum('monto');

        // 1. Obtener el total de ingresos en EFECTIVO desde el resumen que ya tienes
        $ingresosEfectivo = $resumenPagos['EFECTIVO']['total'] ?? 0;
        // 2. Calcular el efectivo real (Ingreso Efectivo - Gastos)
        $efectivo_neto = $ingresosEfectivo - $this->total_gastos;

        return view('livewire.reportes-inspecciones-ejecutivo', [
            'inspecciones' => $inspecciones,
            'resumenPagos' => $resumenPagos,
            'gastos' => $gastos,
            'saldo_neto' => $this->total_monto - $this->total_gastos,
            'efectivo_neto' => $efectivo_neto,
            'total_comisiones' => $this->total_comisiones
        ]);
    }
}
