<?php

namespace App\Livewire;

use App\Models\CierreDiario;
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

    /*public function render()
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

            ->whereNull('fecha_anulacion')
            ->where('estado_inspeccion', '!=', 'Anulada')
            
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

        // egresis MENSUALES
        $egresosMensuales = Gasto::query()
            ->whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->where('tipo_egreso', 'MENSUAL')
            ->orderBy('fecha', 'asc')
            ->get();

        // Obtener todos los cierres diarios del mes seleccionado
        $cierresDelMes = CierreDiario::whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->get()
            ->keyBy(function($item) {
                // Forzamos a que la llave sea un string YYYY-MM-DD aunque el cast sea date
                return is_string($item->fecha) ? $item->fecha : $item->fecha->format('Y-m-d');
            });

        $reporteMensual->transform(function ($fila) use ($gastosDiarios, $cierresDelMes) {
            $fechaKey = Carbon::parse($fila->fecha_inspeccion)->format('Y-m-d');
            $cierre = $cierresDelMes->get($fechaKey);

            // Gastos operativos diarios
            $fila->monto_gastos = $gastosDiarios->has($fechaKey) ? $gastosDiarios[$fechaKey]->total_gasto_dia : 0;
            
            // Lógica de POS y Comisiones
            if ($cierre) {
                // CASO CON CIERRE:
                // El banco ya viene neto
                $fila->comision_pos = $cierre->comision_pos;
                $fila->monto_pos_neto = $cierre->monto_neto_pos;
                
                // IMPORTANTE: El efectivo real YA NO resta gastos porque ya fue restado al cerrar caja
                $fila->monto_efectivo_final = $cierre->efectivo_real; 
                $fila->saldo_efectivo = $cierre->efectivo_real; 
            } else {
                // CASO SIN CIERRE (Estimado del sistema):
                $fila->comision_pos = 0; 
                $fila->monto_pos_neto = $fila->monto_pos;
                
                // Aquí SÍ restamos los gastos porque el sistema solo conoce el Ingreso Bruto
                $fila->saldo_efectivo = $fila->monto_efectivo - $fila->monto_gastos;
            }

            //$fila->saldo_efectivo = $fila->monto_efectivo_real - $fila->monto_gastos;
            
            // Saldo del día = Efectivo neto + POS ya descontado la comisión
            $fila->saldo_dia = $fila->saldo_efectivo + $fila->monto_pos_neto;

            $fila->cierre = $cierre; 
            
            return $fila;
        });

        // Totales finales para el balance
        $ingresoBruto = $reporteMensual->sum('monto_dia');

        $totalComisiones = $reporteMensual->sum('comision_pos');

        $ingresosOperativos = $reporteMensual->sum('saldo_dia'); // Suma de saldos por día
        $egresosMensualesTotal = $egresosMensuales->sum('monto');

        // Totales finales para el balance
        $balance = [
            'total_certificados' => $reporteMensual->sum('total_certificados'),
            'ingreso_bruto'      => $ingresoBruto,

            'total_comisiones'   => $totalComisiones,

            'ingresos_operativos' => $ingresosOperativos,
            'egresos_mensuales'   => $egresosMensualesTotal,
            'utilidad_real'       => $ingresosOperativos - $egresosMensualesTotal,

        ];

        return view('livewire.reportes-inspecciones-mensual', [
            'reporte' => $reporteMensual,
            'egresosMensuales' => $egresosMensuales,
            'balance' => $balance,
            'nombreMes' => $nombreMes,
            'anio' => $anio,
        ]);
    }*/

    public function render()
    {
        $fecha = Carbon::parse($this->mes_seleccionado);
        $nombreMes = $fecha->translatedFormat('F');
        $anio = $fecha->year;

        // 1. Definir Subconsultas para Unificar Ingresos
        $ingresosPrincipales = DB::table('inspecciones_maestras')
            ->select(
                'fecha_inspeccion',
                'monto_total',
                'metodo_pago',
                'estado_inspeccion',
                'fecha_anulacion'
            )
            ->whereMonth('fecha_inspeccion', $fecha->month)
            ->whereYear('fecha_inspeccion', $anio);

        $ingresosExtras = DB::table('inspecciones_extras')
            ->select(
                'fecha_inspeccion',
                'monto_total',
                'metodo_pago',
                'estado as estado_inspeccion',
                DB::raw('NULL as fecha_anulacion')
            )
            ->whereMonth('fecha_inspeccion', $fecha->month)
            ->whereYear('fecha_inspeccion', $anio);

        // 2. Unificar y Agrupar por Día
        $reporteMensual = DB::query()
            ->fromSub($ingresosPrincipales->unionAll($ingresosExtras), 'ingresos_unificados')
            ->select(
                'fecha_inspeccion',
                DB::raw('count(*) as total_certificados'),
                DB::raw('sum(monto_total) as monto_dia'),
                DB::raw("SUM(CASE WHEN metodo_pago = 'EFECTIVO' THEN monto_total ELSE 0 END) as monto_efectivo"),
                DB::raw("SUM(CASE WHEN metodo_pago IN ('YAPE', 'VISA', 'TRANSFERENCIA') THEN monto_total ELSE 0 END) as monto_pos")
            )
            ->whereNull('fecha_anulacion')
            ->where('estado_inspeccion', '!=', 'Anulada')
            ->groupBy('fecha_inspeccion')
            ->orderBy('fecha_inspeccion', 'asc')
            ->get();

        // 3. Gastos DIARIOS
        $gastosDiarios = Gasto::query()
            ->select('fecha', DB::raw('sum(monto) as total_gasto_dia'))
            ->whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->where('tipo_egreso', 'DIARIO')
            ->groupBy('fecha')
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->fecha)->format('Y-m-d');
            });

        // 4. Egresos MENSUALES
        $egresosMensuales = Gasto::query()
            ->whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->where('tipo_egreso', 'MENSUAL')
            ->orderBy('fecha', 'asc')
            ->get();

        // 5. Cierres Diarios
        $cierresDelMes = CierreDiario::whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->get()
            ->keyBy(function($item) {
                return is_string($item->fecha) ? $item->fecha : $item->fecha->format('Y-m-d');
            });

        // 6. Transformación de datos con Lógica de Cierre
        $reporteMensual->transform(function ($fila) use ($gastosDiarios, $cierresDelMes) {
            $fechaKey = Carbon::parse($fila->fecha_inspeccion)->format('Y-m-d');
            $cierre = $cierresDelMes->get($fechaKey);

            $fila->monto_gastos = $gastosDiarios->has($fechaKey) ? $gastosDiarios[$fechaKey]->total_gasto_dia : 0;
            
            if ($cierre) {
                $fila->comision_pos = $cierre->comision_pos;
                $fila->monto_pos_neto = $cierre->monto_neto_pos;
                $fila->saldo_efectivo = $cierre->efectivo_real; 
            } else {
                $fila->comision_pos = 0; 
                $fila->monto_pos_neto = $fila->monto_pos;
                $fila->saldo_efectivo = $fila->monto_efectivo - $fila->monto_gastos;
            }
            
            $fila->saldo_dia = $fila->saldo_efectivo + $fila->monto_pos_neto;
            $fila->cierre = $cierre; 
            
            return $fila;
        });

        // 7. Balance Final
        $ingresosOperativos = $reporteMensual->sum('saldo_dia');
        $egresosMensualesTotal = $egresosMensuales->sum('monto');

        $balance = [
            'total_certificados' => $reporteMensual->sum('total_certificados'),
            'ingreso_bruto'      => $reporteMensual->sum('monto_dia'),
            'total_comisiones'   => $reporteMensual->sum('comision_pos'),
            'ingresos_operativos' => $ingresosOperativos,
            'egresos_mensuales'   => $egresosMensualesTotal,
            'utilidad_real'       => $ingresosOperativos - $egresosMensualesTotal,
        ];

        return view('livewire.reportes-inspecciones-mensual', [
            'reporte' => $reporteMensual,
            'egresosMensuales' => $egresosMensuales,
            'balance' => $balance,
            'nombreMes' => $nombreMes,
            'anio' => $anio,
        ]);
    }
}
