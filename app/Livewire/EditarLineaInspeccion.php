<?php

namespace App\Livewire;

use App\Models\InspeccionPropuesta;
use Livewire\Component;

class EditarLineaInspeccion extends Component
{
    public $idPropuesta, $propuesta;
    public $mostrarSubopciones = true;
    public $seccionActiva = null;


    protected $listeners = ["refrescaPropuesta"];

    /*public function mount()
    {
        $this->propuesta = InspeccionPropuesta::find($this->idPropuesta);
    }*/

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;
        $this->propuesta = InspeccionPropuesta::findOrFail($idPropuesta);
    }

    public function refrescaPropuesta()
    {
        $this->propuesta->refresh();
    }

    public function mostrarSeccion($seccion)
    {
        $this->seccionActiva = $this->seccionActiva === $seccion ? null : $seccion;
    }

    public function render()
    {
        return view('livewire.editar-linea-inspeccion');
    }
}
