<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialInspeccion extends Model
{
    use HasFactory;
    
    protected $fillable = ['vehiculo_id', 'inspeccion_id', 'fecha', 'resultado', 'observaciones'];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function inspeccion()
    {
        return $this->belongsTo(Inspeccion::class);
    }
}
