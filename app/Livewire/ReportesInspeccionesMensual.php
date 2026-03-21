<?php

namespace App\Livewire;

use App\Models\Gasto;
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

        // Ingresos Agrupados por día
        $reporteMensual = InspeccionMaestra::query()
            ->select(
                'fecha_inspeccion',
                DB::raw('count(*) as total_certificados'),
                DB::raw('sum(monto_total) as monto_dia'),
                DB::raw("SUM(CASE WHEN metodo_pago = 'EFECTIVO' THEN monto_total ELSE 0 END) as monto_efectivo"),
                DB::raw("SUM(CASE WHEN metodo_pago IN ('YAPE', 'VISA', 'TRANSFERENCIA') THEN monto_total ELSE 0 END) as monto_pos")
            )
            ->whereMonth('fecha_inspeccion', $fecha->month)
            ->whereYear('fecha_inspeccion', $anio)
            ->groupBy('fecha_inspeccion')
            ->orderBy('fecha_inspeccion', 'asc')
            ->get();

        // Gastos DIARIOS del mes agrupados por fecha
        $gastosDiarios = Gasto::query()
            ->select('fecha', DB::raw('sum(monto) as total_gasto_dia'))
            ->whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->where('tipo_egreso', 'DIARIO')
            ->groupBy('fecha')
            ->get()
            ->keyBy(function($item) {
                return $item->fecha->format('Y-m-d');
            });

        // Mapear los gastos a la colección de reportes
        $reporteMensual->transform(function ($fila) use ($gastosDiarios) {
            $fechaKey = Carbon::parse($fila->fecha_inspeccion)->format('Y-m-d');

            // gastos operativos diarios
            $fila->monto_gastos = $gastosDiarios->has($fechaKey)
                ? $gastosDiarios[$fechaKey]->total_gasto_dia
                : 0;

            // saldo en caja (efectivo - gastos)
            $fila->saldo_efectivo = $fila->monto_efectivo - $fila->monto_gastos;

            // Saldo del dia (Saldo Efectivo + POS)
            $fila->saldo_dia = $fila->saldo_efectivo + $fila->monto_pos;

            return $fila;
        });

        // Egresos del mes (Totales)
        $totalGastosMes = Gasto::mensuales($fecha->month, $anio)->sum('monto');
        $ingresoBruto = $reporteMensual->sum('monto_dia');

        // Totales finales para el balance
        $balance = [
            'total_certificados' => $reporteMensual->sum('total_certificados'),
            'ingreso_bruto'      => $ingresoBruto,
            'total_gastos'       => $totalGastosMes,
            'ingreso_neto'       => $ingresoBruto - $totalGastosMes,
        ];

        return view('livewire.reportes-inspecciones-mensual', [
            'reporte' => $reporteMensual,
            'balance' => $balance,
            'nombreMes' => $nombreMes,
            'anio' => $anio
        ]);
    }
}
