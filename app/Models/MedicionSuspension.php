<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionSuspension extends Model
{
    use HasFactory;

    protected $table = 'mediciones_suspension';

    protected $fillable = [
        'inspeccion_propuesta_id',

        'suspensionDelanteraIzquierda',
        'suspensionDelanteraDerecha',
        'suspensionDelanteraDesviacion',
        'suspensionDelanteraResultado',

        'suspensionPosteriorIzquierda',
        'suspensionPosteriorDerecha',
        'suspensionPosteriorDesviacion',
        'suspensionPosteriorResultado',
        
        'suspensionResultadoFinal',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class);
    }
}
