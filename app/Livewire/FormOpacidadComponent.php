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
    }

    #[On('solicitarDatosOpacidad')]
    public function enviarDatos()
    {
        $this->dispatch('datosOpacidadListos', $this->opacidad);
    }

    public function render()
    {
        return view('livewire.form-opacidad-component');
    }
}
