<?php

namespace App\Livewire;

use App\Models\InspeccionComplementaria;
use Livewire\Component;

class FormComplementaria extends Component
{
    public $idPropuesta;
    public $datosComplementarios;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;
        $this->datosComplementarios = InspeccionComplementaria::where('inspeccion_propuesta_id', $idPropuesta)->first();
    }
    
    public function render()
    {
        return view('livewire.form-complementaria');
    }
}
