<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
class InspeccionAseguradora extends Model
{
    use HasFactory;

    protected $table = 'inspeccion_aseguradora';

    protected $fillable = [
        'idAseguradora',
        'inspeccion_propuesta_id',
        'tipopoliza',
        'num_poliza',
        'fechaInicio',
        'fechaFin'
    ];

    public function aseguradora()
    {
        return $this->belongsTo(Aseguradora::class, 'idAseguradora');
    }

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class, 'inspeccion_propuesta_id');
    }
}
