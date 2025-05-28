<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionSonometro extends Model
{
    use HasFactory;

    protected $table = 'mediciones_sonometro';

    protected $fillable = [
        'inspeccion_propuesta_id',
        'sonometroMedida',
        'sonometroResultado',        
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class);
    }
}
