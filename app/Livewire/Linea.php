<?php

namespace App\Livewire;

use App\Models\InspeccionPropuesta;
use Livewire\Component;

class Linea extends Component
{
    public $propuestas;

    public function mount()
    {
        $this->propuestas = InspeccionPropuesta::where('estado', 1)->get();
    }

    public function render()
    {
        return view('livewire.linea');
    }
}
