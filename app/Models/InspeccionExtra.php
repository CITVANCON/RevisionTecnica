<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeccionExtra extends Model
{
    protected $table = 'inspecciones_extras';

    protected $fillable = [
        'cliente_id', 'vehiculo_id', 'tipo_servicio_id', 'numero_certificado',
        'fecha_inspeccion', 'hora_inspeccion', 'vigencia_meses', 'proxima_inspeccion',
        
        'monto_total', 'metodo_pago', 'nro_comprobante', 'comision_monto', 'observaciones',
        'resultado_final', //enum('APTO', 'OBSERVADO', 'NO APTO', 'APROBADO', 'DESAPROBADO')
        'usuario_id', 'estado'
    ];

    // Esto hace que url_certificado esté siempre disponible, incluso en Livewire
    protected $appends = ['url_certificado'];

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

    public function pagos()
    {
        // Esto permite que ambos modelos usen la misma tabla de pagos
        return $this->morphMany(DetallePago::class, 'pagoable');
    }

    // Atributo para verificar el saldo pendiente
    public function getSaldoPendienteAttribute()
    {
        return $this->monto_total - $this->pagos->sum('monto');
    }

    // --- NUEVO MÉTODO PARA OBTENER LA RUTA DEL PDF ---
    public function getUrlCertificadoAttribute()
    {
        // Usamos match (PHP 8+) para determinar la ruta según el ID del servicio
        // Asumiendo: 1 = Hermeticidad, 2 = Opacidad
        return match ($this->tipo_servicio_id) {
            1 => route('pdf.hermeticidad', $this->id),
            2 => route('pdf.opacidad', $this->id),
            default => '#', // O una ruta de error si prefieres
        };
    }
}
