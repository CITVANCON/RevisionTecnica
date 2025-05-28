<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoComplementaria extends Model
{
    use HasFactory;

    protected $table = 'tipocomplementaria';

    protected $fillable = [
        'identificacion',
        'descripcionTipo',
        'denominacionCertificado',
        'peligroso',
        'descripcionResiduoPeligroso',
        'modalidad',
        'descripcionModalidad',
        'ambito',
        'descripcionAmbito',
        'leyenda1',
        'leyenda2',
        'leyenda3',
        'leyenda4'
    ];

    public function complementarias()
    {
        return $this->hasMany(InspeccionComplementaria::class, 'idTipoComplementaria');
    }
}
