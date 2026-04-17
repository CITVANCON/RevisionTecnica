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
        //$this->dispatch('datosHermeticidadListos', datos: $this->hermeticidad);

        // Podríamos validar aquí que el campo tiempo_prueba no esté vacío
        if (empty($this->hermeticidad['tiempo_prueba'])) {
            $this->dispatch('minAlert', titulo: "DATO FALTANTE", mensaje: "Debe ingresar el tiempo de la prueba de esfuerzo.", icono: "warning");
            return;
        }

        // Emitimos los datos de vuelta al padre
        $this->dispatch('datosHermeticidadListos', $this->hermeticidad);
    }

    public function updated($propertyName)
    {
        // Solo actuamos si el cambio fue dentro del array de hermeticidad
        if (str_contains($propertyName, 'hermeticidad')) {
            
            // 1. Verificamos si hay algún elemento "Observado" (O)
            // Usamos array_values para asegurarnos de buscar en todo el contenido
            $tieneObservaciones = in_array('O', array_values($this->hermeticidad));

            // 2. Verificamos si hay faltantes en la cuantificación
            $faltantes = (int)($this->hermeticidad['faltas_bisagras'] ?? 0) + 
                        (int)($this->hermeticidad['faltas_pistones'] ?? 0) + 
                        (int)($this->hermeticidad['faltas_mangueras'] ?? 0) + 
                        (int)($this->hermeticidad['faltas_remaches'] ?? 0);

            // LÓGICA DE NEGOCIO:
            $resultado = ($tieneObservaciones || $faltantes > 0) ? 'DESAPROBADO' : 'APROBADO';

            // 3. Enviamos el resultado preliminar al padre inmediatamente
            $this->dispatch('calculoPreliminarHermeticidad', resultado: $resultado);
        }
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
