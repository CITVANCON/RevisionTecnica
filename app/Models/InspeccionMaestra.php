<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeccionMaestra extends Model
{
    protected $table = 'inspecciones_maestras';

    protected $fillable = [
        // informacion que se alimenta desde el servidor de planta
        'placa_vehiculo',
        'categoria_vehiculo',
        'id_inspeccion_local',
        'fecha_inspeccion',
        'hora_inicio',
        'hora_fin',
        'resultado_estado',
        'es_reinspeccion',
        'numero_reinspeccion',
        'monto_total',
        'tipo_atencion',
        'numero_certificado_mtc',
        'serie_certificado',
        'correlativo_certificado',
        'fecha_anulacion',
        // informacion que se alimenta desde el sistema web
        'metodo_pago',
        'nro_comprobante',
        'nro_orden',
        'comision_monto',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inspeccion' => 'date',
        'monto_total' => 'decimal:2',
        'comision_monto' => 'decimal:2',
    ];
}
