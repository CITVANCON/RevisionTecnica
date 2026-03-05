<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeccionMaestra extends Model
{
    protected $table = 'inspecciones_maestras';

    protected $fillable = [
        'placa_vehiculo',
        'categoria_vehiculo',
        'id_inspeccion_local',
        'fecha_inspeccion',
        'hora_inicio',
        'hora_fin',
        'resultado_estado',
        'numero_certificado_mtc',
        'serie_certificado',
        'correlativo_certificado',
        'fecha_anulacion'
    ];
}
