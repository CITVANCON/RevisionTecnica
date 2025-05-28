<?php

namespace App\Livewire;

use App\Models\MedicionSonometro;
use Livewire\Component;

class FormMedicionesSonometro extends Component
{
    public $idPropuesta;
    public $sonometroMedida, $sonometroResultado;

    /*public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;
    }*/

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;

        $medicion = MedicionSonometro::where('inspeccion_propuesta_id', $idPropuesta)->first();
        if ($medicion) {
            $this->sonometroMedida = $medicion->sonometroMedida;
            $this->sonometroResultado = $medicion->sonometroResultado;
        }
    }

    // Crea mediciones para MedicionSonometro
    /*public function guardar()
    {
        $this->validate([
            'sonometroMedida' => 'required|numeric|min:0',
            'sonometroResultado' => 'required|string|in:APROBADO,DESAPROBADO',
        ]);

        MedicionSonometro::create([
            'inspeccion_propuesta_id' => $this->idPropuesta,
            'sonometroMedida' => $this->sonometroMedida,
            'sonometroResultado' => $this->sonometroResultado,
        ]);

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");

        $this->reset('sonometroMedida', 'sonometroResultado');
    }*/

    // Crea y actualiza en caso exista para MedicionSonometro
    public function guardar()
    {
        $this->validate([
            'sonometroMedida' => 'required|numeric|min:0',
            'sonometroResultado' => 'required|string|in:APROBADO,DESAPROBADO',
        ]);

        $medicion = MedicionSonometro::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if ($medicion) {
            // Actualizar
            $medicion->update([
                'sonometroMedida' => $this->sonometroMedida,
                'sonometroResultado' => $this->sonometroResultado,
            ]);
        } else {
            // Crear nuevo
            MedicionSonometro::create([
                'inspeccion_propuesta_id' => $this->idPropuesta,
                'sonometroMedida' => $this->sonometroMedida,
                'sonometroResultado' => $this->sonometroResultado,
            ]);
        }

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");
    }

    public function updatedSonometroMedida($value)
    {
        if (is_numeric($value)) {
            if ($value >= 1 && $value <= 99) {
                $this->sonometroResultado = 'APROBADO';
            } elseif ($value >= 100) {
                $this->sonometroResultado = 'DESAPROBADO';
            } else {
                $this->sonometroResultado = null;
            }
        }
    }

    public function render()
    {
        return view('livewire.form-mediciones-sonometro');
    }
}
