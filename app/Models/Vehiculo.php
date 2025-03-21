<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        'placa', 'propietario', 'categoria', 'marca', 'modelo', 'anio_fabricacion',
        'kilometraje', 'combustible', 'vin_serie', 'numero_motor', 'carroceria',
        'marca_carroceria', 'ejes_ruedas', 'asientos_pasajeros', 'largo', 'ancho',
        'alto', 'color', 'peso_neto', 'peso_bruto', 'peso_util'
    ];

    public function inspecciones()
    {
        return $this->hasMany(Inspeccion::class);
    }

    public function historialInspecciones()
    {
        return $this->hasMany(HistorialInspeccion::class);
    }
}
