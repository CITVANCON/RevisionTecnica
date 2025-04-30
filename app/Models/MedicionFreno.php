<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionFreno extends Model
{
    use HasFactory;

    protected $table = 'mediciones_frenos';

    protected $fillable = [
        'inspeccion_propuesta_id',
        // Pesos
        'eje1Peso', 'eje2Peso', 'eje3Peso', 'eje4Peso', 'eje5Peso',

        // FRENO SERVICIO
        'eje1FSD', 'eje2FSD', 'eje3FSD', 'eje4FSD', 'eje5FSD',
        'eje1FSI', 'eje2FSI', 'eje3FSI', 'eje4FSI', 'eje5FSI',
        'eje1FSDesequilibrio', 'eje2FSDesequilibrio', 'eje3FSDesequilibrio', 'eje4FSDesequilibrio', 'eje5FSDesequilibrio',
        'eje1FSEficiencia', 'eje2FSEficiencia', 'eje3FSEficiencia', 'eje4FSEficiencia', 'eje5FSEficiencia',
        'eje1FSResultado', 'eje2FSResultado', 'eje3FSResultado', 'eje4FSResultado', 'eje5FSResultado',

        // FRENO ESTACIONAMIENTO
        'eje1FED', 'eje2FED', 'eje3FED', 'eje4FED', 'eje5FED',
        'eje1FEI', 'eje2FEI', 'eje3FEI', 'eje4FEI', 'eje5FEI',
        'eje1FEDesequilibrio', 'eje2FEDesequilibrio', 'eje3FEDesequilibrio', 'eje4FEDesequilibrio', 'eje5FEDesequilibrio',
        'eje1FEEficiencia', 'eje2FEEficiencia', 'eje3FEEficiencia', 'eje4FEEficiencia', 'eje5FEEficiencia',
        'eje1FEResultado', 'eje2FEResultado', 'eje3FEResultado', 'eje4FEResultado', 'eje5FEResultado',

        // FRENO EMERGENCIA
        'eje1FEMD', 'eje2FEMD', 'eje3FEMD', 'eje4FEMD', 'eje5FEMD',
        'eje1FEMI', 'eje2FEMI', 'eje3FEMI', 'eje4FEMI', 'eje5FEMI',
        'eje1FEMDesequilibrio', 'eje2FEMDesequilibrio', 'eje3FEMDesequilibrio', 'eje4FEMDesequilibrio', 'eje5FEMDesequilibrio',
        'eje1FEMEficiencia', 'eje2FEMEficiencia', 'eje3FEMEficiencia', 'eje4FEMEficiencia', 'eje5FEMEficiencia',
        'eje1FEMResultado', 'eje2FEMResultado', 'eje3FEMResultado', 'eje4FEMResultado', 'eje5FEMResultado',

        // Resultados finales
        'FSResultadoFinal', 'FEResultadoFinal', 'FEMResultadoFinal',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class, 'inspeccion_propuesta_id');
    }
}
