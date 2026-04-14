<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class FormHermeticidadComponent extends Component
{
    public $hermeticidad = [];

    #[On('solicitarDatosHermeticidad')] 
    public function enviarDatosAlPadre()
    {
        // Emitimos los datos de vuelta al padre
        $this->dispatch('datosHermeticidadListos', datos: $this->hermeticidad);
    }

    public function mount()
    {
        // Inicializamos la matriz con los valores por defecto del protocolo
        $this->hermeticidad = [
            'tiempo_prueba' => '5:00',
            'cant_bisagras' => 12, 'cant_pistones' => 3, 'cant_mangueras' => 3, 'cant_remaches' => 0,
            'faltas_bisagras' => 0, 'faltas_pistones' => 0, 'faltas_mangueras' => 0, 'faltas_remaches' => 0,
        ];

        $elementos = ['tapa', 'compuerta', 'tolva', 'sellos', 'bisagras', 'pistones', 'mangueras', 'remaches'];
        $criterios = ['deformidad', 'fisura', 'oxido', 'resequedad', 'lubricacion'];

        foreach ($elementos as $el) {
            foreach ($criterios as $cr) {
                // Lógica de "No Aplica" por defecto según protocolo
                $default = 'A';
                if (($el == 'tapa' || $el == 'tolva') && ($cr == 'resequedad' || $cr == 'lubricacion')) $default = 'NA';
                if ($el == 'sellos' && ($cr == 'oxido' || $cr == 'lubricacion')) $default = 'NA';
                if ($el == 'mangueras' && ($cr == 'oxido' || $cr == 'lubricacion')) $default = 'NA';
                if ($el == 'remaches') $default = 'NA';

                $this->hermeticidad["{$el}_{$cr}"] = $default;
            }
        }
    }

    // Método para que el padre recoja los datos
    public function getDatos()
    {
        return $this->hermeticidad;
    }
    
    public function render()
    {
        return view('livewire.form-hermeticidad-component');
    }
}
