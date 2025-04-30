<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicionAlineador extends Model
{
    use HasFactory;

    protected $table = 'mediciones_alineador';

    protected $fillable = [
        'inspeccion_propuesta_id',
        // Desviacion
        'eje1ADesviacion', 'eje2ADesviacion', 'eje3ADesviacion', 'eje4ADesviacion', 'eje5ADesviacion',
        // Desviacion resultado
        'eje1AResultado', 'eje2AResultado', 'eje3AResultado', 'eje4AResultado', 'eje5AResultado',
        // Rueda medida izquierda
        'eje1RMedidaIzquierda', 'eje2RMedidaIzquierda', 'eje3RMedidaIzquierda', 'eje4RMedidaIzquierda', 'eje5RMedidaIzquierda',
        // Rueda medida derecha
        'eje1RMedidaDerecha', 'eje2RMedidaDerecha', 'eje3RMedidaDerecha', 'eje4RMedidaDerecha', 'eje5RMedidaDerecha',
        // Rueda medida resultado
        'eje1RMedidaResultado', 'eje2RMedidaResultado', 'eje3RMedidaResultado', 'eje4RMedidaResultado', 'eje5RMedidaResultado',
    ];

    public function propuesta()
    {
        return $this->belongsTo(InspeccionPropuesta::class);
    }
}
