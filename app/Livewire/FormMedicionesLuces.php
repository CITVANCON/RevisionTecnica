<?php

namespace App\Livewire;

use App\Models\MedicionLuz;
use Livewire\Component;

class FormMedicionesLuces extends Component
{
    public $idPropuesta;
    // LUZ ALTA
    public $luzAltaDerecha, $luzAltaIzquierda, $luzAltaAlineamiento, $luzAltaResultado;
    // LUZ BAJA
    public $luzBajaDerecha, $luzBajaIzquierda, $luzBajaAlineamiento, $luzBajaResultado;
    // LUZ ALTA ADICIONAL
    public $luzAltaAdicionalDerecha, $luzAltaAdicionalIzquierda, $luzAltaAdicionalAlineamiento, $luzAltaAdicionalResultado;
    // LUZ NEBLINERA
    public $luzNeblineraDerecha, $luzNeblineraIzquierda, $luzNeblineraAlineamiento, $luzNeblineraResultado;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;

        $medicion = MedicionLuz::where('inspeccion_propuesta_id', $idPropuesta)->first();
        if ($medicion) {
            // LUZ ALTA
            $this->luzAltaDerecha = $medicion->luzAltaDerecha;
            $this->luzAltaIzquierda = $medicion->luzAltaIzquierda;
            $this->luzAltaAlineamiento = $medicion->luzAltaAlineamiento;
            $this->luzAltaResultado = $medicion->luzAltaResultado;
            // LUZ BAJA
            $this->luzBajaDerecha = $medicion->luzBajaDerecha;
            $this->luzBajaIzquierda = $medicion->luzBajaIzquierda;
            $this->luzBajaAlineamiento = $medicion->luzBajaAlineamiento;
            $this->luzBajaResultado = $medicion->luzBajaResultado;
            // LUZ ALTA ADICIONAL
            $this->luzAltaAdicionalDerecha = $medicion->luzAltaAdicionalDerecha;
            $this->luzAltaAdicionalIzquierda = $medicion->luzAltaAdicionalIzquierda;
            $this->luzAltaAdicionalAlineamiento = $medicion->luzAltaAdicionalAlineamiento;
            $this->luzAltaAdicionalResultado = $medicion->luzAltaAdicionalResultado;
            // LUZ NEBLINERA
            $this->luzNeblineraDerecha = $medicion->luzNeblineraDerecha;
            $this->luzNeblineraIzquierda = $medicion->luzNeblineraIzquierda;
            $this->luzNeblineraAlineamiento = $medicion->luzNeblineraAlineamiento;
            $this->luzNeblineraResultado = $medicion->luzNeblineraResultado;
        }
    }

    public function guardar()
    {
        $data = $this->validate([
            'luzAltaDerecha' => 'nullable|string|max:255',
            // LUZ ALTA
            'luzAltaDerecha' => 'required|numeric|min:0',
            'luzAltaIzquierda' => 'required|numeric|min:0',
            'luzAltaAlineamiento' => 'required|numeric|min:0',
            'luzAltaResultado' => 'required|string|in:APROBADO,DESAPROBADO',
            // LUZ BAJA
            'luzBajaDerecha' => 'required|numeric|min:0',
            'luzBajaIzquierda' => 'required|numeric|min:0',
            'luzBajaAlineamiento' => 'required|numeric|min:0',
            'luzBajaResultado' => 'required|string|in:APROBADO,DESAPROBADO',
            // LUZ ALTA ADICIONAL
            'luzAltaAdicionalDerecha' => 'required|numeric|min:0',
            'luzAltaAdicionalIzquierda' => 'required|numeric|min:0',
            'luzAltaAdicionalAlineamiento' => 'required|numeric|min:0',
            'luzAltaAdicionalResultado' => 'required|string|in:APROBADO,DESAPROBADO',
            // LUZ NEBLINERA
            'luzNeblineraDerecha' => 'required|numeric|min:0',
            'luzNeblineraIzquierda' => 'required|numeric|min:0',
            'luzNeblineraAlineamiento' => 'required|numeric|min:0',
            'luzNeblineraResultado' => 'required|string|in:APROBADO,DESAPROBADO',
        ]);

        $medicion = MedicionLuz::where('inspeccion_propuesta_id', $this->idPropuesta)->first();

        if ($medicion) {
            // Actualizar
            $medicion->update([
                // LUZ ALTA
                'luzAltaDerecha' => $this->luzAltaDerecha,
                'luzAltaIzquierda' => $this->luzAltaIzquierda,
                'luzAltaAlineamiento' => $this->luzAltaAlineamiento,
                'luzAltaResultado' => $this->luzAltaResultado,
                // LUZ BAJA
                'luzBajaDerecha' => $this->luzBajaDerecha,
                'luzBajaIzquierda' => $this->luzBajaIzquierda,
                'luzBajaAlineamiento' => $this->luzBajaAlineamiento,
                'luzBajaResultado' => $this->luzBajaResultado,
                // LUZ ALTA ADICIONAL
                'luzAltaAdicionalDerecha' => $this->luzAltaAdicionalDerecha,
                'luzAltaAdicionalIzquierda' => $this->luzAltaAdicionalIzquierda,
                'luzAltaAdicionalAlineamiento' => $this->luzAltaAdicionalAlineamiento,
                'luzAltaAdicionalResultado' => $this->luzAltaAdicionalResultado,
                // LUZ NEBLINERA
                'luzNeblineraDerecha' => $this->luzNeblineraDerecha,
                'luzNeblineraIzquierda' => $this->luzNeblineraIzquierda,
                'luzNeblineraAlineamiento' => $this->luzNeblineraAlineamiento,
                'luzNeblineraResultado' => $this->luzNeblineraResultado,
            ]);
        } else {
            // Crear nuevo
            MedicionLuz::create([
                'inspeccion_propuesta_id' => $this->idPropuesta,
                // LUZ ALTA
                'luzAltaDerecha' => $this->luzAltaDerecha,
                'luzAltaIzquierda' => $this->luzAltaIzquierda,
                'luzAltaAlineamiento' => $this->luzAltaAlineamiento,
                'luzAltaResultado' => $this->luzAltaResultado,
                // LUZ BAJA
                'luzBajaDerecha' => $this->luzBajaDerecha,
                'luzBajaIzquierda' => $this->luzBajaIzquierda,
                'luzBajaAlineamiento' => $this->luzBajaAlineamiento,
                'luzBajaResultado' => $this->luzBajaResultado,
                // LUZ ALTA ADICIONAL
                'luzAltaAdicionalDerecha' => $this->luzAltaAdicionalDerecha,
                'luzAltaAdicionalIzquierda' => $this->luzAltaAdicionalIzquierda,
                'luzAltaAdicionalAlineamiento' => $this->luzAltaAdicionalAlineamiento,
                'luzAltaAdicionalResultado' => $this->luzAltaAdicionalResultado,
                // LUZ NEBLINERA
                'luzNeblineraDerecha' => $this->luzNeblineraDerecha,
                'luzNeblineraIzquierda' => $this->luzNeblineraIzquierda,
                'luzNeblineraAlineamiento' => $this->luzNeblineraAlineamiento,
                'luzNeblineraResultado' => $this->luzNeblineraResultado,
            ]);
        }

        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Medición guardada correctamente.", icono: "success");
    }

    public function render()
    {
        return view('livewire.form-mediciones-luces');
    }
}
