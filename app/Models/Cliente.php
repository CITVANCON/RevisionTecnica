<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'tipo_cliente', 'nombre_razon_social', 'tipo_documento', 
        'numero_documento', 'direccion', 'telefono', 'email'
    ];

    public function inspecciones()
    {
        return $this->hasMany(InspeccionExtra::class);
    }
}
