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

    public function render()
    {
        $fecha = Carbon::parse($this->mes_seleccionado);
        $nombreMes = $fecha->translatedFormat('F');
        $anio = $fecha->year;

        // 1. Unificar IDs y Fechas de todas las inspecciones del mes (Maestras + Extras)
        // Esto sirve para tener la lista de días con actividad y conteo de certificados
        $inspeccionesPrincipales = DB::table('inspecciones_maestras')
            ->select('id', 'fecha_inspeccion', DB::raw("'App\\\\Models\\\\InspeccionMaestra' as tipo"))
            ->whereMonth('fecha_inspeccion', $fecha->month)
            ->whereYear('fecha_inspeccion', $anio)
            ->where('estado_inspeccion', '!=', 'Anulada')
            ->whereNull('fecha_anulacion');

        $inspeccionesExtras = DB::table('inspecciones_extras')
            ->select('id', 'fecha_inspeccion', DB::raw("'App\\\\Models\\\\InspeccionExtra' as tipo"))
            ->whereMonth('fecha_inspeccion', $fecha->month)
            ->whereYear('fecha_inspeccion', $anio)
            ->where('estado', '!=', 'Anulada');

        $unificadas = $inspeccionesPrincipales->unionAll($inspeccionesExtras);

        // 2. Agrupar por día y sumar desde DETALLES_PAGOS usando los tipos polimórficos
        $reporteMensual = DB::query()
            ->fromSub($unificadas, 'u')
            ->join('detalles_pagos as dp', function($join) {
                $join->on('dp.pagoable_id', '=', 'u.id')
                     ->on('dp.pagoable_type', '=', 'u.tipo');
            })
            ->select(
                'u.fecha_inspeccion',
                DB::raw('COUNT(DISTINCT CONCAT(u.tipo, u.id)) as total_certificados'),
                DB::raw('SUM(dp.monto) as monto_dia'),
                DB::raw("SUM(CASE WHEN dp.metodo_pago = 'EFECTIVO' THEN dp.monto ELSE 0 END) as monto_efectivo"),
                DB::raw("SUM(CASE WHEN dp.metodo_pago IN ('YAPE', 'VISA', 'TRANSFERENCIA') THEN dp.monto ELSE 0 END) as monto_pos")
            )
            ->groupBy('u.fecha_inspeccion')
            ->orderBy('u.fecha_inspeccion', 'asc')
            ->get();

        // 3. Obtener Gastos DIARIOS
        $gastosDiarios = Gasto::whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->where('tipo_egreso', 'DIARIO')
            ->select('fecha', DB::raw('sum(monto) as total_gasto_dia'))
            ->groupBy('fecha')
            ->get()
            ->keyBy(fn($i) => Carbon::parse($i->fecha)->format('Y-m-d'));

        // 4. Obtener Cierres Diarios (La verdad auditada)
        $cierresDelMes = CierreDiario::whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->get()
            ->keyBy('fecha');

        // 5. Transformar datos cruzando con Auditoría
        $reporteMensual->transform(function ($fila) use ($gastosDiarios, $cierresDelMes) {
            $fechaKey = Carbon::parse($fila->fecha_inspeccion)->format('Y-m-d');
            $cierre = $cierresDelMes->get($fechaKey);
            $gastoDia = $gastosDiarios->get($fechaKey)->total_gasto_dia ?? 0;

            $fila->monto_gastos = $gastoDia;
            
            if ($cierre) {
                // Si hay cierre, mandan los montos reales que el auditor contó
                $fila->comision_pos = (float)$cierre->comision_pos;
                $fila->monto_pos_neto = (float)$cierre->monto_neto_pos;
                $fila->saldo_efectivo = (float)$cierre->efectivo_real; 
                $fila->estado_cierre = $cierre->estado;
            } else {
                // Si no hay cierre, usamos el cálculo teórico del sistema
                $fila->comision_pos = 0; 
                $fila->monto_pos_neto = (float)$fila->monto_pos;
                $fila->saldo_efectivo = (float)$fila->monto_efectivo - $gastoDia;
                $fila->estado_cierre = 'Sin Registro';
            }
            
            $fila->saldo_dia = $fila->saldo_efectivo + $fila->monto_pos_neto;
            $fila->cierre = $cierre; 
            
            return $fila;
        });

        // 6. Egresos MENSUALES y Balance
        $egresosMensuales = Gasto::whereMonth('fecha', $fecha->month)
            ->whereYear('fecha', $anio)
            ->where('tipo_egreso', 'MENSUAL')
            ->get();

        $balance = [
            'total_certificados' => $reporteMensual->sum('total_certificados'),
            'ingreso_bruto'      => $reporteMensual->sum('monto_dia'),
            'total_comisiones'   => $reporteMensual->sum('comision_pos'),
            'ingresos_operativos' => $reporteMensual->sum('saldo_dia'),
            'egresos_mensuales'   => $egresosMensuales->sum('monto'),
            'utilidad_real'       => $reporteMensual->sum('saldo_dia') - $egresosMensuales->sum('monto'),
        ];

        return view('livewire.reportes-inspecciones-mensual', [
            'reporte' => $reporteMensual,
            'egresosMensuales' => $egresosMensuales,
            'balance' => $balance,
            'nombreMes' => $nombreMes,
            'anio' => $anio,
        ]);
    }

    /*public function render()
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
    }*/
}

