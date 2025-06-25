<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;
    protected $table = 'expedientes';
    protected $fillable=
    [
    'placa',
    'num_propuesta',
    'estado',
    ];

    public function Archivos(): HasMany
    {
        return $this->hasMany(Imagen::class,'idExpediente');
    }

    public function getFotosAttribute(){
        return $this->Archivos()->whereIn('extension',[ 'jpg','jpeg','gif','gift','bimp','tif','tiff'])->get();
    }
    

    public function scopePlacaOcertificado(Builder $query, string $search): void
    {   
        if($search){
            $query->where('placa','like','%'.$search.'%')->orWhere('certificado','like','%'.$search.'%');
        }       
    }
}
