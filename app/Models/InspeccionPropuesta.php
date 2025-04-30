<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionPropuesta extends Model
{
    use HasFactory;

    protected $table = 'inspeccion_propuesta';

    protected $fillable = [
        'num_propuesta',
        'fecha_creacion',
        'estado',
    ];

    // Relación con InspeccionVehiculo
    public function vehiculo()
    {
        return $this->hasOne(InspeccionVehiculo::class);
    }

    // Relación con InspeccionServicioAmbito
    public function servicioAmbito()
    {
        return $this->hasOne(InspeccionServicioAmbito::class);
    }

    // Relación con InspeccionFinalizada
    public function finalizada()
    {
        return $this->hasOne(InspeccionFinalizada::class);
    }

    // Relación con InspeccionAseguradora
    public function aseguradora()
    {
        return $this->hasOne(InspeccionAseguradora::class);
    }

    // Relación con medicion frenos
    public function medicionFrenos()
    {
        return $this->hasOne(MedicionFreno::class);
    }

    // Relación con medicion alineador
    public function medicionAlineador()
    {
        return $this->hasOne(MedicionAlineador::class);
    }

    // Relación con medicion luces
    public function medicionLuz()
    {
        return $this->hasOne(MedicionLuz::class);
    }


}
