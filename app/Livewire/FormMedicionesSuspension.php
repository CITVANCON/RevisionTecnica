<?php

namespace App\Livewire;

use App\Models\MedicionSuspension;
use Livewire\Component;

class FormMedicionesSuspension extends Component
{
    public $idPropuesta;
    public $suspensionDelanteraIzquierda, $suspensionDelanteraDerecha, $suspensionDelanteraDesviacion, $suspensionDelanteraResultado;
    public $suspensionPosteriorIzquierda, $suspensionPosteriorDerecha, $suspensionPosteriorDesviacion, $suspensionPosteriorResultado;
    public $suspensionResultadoFinal;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;

        $medicion = MedicionSuspension::where('inspeccion_propuesta_id', $idPropuesta)->first();
        if ($medicion) {
            $this->suspensionDelanteraIzquierda = $medicion->suspensionDelanteraIzquierda;
            $this->suspensionDelanteraDerecha = $medicion->suspensionDelanteraDerecha;
            $this->suspensionDelanteraDesviacion = $medicion->suspensionDelanteraDesviacion;
            $this->suspensionDelanteraResultado = $medicion->suspensionDelanteraResultado;
            $this->suspensionPosteriorIzquierda = $medicion->suspensionPosteriorIzquierda;
            $this->suspensionPosteriorDerecha = $medicion->suspensionPosteriorDerecha;
            $this->suspensionPosteriorDesviacion = $medicion->suspensionPosteriorDesviacion;
            $this->suspensionPosteriorResultado = $medicion->suspensionPosteriorResultado;
            $this->suspensionResultadoFinal = $medicion->suspensionResultadoFinal;
        }
    }

    public function guardar()
    {
        $this->validate([
            'suspensionDelanteraIzquierda' => 'required|numeric|min:0',
            'suspensionDelanteraDerecha' => 'required|numeric|min:0',
            'suspensionDelanteraDesviacion' => 'required|numeric|min:0',
            'suspensionDelanteraResultado' => 'required|string|in:APROBADO,DESAPROBADO',

            'suspensionPosteriorIzquierda' => 'required|numeric|min:0',
            'suspensionPosteriorDerecha' => 'required|numeric|min:0',
            'suspensionPosteriorDesviacion' => 'required|numeric|min:0',
            'suspensionPosteriorResultado' => 'required|string|in:APROBADO,DESAPROBADO',

            //'suspensionResultadoFinal' => 'required|string|in:APROBADO,DESAPROBADO',
        ]);

        $medicion = MedicionSuspension::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if ($medicion) {
            // Actualizar
            $medicion->update([
                'suspensionDelanteraIzquierda' => $this->suspensionDelanteraIzquierda,
                'suspensionDelanteraDerecha' => $this->suspensionDelanteraDerecha,
                'suspensionDelanteraDesviacion' => $this->suspensionDelanteraDesviacion,
                'suspensionDelanteraResultado' => $this->suspensionDelanteraResultado,
                'suspensionPosteriorIzquierda' => $this->suspensionPosteriorIzquierda,
                'suspensionPosteriorDerecha' => $this->suspensionPosteriorDerecha,
                'suspensionPosteriorDesviacion' => $this->suspensionPosteriorDesviacion,
                'suspensionPosteriorResultado' => $this->suspensionPosteriorResultado,
                'suspensionResultadoFinal' => $this->suspensionResultadoFinal,
            ]);
        } else {
            // Crear nuevo
            MedicionSuspension::create([
                'inspeccion_propuesta_id' => $this->idPropuesta,
                'suspensionDelanteraIzquierda' => $this->suspensionDelanteraIzquierda,
                'suspensionDelanteraDerecha' => $this->suspensionDelanteraDerecha,
                'suspensionDelanteraDesviacion' => $this->suspensionDelanteraDesviacion,
                'suspensionDelanteraResultado' => $this->suspensionDelanteraResultado,
                'suspensionPosteriorIzquierda' => $this->suspensionPosteriorIzquierda,
                'suspensionPosteriorDerecha' => $this->suspensionPosteriorDerecha,
                'suspensionPosteriorDesviacion' => $this->suspensionPosteriorDesviacion,
                'suspensionPosteriorResultado' => $this->suspensionPosteriorResultado,
                'suspensionResultadoFinal' => $this->suspensionResultadoFinal,
            ]);
        }

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");
    }

    public function render()
    {
        return view('livewire.form-mediciones-suspension');
    }
}
