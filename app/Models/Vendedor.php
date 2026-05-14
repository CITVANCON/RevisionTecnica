<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = [
        'nombre', 
        'documento', 
        'celular', 
        'activo'
    ];

    // Relación con Inspecciones Maestras
    public function inspeccionesMaestras()
    {
        return $this->hasMany(InspeccionMaestra::class, 'vendedor_id');
    }

    // Relación con Inspecciones Extras
    public function inspeccionesExtras()
    {
        return $this->hasMany(InspeccionExtra::class, 'vendedor_id');
    }
}
