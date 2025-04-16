<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionFinalizada extends Model
{
    use HasFactory;

    protected $table = 'inspeccion_finalizada';

    protected $fillable = [
        'inspeccion_propuesta_id',
        'num_inspeccion',
        'idVehiculo',
        'idClase',
        'idSubclase',
        'num_certificado',
        'resultado',
        'fecha_proxima_inspeccion',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class, 'inspeccion_propuesta_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'idVehiculo');
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class, 'idClase');
    }

    public function subclase()
    {
        return $this->belongsTo(Subclase::class, 'idSubclase');
    }
}
