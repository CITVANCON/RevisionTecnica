<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionGases extends Model
{
    use HasFactory;

    protected $table = 'mediciones_gases';

    protected $fillable = [
        'inspeccion_propuesta_id',
        'gasesTemperaturaAceite',
        'gasesRPM',
        'gasesOpacidad',
        'gasesCORalenti',
        'gasesCOCO2Ralenti',
        'gasesHCRalenti',
        'gasesCOAcelerado',
        'gasesCOCO2Acelerado',
        'gasesHCAcelerado',
        'gasesResultado',        
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class);
    }
}
