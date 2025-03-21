<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;

    protected $fillable = ['inspeccion_id', 'numero_certificado', 'fecha_emision', 'fecha_vencimiento', 'resultado'];

    public function inspeccion()
    {
        return $this->belongsTo(Inspeccion::class);
    }
}
