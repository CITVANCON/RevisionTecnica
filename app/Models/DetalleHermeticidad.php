<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleHermeticidad extends Model
{
    protected $table = 'detalle_hermeticidad';
    // Como añadiste los campos en el SQL, activamos los timestamps
    public $timestamps = true;

    protected $fillable = [
        'inspeccion_extra_id',
        
        // TAPA
        'tapa_deformidad', 'tapa_fisura', 'tapa_oxido', 'tapa_resequedad', 'tapa_lubricacion',
        
        // COMPUERTA
        'compuerta_deformidad', 'compuerta_fisura', 'compuerta_oxido', 'compuerta_resequedad', 'compuerta_lubricacion',
        
        // TOLVA
        'tolva_deformidad', 'tolva_fisura', 'tolva_oxido', 'tolva_resequedad', 'tolva_lubricacion',
        
        // SELLOS
        'sellos_deformidad', 'sellos_fisura', 'sellos_oxido', 'sellos_resequedad', 'sellos_lubricacion',
        
        // BISAGRAS
        'bisagras_deformidad', 'bisagras_fisura', 'bisagras_oxido', 'bisagras_resequedad', 'bisagras_lubricacion',
        
        // PISTONES
        'pistones_deformidad', 'pistones_fisura', 'pistones_oxido', 'pistones_resequedad', 'pistones_lubricacion',
        
        // MANGUERAS
        'mangueras_deformidad', 'mangueras_fisura', 'mangueras_oxido', 'mangueras_resequedad', 'mangueras_lubricacion',
        
        // REMACHES
        'remaches_deformidad', 'remaches_fisura', 'remaches_oxido', 'remaches_resequedad', 'remaches_lubricacion',

        // Tiempos y Cantidades
        'tiempo_prueba',
        'cant_bisagras', 'cant_pistones', 'cant_mangueras', 'cant_remaches',
        'faltas_bisagras', 'faltas_pistones', 'faltas_mangueras', 'faltas_remaches'
    ];

    // Relación con InspeccionExtra
    public function inspeccionExtra() {
        return $this->belongsTo(InspeccionExtra::class, 'inspeccion_extra_id');
    }
}
