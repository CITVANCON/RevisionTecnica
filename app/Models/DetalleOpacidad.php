<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOpacidad extends Model
{
    protected $table = 'detalle_opacidad';
    public $timestamps = false;

    protected $fillable = [
        // Lecturas (K = Opacidad, T = Temperatura)
        'ciclo1_k', 'ciclo1_t', 
        'ciclo2_k', 'ciclo2_t', 
        'ciclo3_k', 'ciclo3_t',
        'ciclo4_k', 'ciclo4_t', 
        'promedio_k', 'limite_permitido'
    ];

    // Relación con InspeccionExtra
    public function inspeccionExtra() {
        return $this->belongsTo(InspeccionExtra::class, 'inspeccion_extra_id');
    }
}
