<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionFoto extends Model
{
    use HasFactory;

    protected $table = 'inspeccion_fotos';

    protected $fillable = [
        'inspeccion_propuesta_id',
        'tipo_foto', // enum('Izquierda', 'Centro', 'Derecha')
        'nombre',
        'ruta',
        'extension',
        'estado',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class, 'inspeccion_propuesta_id');
    }
}
