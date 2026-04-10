<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleHermeticidad extends Model
{
    protected $table = 'detalle_hermeticidad';
    public $timestamps = false; // Como es una tabla de extensión, a veces no los necesitas

    protected $fillable = [
        'inspeccion_extra_id', 'tapa', 'compuerta', 'tolva', 'sellos', 
        'bisagras', 'pistones', 'mangueras', 'remaches', 'tiempo_prueba',
        'cant_bisagras', 'cant_pistones', 'cant_mangueras', 'cant_remaches',
        'faltas_bisagras', 'faltas_pistones', 'faltas_mangueras', 'faltas_remaches'
    ];

    // Relación con InspeccionExtra
    public function inspeccionExtra() {
        return $this->belongsTo(InspeccionExtra::class, 'inspeccion_extra_id');
    }
}
