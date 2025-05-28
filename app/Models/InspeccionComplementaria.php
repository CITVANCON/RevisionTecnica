<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspeccionComplementaria extends Model
{
    use HasFactory;

    protected $table = 'inspeccion_complementaria';

    protected $fillable = [
        'inspeccion_propuesta_id',
        'num_propuesta',
        'num_inspeccion',
        'idVehiculo',
        'num_impreso',
        'resultado',
        'idTipoComplementaria',
        'observaciones',
        'ingenieroSupervisor',
        'foto1',
    ];

    public function complementaria()
    {
        return $this->belongsTo(TipoComplementaria::class, 'idTipoComplementaria');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'idVehiculo');
    }

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class, 'inspeccion_propuesta_id');
    }
}
