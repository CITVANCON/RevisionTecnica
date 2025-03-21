<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspeccion extends Model
{
    use HasFactory;

    protected $fillable = ['vehiculo_id', 'fecha', 'resultado', 'observaciones'];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function detalles()
    {
        return $this->hasMany(InspeccionDetalle::class);
    }

    public function fotos()
    {
        return $this->hasMany(InspeccionFoto::class);
    }

    public function certificado()
    {
        return $this->hasOne(Certificado::class);
    }
}
