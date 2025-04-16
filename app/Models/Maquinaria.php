<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    use HasFactory;

    protected $table = 'maquinaria';

    protected $fillable = [
        'idTipomaquinaria',
        'numSerie',
        'identificacionLinea',
        'identificacionTipo_linea',
    ];

    // Relación muchos a uno
    public function tipoMaquinaria()
    {
        return $this->belongsTo(Tipomaquinaria::class, 'idTipomaquinaria');
    }
}
