<?php

namespace App\Livewire;

use App\Models\InspeccionPropuesta;
use Livewire\Component;

class EditarLineaInspeccion extends Component
{
    public $idPropuesta, $propuesta;
    public $seccionActiva = null;
    public $subseccionActiva = null;


    protected $listeners = ["refrescaPropuesta"];

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
        $this->subseccionActiva = null; // Resetear subsección al cambiar de sección
    }

    public function mostrarSubseccion($subseccion)
    {
        $this->subseccionActiva = $this->subseccionActiva === $subseccion ? null : $subseccion;
    }

    public function render()
    {
        return view('livewire.editar-linea-inspeccion');
    }
}
