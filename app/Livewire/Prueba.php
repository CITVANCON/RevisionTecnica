<?php

namespace App\Livewire;

use Livewire\Component;

class Prueba extends Component
{
    protected $listeners = ['cargaVehiculo' => 'carga'];
    
    public function render()
    {
        return view('livewire.prueba');
    }
}
