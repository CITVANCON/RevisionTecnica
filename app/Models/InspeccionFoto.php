<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionFoto extends Model
{
    use HasFactory;

    protected $fillable = ['inspeccion_id', 'url_foto', 'descripcion'];

    public function inspeccion()
    {
        return $this->belongsTo(Inspeccion::class);
    }
}
