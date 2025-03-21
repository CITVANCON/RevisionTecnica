<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionDetalle extends Model
{
    use HasFactory;

    protected $fillable = ['inspeccion_id', 'tipo', 'resultado', 'observaciones'];

    public function inspeccion()
    {
        return $this->belongsTo(Inspeccion::class);
    }
}
