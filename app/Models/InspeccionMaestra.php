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
        'peso_bruto_v',
        'peso_neto_v',
        'carga_util_v',
        'nro_asientos_v',
        'nro_pasajeros_v',
        'nro_ejes_v',
        'nro_ruedas_v',
        'nro_motor_v',
        'nro_vin_v',
        'id_inspeccion_local',
        'fecha_inspeccion',
        'fecha_vencimiento',
        'hora_inicio',
        'hora_fin',
        'resultado_estado', // A = Aprobado, D = Desaprobado
        'estado_inspeccion', // Espera, Evaluación, Finalizada, Anulada
        'es_reinspeccion',
        'numero_reinspeccion',
        'monto_total',
        'tipo_atencion',
        'tipo_inspeccion',
        'numero_certificado_mtc',
        'serie_certificado',
        'correlativo_certificado',
        'propietario_nombre',
        'propietario_documento',
        'propietario_celular',
        'fecha_anulacion', // null quiero decir que no ha sido anulada, si tiene fecha, entonces se anulo
        'nivel_defecto',
        'codigos_defectos',

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
