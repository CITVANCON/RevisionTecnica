<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DetallePago extends Model
{
    protected $table = 'detalles_pagos';

    protected $fillable = [
        'pagoable_id',
        'pagoable_type',
        'metodo_pago',
        'monto',
        'nro_referencia',
        'fecha_pago',
        'user_id'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    //Obtener el modelo perteneciente (InspeccionMaestra o InspeccionExtra).
    public function pagoable(): MorphTo
    {
        return $this->morphTo();
    }

    
    //Relación con el usuario que registró el pago.
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
