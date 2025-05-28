<?php

namespace App\Livewire;

use App\Models\InspeccionFoto;
use Livewire\Component;

class FormFotos extends Component
{
    public $idPropuesta;
    public $muestrafotos;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;
        $this->muestrafotos = InspeccionFoto::where('inspeccion_propuesta_id', $idPropuesta)->first();
    }

    public function render()
    {
        return view('livewire.form-fotos');
    }
}
