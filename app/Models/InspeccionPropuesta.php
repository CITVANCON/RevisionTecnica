<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    // Relación con InspeccionFoto
    public function fotos()
    {
        //return $this->hasMany(InspeccionFoto::class);
        return $this->hasMany(InspeccionFoto::class, 'inspeccion_propuesta_id');
    }



    //SCOPES PARA FILTROS

    /*public function scopePropuesta(Builder $query, string $search)
    {
        return $search
            ? $query->where('num_propuesta', 'like', '%' . $search . '%')
            : $query;
    }*/

    public function scopePropuesta(Builder $query, ?string $search): Builder
    {
        return $search
            ? $query->where('num_propuesta', 'like', '%' . $search . '%')
            : $query;
    }

    public function scopeEstado(Builder $query, ?string $search): Builder
    {
        return $search
            ? $query->where('estado', $search)
            : $query;
    }
}
