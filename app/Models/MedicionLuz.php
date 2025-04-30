<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicionLuz extends Model
{
    protected $table = 'mediciones_luces';

    protected $fillable = [
        'inspeccion_propuesta_id',
        // LUZ ALTA
        'luzAltaDerecha', 'luzAltaIzquierda', 'luzAltaAlineamiento', 'luzAltaResultado',
        // LUZ BAJA
        'luzBajaDerecha', 'luzBajaIzquierda', 'luzBajaAlineamiento', 'luzBajaResultado',
        // LUZ ALTA ADICIONAL
        'luzAltaAdicionalDerecha', 'luzAltaAdicionalIzquierda', 'luzAltaAdicionalAlineamiento', 'luzAltaAdicionalResultado',
        // LUZ NEBLINERA
        'luzNeblineraDerecha', 'luzNeblineraIzquierda', 'luzNeblineraAlineamiento', 'luzNeblineraResultado',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class);
    }
}
