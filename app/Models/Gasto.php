<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'egresos';

    protected $fillable = [
        'fecha',
        'descripcion',
        'monto',
        'tipo_egreso',      // 'DIARIO' o 'MENSUAL'
        'categoria_gasto',  // 'Planilla', 'Servicios', 'Mantenimiento', etc.
        'metodo_pago',     // 'EFECTIVO', 'TRANSFERENCIA', etc.
        'id_usuario'
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    // Scope para filtrar gastos operativos del día (Reporte 1)
    public function scopeDiarios($query, $fecha)
    {
        return $query->where('fecha', $fecha)
                     ->where('tipo_egreso', 'DIARIO');
    }

    // Scope para el reporte de auditoría mensual (Reporte 2)
    public function scopeMensuales($query, $mes, $anio)
    {
        return $query->whereMonth('fecha', $mes)
                     ->whereYear('fecha', $anio);
    }
}
