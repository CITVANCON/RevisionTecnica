<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipomaquinaria extends Model
{
    use HasFactory;

    protected $table = 'tipomaquinaria';

    protected $fillable = ['descripcion'];

    // Relación uno a muchos
    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class, 'idTipomaquinaria');
    }
}
