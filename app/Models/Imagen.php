<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imagen extends Model
{
    use HasFactory;

    protected $table = 'imagenes';

    protected $fillable =
    [
        'nombre',
        'ruta',
        'extension',
        'idExpediente',
    ];

    public function Expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'idExpediente');
    }
}
