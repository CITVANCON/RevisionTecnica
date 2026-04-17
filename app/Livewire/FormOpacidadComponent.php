<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class FormOpacidadComponent extends Component
{
    public $opacidad = [
        'ciclo1_k' => 0, 'ciclo1_t' => 0,
        'ciclo2_k' => 0, 'ciclo2_t' => 0,
        'ciclo3_k' => 0, 'ciclo3_t' => 0,
        'ciclo4_k' => 0, 'ciclo4_t' => 0,
        'promedio_k' => 0,
        'limite_permitido' => 2.5 // Valor por defecto según norma
    ];

    public function updatedOpacidad()
    {
        $suma = (float)$this->opacidad['ciclo1_k'] + (float)$this->opacidad['ciclo2_k'] + 
                (float)$this->opacidad['ciclo3_k'] + (float)$this->opacidad['ciclo4_k'];
        $this->opacidad['promedio_k'] = round($suma / 4, 3);

        // Enviar cálculo preliminar al padre para que el select se mueva solo
        $this->dispatch('calculoPreliminarOpacidad', promedio: $this->opacidad['promedio_k'], limite: $this->opacidad['limite_permitido']);
    }

    #[On('solicitarDatosOpacidad')]
    public function enviarDatos()
    {
        // Validamos que el promedio no sea 0 (evita enviar formularios vacíos)
        if ($this->opacidad['promedio_k'] <= 0) {
            $this->dispatch('minAlert', titulo: "VERIFICAR", mensaje: "Las lecturas de opacidad deben ser mayores a 0.", icono: "warning");
            return;
        }

        $this->dispatch('datosOpacidadListos', $this->opacidad);
    }

    public function render()
    {
        return view('livewire.form-opacidad-component');
    }
}
