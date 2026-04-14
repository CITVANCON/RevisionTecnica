<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeccionExtra extends Model
{
    protected $table = 'inspecciones_extras';

    protected $fillable = [
        'cliente_id', 'vehiculo_id', 'tipo_servicio_id', 'numero_certificado',
        'fecha_inspeccion', 'hora_inspeccion', 'vigencia_meses', 'proxima_inspeccion',
        'metodo_pago', 'nro_comprobante', 'comision_monto', 'observaciones', 'resultado_final', 'usuario_id'
    ];

    // Relaciones
    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoServicio() {
        return $this->belongsTo(TipoServicioExtra::class, 'tipo_servicio_id');
    }

    public function vehiculo() {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relaciones con los detalles
    public function detalleHermeticidad() {
        return $this->hasOne(DetalleHermeticidad::class, 'inspeccion_extra_id');
    }

    public function detalleOpacidad() {
        return $this->hasOne(DetalleOpacidad::class, 'inspeccion_extra_id');
    }
}
