<?php

namespace App\Livewire;

use App\Models\MedicionGases;
use Livewire\Component;

class FormMedicionesGases extends Component
{
    public $idPropuesta;
    public $gasesTemperaturaAceite, $gasesRPM, $gasesOpacidad, $gasesCORalenti, $gasesCOCO2Ralenti, $gasesHCRalenti;
    public $gasesCOAcelerado, $gasesCOCO2Acelerado, $gasesHCAcelerado, $gasesResultado;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;       

        $medicion = MedicionGases::where('inspeccion_propuesta_id', $idPropuesta)->first();
        if ($medicion) {
            $this->gasesTemperaturaAceite = $medicion->gasesTemperaturaAceite;
            $this->gasesRPM = $medicion->gasesRPM;
            $this->gasesOpacidad = $medicion->gasesOpacidad;
            $this->gasesCORalenti = $medicion->gasesCORalenti;
            $this->gasesCOCO2Ralenti = $medicion->gasesCOCO2Ralenti;
            $this->gasesHCRalenti = $medicion->gasesHCRalenti;
            $this->gasesCOAcelerado = $medicion->gasesCOAcelerado;
            $this->gasesCOCO2Acelerado = $medicion->gasesCOCO2Acelerado;
            $this->gasesHCAcelerado = $medicion->gasesHCAcelerado;
            $this->gasesResultado = $medicion->gasesResultado;
        }
    }

    public function guardar()
    {
        $this->validate([
            'gasesTemperaturaAceite' => 'required|numeric|min:0',
            'gasesRPM' => 'required|numeric|min:0',
            'gasesOpacidad' => 'required|numeric|min:0',
            'gasesCORalenti' => 'required|numeric|min:0',
            'gasesCOCO2Ralenti' => 'required|numeric|min:0',
            'gasesHCRalenti' => 'required|numeric|min:0',
            'gasesCOAcelerado' => 'required|numeric|min:0',
            'gasesCOCO2Acelerado' => 'required|numeric|min:0',
            'gasesHCAcelerado' => 'required|numeric|min:0',
            'gasesResultado' => 'required|string|in:APROBADO,DESAPROBADO',
        ]);

        $medicion = MedicionGases::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if ($medicion) {
            // Actualizar
            $medicion->update([
                'gasesTemperaturaAceite' => $this->gasesTemperaturaAceite,
                'gasesRPM' => $this->gasesRPM,
                'gasesOpacidad' => $this->gasesOpacidad,
                'gasesCORalenti' => $this->gasesCORalenti,
                'gasesCOCO2Ralenti' => $this->gasesCOCO2Ralenti,
                'gasesHCRalenti' => $this->gasesHCRalenti,
                'gasesCOAcelerado' => $this->gasesCOAcelerado,
                'gasesCOCO2Acelerado' => $this->gasesCOCO2Acelerado,
                'gasesHCAcelerado' => $this->gasesHCAcelerado,
                'gasesResultado' => $this->gasesResultado,
            ]);
        } else {
            // Crear nuevo
            MedicionGases::create([
                'inspeccion_propuesta_id' => $this->idPropuesta,
                'gasesTemperaturaAceite' => $this->gasesTemperaturaAceite,
                'gasesRPM' => $this->gasesRPM,
                'gasesOpacidad' => $this->gasesOpacidad,
                'gasesCORalenti' => $this->gasesCORalenti,
                'gasesCOCO2Ralenti' => $this->gasesCOCO2Ralenti,
                'gasesHCRalenti' => $this->gasesHCRalenti,
                'gasesCOAcelerado' => $this->gasesCOAcelerado,
                'gasesCOCO2Acelerado' => $this->gasesCOCO2Acelerado,
                'gasesHCAcelerado' => $this->gasesHCAcelerado,
                'gasesResultado' => $this->gasesResultado,
            ]);
        }

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");
    }

    public function render()
    {
        return view('livewire.form-mediciones-gases');
    }
}
