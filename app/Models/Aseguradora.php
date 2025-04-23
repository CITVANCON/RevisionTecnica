<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aseguradora extends Model
{
    use HasFactory;

    protected $table = 'aseguradora';

    protected $fillable = ['descripcion'];

    /*public function inspecciones()
    {
        return $this->hasMany(InspeccionAseguradora::class, 'idAseguradora');
    }*/
}
