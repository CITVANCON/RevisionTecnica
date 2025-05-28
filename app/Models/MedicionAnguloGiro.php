<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionAnguloGiro extends Model
{
    use HasFactory;

    protected $table = 'mediciones_anguloGiro';

    protected $fillable = [
        'eje1AngIzquierda1', 'eje1AngIzquierda2', 'eje1AngDerecha1', 'eje1AngDerecha2', 'eje1AngDifIzquierda', 'eje1AngDifDerecha',
        'eje2AngIzquierda1', 'eje2AngIzquierda2', 'eje2AngDerecha1', 'eje2AngDerecha2', 'eje2AngDifIzquierda', 'eje2AngDifDerecha',
        'eje3AngIzquierda1', 'eje3AngIzquierda2', 'eje3AngDerecha1', 'eje3AngDerecha2', 'eje3AngDifIzquierda', 'eje3AngDifDerecha',
        'eje4AngIzquierda1', 'eje4AngIzquierda2', 'eje4AngDerecha1', 'eje4AngDerecha2', 'eje4AngDifIzquierda', 'eje4AngDifDerecha',
        'eje5AngIzquierda1', 'eje5AngIzquierda2', 'eje5AngDerecha1', 'eje5AngDerecha2', 'eje5AngDifIzquierda', 'eje5AngDifDerecha',
        'inspeccion_propuesta_id',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class);
    }
}
