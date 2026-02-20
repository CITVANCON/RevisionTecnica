<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanillaArchivo extends Model
{
    use HasFactory;

    protected $table = 'planilla_archivos';

    protected $fillable = [
        'planilla_id',
        'tipo',
        'nombre',
        'ruta',
        'extension',
    ];

    public function planilla()
    {
        return $this->belongsTo(Planilla::class, 'planilla_id');
    }
}
