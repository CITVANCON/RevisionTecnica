<?php

namespace App\Livewire;

use App\Models\MedicionAnguloGiro;
use Livewire\Component;

class FormMedicionesAngulogiro extends Component
{
    public $idPropuesta;
    public $eje1AngIzquierda1, $eje1AngIzquierda2, $eje1AngDerecha1, $eje1AngDerecha2, $eje1AngDifIzquierda, $eje1AngDifDerecha;
    public $eje2AngIzquierda1, $eje2AngIzquierda2, $eje2AngDerecha1, $eje2AngDerecha2, $eje2AngDifIzquierda, $eje2AngDifDerecha;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;

        $medicion = MedicionAnguloGiro::where('inspeccion_propuesta_id', $idPropuesta)->first();
        if ($medicion) {
            $this->eje1AngIzquierda1 = $medicion->eje1AngIzquierda1;
            $this->eje1AngIzquierda2 = $medicion->eje1AngIzquierda2;
            $this->eje1AngDerecha1 = $medicion->eje1AngDerecha1;
            $this->eje1AngDerecha2 = $medicion->eje1AngDerecha2;
            $this->eje1AngDifIzquierda = $medicion->eje1AngDifIzquierda;
            $this->eje1AngDifDerecha = $medicion->eje1AngDifDerecha;
            $this->eje2AngIzquierda1 = $medicion->eje2AngIzquierda1;
            $this->eje2AngIzquierda2 = $medicion->eje2AngIzquierda2;
            $this->eje2AngDerecha1 = $medicion->eje2AngDerecha1;
            $this->eje2AngDerecha2 = $medicion->eje2AngDerecha2;
            $this->eje2AngDifIzquierda = $medicion->eje2AngDifIzquierda;
            $this->eje2AngDifDerecha = $medicion->eje2AngDifDerecha;
        }
    }

    public function guardar()
    {
        $this->validate([
            'eje1AngIzquierda1' => 'required|numeric|min:0',
            'eje1AngIzquierda2' => 'required|numeric|min:0',
            'eje1AngDerecha1' => 'required|numeric|min:0',
            'eje1AngDerecha2' => 'required|numeric|min:0',
            'eje1AngDifIzquierda' => 'required|numeric|min:0',
            'eje1AngDifDerecha' => 'required|numeric|min:0',
            'eje2AngIzquierda1' => 'required|numeric|min:0',
            'eje2AngIzquierda2' => 'required|numeric|min:0',
            'eje2AngDerecha1' => 'required|numeric|min:0',
            'eje2AngDerecha2' => 'required|numeric|min:0',
            'eje2AngDifIzquierda' => 'required|numeric|min:0',
            'eje2AngDifDerecha' => 'required|numeric|min:0',
        ]);

        $medicion = MedicionAnguloGiro::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if ($medicion) {
            // Actualizar
            $medicion->update([
                'eje1AngIzquierda1' => $this->eje1AngIzquierda1,
                'eje1AngIzquierda2' => $this->eje1AngIzquierda2,
                'eje1AngDerecha1' => $this->eje1AngDerecha1,
                'eje1AngDerecha2' => $this->eje1AngDerecha2,
                'eje1AngDifIzquierda' => $this->eje1AngDifIzquierda,
                'eje1AngDifDerecha' => $this->eje1AngDifDerecha,
                'eje2AngIzquierda1' => $this->eje2AngIzquierda1,
                'eje2AngIzquierda2' => $this->eje2AngIzquierda2,
                'eje2AngDerecha1' => $this->eje2AngDerecha1,
                'eje2AngDerecha2' => $this->eje2AngDerecha2,
                'eje2AngDifIzquierda' => $this->eje2AngDifIzquierda,
                'eje2AngDifDerecha' => $this->eje2AngDifDerecha,
            ]);
        } else {
            // Crear nuevo
            MedicionAnguloGiro::create([
                'inspeccion_propuesta_id' => $this->idPropuesta,
                'eje1AngIzquierda1' => $this->eje1AngIzquierda1,
                'eje1AngIzquierda2' => $this->eje1AngIzquierda2,
                'eje1AngDerecha1' => $this->eje1AngDerecha1,
                'eje1AngDerecha2' => $this->eje1AngDerecha2,
                'eje1AngDifIzquierda' => $this->eje1AngDifIzquierda,
                'eje1AngDifDerecha' => $this->eje1AngDifDerecha,
                'eje2AngIzquierda1' => $this->eje2AngIzquierda1,
                'eje2AngIzquierda2' => $this->eje2AngIzquierda2,
                'eje2AngDerecha1' => $this->eje2AngDerecha1,
                'eje2AngDerecha2' => $this->eje2AngDerecha2,
                'eje2AngDifIzquierda' => $this->eje2AngDifIzquierda,
                'eje2AngDifDerecha' => $this->eje2AngDifDerecha,
            ]);
        }

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");
    }


    public function render()
    {
        return view('livewire.form-mediciones-angulogiro');
    }
}
