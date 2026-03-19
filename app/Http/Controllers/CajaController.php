<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\InspeccionMaestra;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function calcularCierreDiario($fecha)
{
    // 1. Sumar ingresos que fueron pagados solo en EFECTIVO
    // Filtramos por la fecha y el método de pago que agregaremos
    $ingresosEfectivo = InspeccionMaestra::where('fecha_inspeccion', $fecha)
        ->where('metodo_pago', 'EFECTIVO')
        ->sum('monto_total');

    // 2. Sumar gastos operativos del día (Tipo 'DIARIO')
    // Usamos el scope que creamos en el modelo Gasto
    $gastosDiarios = Gasto::where('fecha', $fecha)
        ->where('tipo_egreso', 'DIARIO')
        ->sum('monto');

    // 3. Calcular el saldo físico que debe haber en caja
    $efectivoADepositar = $ingresosEfectivo - $gastosDiarios;

    return [
        'ingresos_efectivo' => $ingresosEfectivo,
        'gastos_operativos' => $gastosDiarios,
        'entrega_efectivo' => $efectivoADepositar
    ];
}
}
