<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoServicioExtra extends Model
{
    protected $table = 'tipos_servicios_extras';

    protected $fillable = [
        'nombre_servicio', 'descripcion', 'estado'
    ];

    public function inspecciones()
    {
        return $this->hasMany(InspeccionExtra::class);
    }
}
