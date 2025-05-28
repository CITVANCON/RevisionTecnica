<?php

namespace App\Livewire;

use App\Models\MedicionFreno;
use Livewire\Component;

class FormMedicionesFrenos extends Component
{
    public $idPropuesta;
    // PESOS    
    public $eje1Peso, $eje2Peso;
    // FRENO SERVICIO
    public $eje1FSD, $eje2FSD;
    public $eje1FSI, $eje2FSI;
    public $eje1FSDesequilibrio, $eje2FSDesequilibrio;
    public $eje1FSEficiencia, $eje2FSEficiencia;
    public $eje1FSResultado, $eje2FSResultado;
    // FRENO ESTACIONAMIENTO
    public $eje1FED, $eje2FED;
    public $eje1FEI, $eje2FEI;
    public $eje1FEDesequilibrio, $eje2FEDesequilibrio;
    public $eje1FEEficiencia, $eje2FEEficiencia;
    public $eje1FEResultado, $eje2FEResultado;
    // FRENO EMERGENCIA
    public $eje1FEMD, $eje2FEMD;
    public $eje1FEMI, $eje2FEMI;
    public $eje1FEMDesequilibrio, $eje2FEMDesequilibrio;
    public $eje1FEMEficiencia, $eje2FEMEficiencia;
    public $eje1FEMResultado, $eje2FEMResultado;
    // Resultados finales
    public $FSResultadoFinal, $FEResultadoFinal, $FEMResultadoFinal;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;

        $medicion = MedicionFreno::where('inspeccion_propuesta_id', $idPropuesta)->first();
        if ($medicion) {
            // PESOS
            $this->eje1Peso = $medicion->eje1Peso;
            $this->eje2Peso = $medicion->eje2Peso;
            // FRENO SERVICIO
            $this->eje1FSD = $medicion->eje1FSD;
            $this->eje2FSD = $medicion->eje2FSD;
            $this->eje1FSI = $medicion->eje1FSI;
            $this->eje2FSI = $medicion->eje2FSI;
            $this->eje1FSDesequilibrio = $medicion->eje1FSDesequilibrio;
            $this->eje2FSDesequilibrio = $medicion->eje2FSDesequilibrio;
            $this->eje1FSEficiencia = $medicion->eje1FSEficiencia;
            $this->eje1FSResultado = $medicion->eje1FSResultado;
            $this->eje2FSResultado = $medicion->eje2FSResultado;
            // FRENO ESTACIONAMIENTO
            $this->eje2FED = $medicion->eje2FED;
            $this->eje2FEI = $medicion->eje2FEI;
            $this->eje1FEEficiencia = $medicion->eje1FEEficiencia;
            $this->eje1FEResultado = $medicion->eje1FEResultado;
            $this->eje2FEResultado = $medicion->eje2FEResultado;
            // FRENO EMERGENCIA

            // RESULTADOS FINALES
            $this->FSResultadoFinal = $medicion->FSResultadoFinal;
            $this->FEResultadoFinal = $medicion->FEResultadoFinal;
            //$this->FEMResultadoFinal = $medicion->FEMResultadoFinal;
        }
    }

    // Crea y actualiza en caso exista para MedicionFreno
    public function guardar()
    {
        $this->validate([
            // PESOS
            'eje1Peso' => 'required|numeric|min:0',
            'eje2Peso' => 'required|numeric|min:0',
            // FRENO SERVICIO
            'eje1FSD' => 'required|numeric|min:0',
            'eje2FSD' => 'required|numeric|min:0',
            'eje1FSI' => 'required|numeric|min:0',
            'eje2FSI' => 'required|numeric|min:0',
            'eje1FSDesequilibrio' => 'required|numeric|min:0',
            'eje2FSDesequilibrio' => 'required|numeric|min:0',
            'eje1FSEficiencia' => 'required|numeric|min:0',
            'eje1FSResultado' => 'nullable|string|in:APROBADO,DESAPROBADO',
            'eje2FSResultado' => 'nullable|string|in:APROBADO,DESAPROBADO',
            // FRENO ESTACIONAMIENTO
            'eje2FED' => 'required|numeric|min:0',
            'eje2FEI' => 'required|numeric|min:0',
            'eje1FEEficiencia' => 'required|numeric|min:0',            
            'eje1FEResultado' => 'nullable|string|in:APROBADO,DESAPROBADO',
            'eje2FEResultado' => 'nullable|string|in:APROBADO,DESAPROBADO',
            // RESULTADOS FINALES
            'FSResultadoFinal' => 'required|string|in:APROBADO,DESAPROBADO',
            'FEResultadoFinal' => 'required|string|in:APROBADO,DESAPROBADO',
        ]);

        $medicion = MedicionFreno::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if ($medicion) {
            // Actualizar
            $medicion->update([
                // PESOS
                'eje1Peso' => $this->eje1Peso,
                'eje2Peso' => $this->eje2Peso,
                // FRENO SERVICIO
                'eje1FSD' => $this->eje1FSD,
                'eje2FSD' => $this->eje2FSD,
                'eje1FSI' => $this->eje1FSI,
                'eje2FSI' => $this->eje2FSI,
                'eje1FSDesequilibrio' => $this->eje1FSDesequilibrio,
                'eje2FSDesequilibrio' => $this->eje2FSDesequilibrio,
                'eje1FSEficiencia' => $this->eje1FSEficiencia,
                'eje1FSResultado' => $this->eje1FSResultado,
                'eje2FSResultado' => $this->eje2FSResultado,
                // FRENO ESTACIONAMIENTO
                'eje2FED' => $this->eje2FED,
                'eje2FEI' => $this->eje2FEI,
                'eje1FEEficiencia' => $this->eje1FEEficiencia,
                'eje1FEResultado' => $this->eje1FEResultado,
                'eje2FEResultado' => $this->eje2FEResultado,
                // FRENO EMERGENCIA
                // RESULTADOS FINALES
                'FSResultadoFinal' => $this->FSResultadoFinal,
                'FEResultadoFinal' => $this->FEResultadoFinal,
                //'FEMResultadoFinal' => $this->FEMResultadoFinal,
            ]);
        } else {
            // Crear nuevo
            MedicionFreno::create([
                'inspeccion_propuesta_id' => $this->idPropuesta,
                // PESOS
                'eje1Peso' => $this->eje1Peso,
                'eje2Peso' => $this->eje2Peso,
                // FRENO SERVICIO
                'eje1FSD' => $this->eje1FSD,
                'eje2FSD' => $this->eje2FSD,
                'eje1FSI' => $this->eje1FSI,
                'eje2FSI' => $this->eje2FSI,
                'eje1FSDesequilibrio' => $this->eje1FSDesequilibrio,
                'eje2FSDesequilibrio' => $this->eje2FSDesequilibrio,
                'eje1FSEficiencia' => $this->eje1FSEficiencia,
                'eje1FSResultado' => $this->eje1FSResultado,
                'eje2FSResultado' => $this->eje2FSResultado,
                // FRENO ESTACIONAMIENTO
                'eje2FED' => $this->eje2FED,
                'eje2FEI' => $this->eje2FEI,
                'eje1FEEficiencia' => $this->eje1FEEficiencia,
                'eje1FEResultado' => $this->eje1FEResultado,
                'eje2FEResultado' => $this->eje2FEResultado,
                // FRENO EMERGENCIA
                // RESULTADOS FINALES
                'FSResultadoFinal' => $this->FSResultadoFinal,
                'FEResultadoFinal' => $this->FEResultadoFinal,
                //'FEMResultadoFinal' => $this->FEMResultadoFinal,
            ]);
        }

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");
    }

    public function render()
    {
        return view('livewire.form-mediciones-frenos');
    }
}
