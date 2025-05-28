<?php

namespace App\Livewire;

use App\Models\InspeccionVehiculo;
use App\Models\MedicionAlineador;
use Livewire\Component;

class FormMedicionesAlineador extends Component
{
    public $idPropuesta;
    // Desviacion
    public $eje1ADesviacion, $eje2ADesviacion;
    // Desviacion resultado
    public $eje1AResultado, $eje2AResultado;

    // Rueda medida izquierda
    public $eje1RMedidaIzquierda, $eje2RMedidaIzquierda;
    // Rueda medida derecha
    public $eje1RMedidaDerecha, $eje2RMedidaDerecha;
    // Rueda medida resultado
    public $eje1RMedidaResultado, $eje2RMedidaResultado;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;

        $medicion = MedicionAlineador::where('inspeccion_propuesta_id', $idPropuesta)->first();
        if ($medicion) {
            // Desviacion
            $this->eje1ADesviacion = $medicion->eje1ADesviacion;
            $this->eje2ADesviacion = $medicion->eje2ADesviacion;
            // Desviacion resultado
            $this->eje1AResultado = $medicion->eje1AResultado;
            $this->eje2AResultado = $medicion->eje2AResultado;
            // Rueda medida izquierda
            $this->eje1RMedidaIzquierda = $medicion->eje1RMedidaIzquierda;
            $this->eje2RMedidaIzquierda = $medicion->eje2RMedidaIzquierda;
            // Rueda medida derecha
            $this->eje1RMedidaDerecha = $medicion->eje1RMedidaDerecha;
            $this->eje2RMedidaDerecha = $medicion->eje2RMedidaDerecha;
            // Rueda medida resultado
            $this->eje1RMedidaResultado = $medicion->eje1RMedidaResultado;
            $this->eje2RMedidaResultado = $medicion->eje2RMedidaResultado;
        }
    }    

    public function updated($propertyName, $value)
    {
        // Verifica si el nombre de la propiedad termina en 'Desviacion', lo cual aplica para eje1ADesviacion y eje2ADesviacion.
        if (str_ends_with($propertyName, 'Desviacion')) {
            // Luego transforma, por ejemplo, eje1ADesviacion a eje1AResultado usando str_replace.
            $resultadoProperty = str_replace('Desviacion', 'Resultado', $propertyName);
            // evalúa si el valor es menor o igual a 8 y asigna "APROBADO" o "DESAPROBADO" al campo correspondiente.
            $this->$resultadoProperty = ($value <= 8) ? 'APROBADO' : 'DESAPROBADO';
        }
    }

    public function updatedEje1RMedidaIzquierda($value)
    {
        $this->evaluarResultadoMedidaRueda('eje1');
    }
    public function updatedEje1RMedidaDerecha($value)
    {
        $this->evaluarResultadoMedidaRueda('eje1');
    }

    public function updatedEje2RMedidaIzquierda($value)
    {
        $this->evaluarResultadoMedidaRueda('eje2');
    }
    public function updatedEje2RMedidaDerecha($value)
    {
        $this->evaluarResultadoMedidaRueda('eje2');
    }

    private function evaluarResultadoMedidaRueda($eje)
    {
        $inspeccion = InspeccionVehiculo::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if (!$inspeccion || !$inspeccion->categoria) {
            $this->{$eje . 'RMedidaResultado'} = 'DESAPROBADO';
            return;
        }

        $categoria = $inspeccion->categoria->identificacion;

        $categoriasMin16 = ['M1', 'M2', 'N1', 'N2', 'O1', 'O2'];
        $categoriasMin20 = ['M3', 'N3', 'O3', 'O4'];

        $minimo = in_array($categoria, $categoriasMin16) ? 1.6 : (in_array($categoria, $categoriasMin20) ? 2.0 : null);

        if (is_null($minimo)) {
            $this->{$eje . 'RMedidaResultado'} = 'DESAPROBADO';
            return;
        }

        $izquierda = $this->{$eje . 'RMedidaIzquierda'};
        $derecha = $this->{$eje . 'RMedidaDerecha'};

        if (is_numeric($izquierda) && is_numeric($derecha) && $izquierda >= $minimo && $derecha >= $minimo) {
            $this->{$eje . 'RMedidaResultado'} = 'APROBADO';
        } else {
            $this->{$eje . 'RMedidaResultado'} = 'DESAPROBADO';
        }
    }

    public function guardar()
    {
        $this->validate([
            // Desviacion
            'eje1ADesviacion' => 'required|numeric|min:0',
            'eje2ADesviacion' => 'required|numeric|min:0',
            // Desviacion resultado
            'eje1AResultado' => 'required|string|in:APROBADO,DESAPROBADO',
            'eje2AResultado' => 'required|string|in:APROBADO,DESAPROBADO',
            // Rueda medida izquierda
            'eje1RMedidaIzquierda' => 'required|numeric|min:0',
            'eje2RMedidaIzquierda' => 'required|numeric|min:0',
            // Rueda medida derecha
            'eje1RMedidaDerecha' => 'required|numeric|min:0',
            'eje2RMedidaDerecha' => 'required|numeric|min:0',
            // Rueda medida resultado
            'eje1RMedidaResultado' => 'required|string|in:APROBADO,DESAPROBADO',
            'eje2RMedidaResultado' => 'required|string|in:APROBADO,DESAPROBADO',
        ]);

        $medicion = MedicionAlineador::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if ($medicion) {
            // Actualizar
            $medicion->update([
                // Desviacion
                'eje1ADesviacion' => $this->eje1ADesviacion,
                'eje2ADesviacion' => $this->eje2ADesviacion,
                // Desviacion resultado
                'eje1AResultado' => $this->eje1AResultado,
                'eje2AResultado' => $this->eje2AResultado,
                // Rueda medida izquierda
                'eje1RMedidaIzquierda' => $this->eje1RMedidaIzquierda,
                'eje2RMedidaIzquierda' => $this->eje2RMedidaIzquierda,
                // Rueda medida derecha
                'eje1RMedidaDerecha' => $this->eje1RMedidaDerecha,
                'eje2RMedidaDerecha' => $this->eje2RMedidaDerecha,
                // Rueda medida resultado
                'eje1RMedidaResultado' => $this->eje1RMedidaResultado,
                'eje2RMedidaResultado' => $this->eje2RMedidaResultado,
            ]);
        } else {
            // Crear nuevo
            MedicionAlineador::create([
                'inspeccion_propuesta_id' => $this->idPropuesta,
                // Desviacion
                'eje1ADesviacion' => $this->eje1ADesviacion,
                'eje2ADesviacion' => $this->eje2ADesviacion,
                // Desviacion resultado
                'eje1AResultado' => $this->eje1AResultado,
                'eje2AResultado' => $this->eje2AResultado,
                // Rueda medida izquierda
                'eje1RMedidaIzquierda' => $this->eje1RMedidaIzquierda,
                'eje2RMedidaIzquierda' => $this->eje2RMedidaIzquierda,
                // Rueda medida derecha
                'eje1RMedidaDerecha' => $this->eje1RMedidaDerecha,
                'eje2RMedidaDerecha' => $this->eje2RMedidaDerecha,
                // Rueda medida resultado
                'eje1RMedidaResultado' => $this->eje1RMedidaResultado,
                'eje2RMedidaResultado' => $this->eje2RMedidaResultado,
            ]);
        }

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");
    }

    public function render()
    {
        return view('livewire.form-mediciones-alineador');
    }
}


/*public function updatedEje1ADesviacion($value)
    {
        $this->eje1AResultado = ($value <= 8) ? 'APROBADO' : 'DESAPROBADO';
    }
    public function updatedEje2ADesviacion($value)
    {
        $this->eje2AResultado = ($value <= 8) ? 'APROBADO' : 'DESAPROBADO';
    }
*/

/*public function evaluarEje1RMedidaResultado()
    {
        // Obtener categoría a través de inspección_vehiculo
        $inspeccion = InspeccionVehiculo::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if (!$inspeccion || !$inspeccion->categoria) {
            $this->eje1RMedidaResultado = 'DESAPROBADO';
            return;
        }

        $categoria = $inspeccion->categoria->identificacion;

        // Define umbral mínimo según categoría
        $categoriasMin16 = ['M1', 'M2', 'N1', 'N2', 'O1', 'O2'];
        $categoriasMin20 = ['M3', 'N3', 'O3', 'O4'];

        $minimo = 0;
        if (in_array($categoria, $categoriasMin16)) {
            $minimo = 1.6;
        } elseif (in_array($categoria, $categoriasMin20)) {
            $minimo = 2.0;
        } else {
            // Si no se reconoce la categoría, marcamos como desaprobado
            $this->eje1RMedidaResultado = 'DESAPROBADO';
            return;
        }

        // Verifica si ambas medidas cumplen el mínimo
        if (
            is_numeric($this->eje1RMedidaIzquierda) &&
            is_numeric($this->eje1RMedidaDerecha) &&
            $this->eje1RMedidaIzquierda >= $minimo &&
            $this->eje1RMedidaDerecha >= $minimo
        ) {
            $this->eje1RMedidaResultado = 'APROBADO';
        } else {
            $this->eje1RMedidaResultado = 'DESAPROBADO';
        }
    }
    public function evaluarEje2RMedidaResultado()
    {
        // Obtener categoría a través de inspección_vehiculo
        $inspeccion = InspeccionVehiculo::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if (!$inspeccion || !$inspeccion->categoria) {
            $this->eje2RMedidaResultado = 'DESAPROBADO';
            return;
        }

        $categoria = $inspeccion->categoria->identificacion;

        // Define umbral mínimo según categoría
        $categoriasMin16 = ['M1', 'M2', 'N1', 'N2', 'O1', 'O2'];
        $categoriasMin20 = ['M3', 'N3', 'O3', 'O4'];

        $minimo = 0;
        if (in_array($categoria, $categoriasMin16)) {
            $minimo = 1.6;
        } elseif (in_array($categoria, $categoriasMin20)) {
            $minimo = 2.0;
        } else {
            // Si no se reconoce la categoría, marcamos como desaprobado
            $this->eje2RMedidaResultado = 'DESAPROBADO';
            return;
        }

        // Verifica si ambas medidas cumplen el mínimo
        if (
            is_numeric($this->eje2RMedidaIzquierda) &&
            is_numeric($this->eje2RMedidaDerecha) &&
            $this->eje2RMedidaIzquierda >= $minimo &&
            $this->eje2RMedidaDerecha >= $minimo
        ) {
            $this->eje2RMedidaResultado = 'APROBADO';
        } else {
            $this->eje2RMedidaResultado = 'DESAPROBADO';
        }
    }
*/