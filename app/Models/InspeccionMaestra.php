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
        'kilometraje', // campo nuevo
        'peso_bruto_v', // campo nuevo
        'peso_neto_v', // campo nuevo
        'carga_util_v', // campo nuevo
        'nro_asientos_v', // campo nuevo
        'nro_pasajeros_v', // campo nuevo
        'nro_ejes_v', // campo nuevo
        'nro_ruedas_v', // campo nuevo
        'nro_motor_v', // campo nuevo
        'nro_vin_v', // campo nuevo
        'id_inspeccion_local',
        'fecha_inspeccion',
        'fecha_vencimiento', // campo nuevo
        'hora_inicio',
        'hora_fin',
        'resultado_estado',
        'estado_inspeccion', // campo nuevo, Espera, Evaluación, Finalizada, Anulada
        'es_reinspeccion',
        'numero_reinspeccion',
        'monto_total',
        'tipo_atencion',
        'tipo_inspeccion', // campo nuevo
        'numero_certificado_mtc',
        'serie_certificado',
        'correlativo_certificado',
        'propietario_nombre', // campo nuevo
        'propietario_documento', // campo nuevo
        'propietario_celular', // campo nuevo
        'fecha_anulacion',
        'nivel_defecto', // campo nuevo
        'codigos_defectos', // campo nuevo

        // informacion que se trabaja desde el sistema web
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
