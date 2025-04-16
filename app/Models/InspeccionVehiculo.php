<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionVehiculo extends Model
{
    use HasFactory;

    protected $table = 'inspeccion_vehiculo';

    protected $fillable = [
        'inspeccion_propuesta_id',
        'num_inspeccion',
        'idVehiculo',
        'idTipovehiculo',
        'idCategoria',
        'idTipodocumento',
        'num_documento',
        'direccion',
        'celular',
        'correo',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class, 'inspeccion_propuesta_id');
    }


    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'idVehiculo');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'idTipovehiculo');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumentoIdentidad::class, 'idTipodocumento');
    }
}
