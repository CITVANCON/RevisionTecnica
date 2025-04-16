<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionServicioAmbito extends Model
{
    use HasFactory;

    protected $table = 'inspeccion_servicio_ambito';

    protected $fillable = [
        'inspeccion_propuesta_id',
        'idServicio',
        'idAmbito',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class, 'inspeccion_propuesta_id');
    }


    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio');
    }

    public function ambito()
    {
        return $this->belongsTo(Ambito::class, 'idAmbito');
    }
}
