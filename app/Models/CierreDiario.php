<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CierreDiario extends Model
{
    use HasFactory;

    protected $table = 'cierres_diarios';

    // Indicamos que la llave primaria no es 'id' y no es autoincremental
    protected $primaryKey = 'fecha';
    public $incrementing = false;
    protected $keyType = 'string'; // Las fechas se manejan como string en la PK

    protected $fillable = [
        'fecha',
        'efectivo_esperado', // Suma sistema: Ventas - Gastos Diarios
        'efectivo_real', // Monto físicamente contado/depositado
        'pos_esperado', // Suma sistema: Pagos con tarjeta
        'pos_real', // Monto según reporte Izipay/Niubiz
        // 'diferencia', // Calculada: efectivo_real - efectivo_esperado

        'comision_pos',    // <-- Nuevo: El gasto de comisión
        'monto_neto_pos',  // <-- Nuevo: Lo que llega al banco

        'observacion', 
        'auditado_por', // ID del usuario que realizó la auditoría
        'estado' // pendiente, cuadrado, observado
    ];

    /*protected $casts = [
        'fecha' => 'date', // Aunque sea PK string, esto ayuda a Carbon
        'efectivo_esperado' => 'decimal:2',
        'efectivo_real' => 'decimal:2',
        'pos_esperado' => 'decimal:2',
        'pos_real' => 'decimal:2',
        'comision_pos' => 'decimal:2',
        'monto_neto_pos' => 'decimal:2',
    ];*/

    // Relación con el usuario que auditó
    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditado_por');
    }
}
