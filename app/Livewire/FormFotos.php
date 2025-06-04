<?php

namespace App\Livewire;

use App\Models\InspeccionFoto;
use Livewire\Component;

class FormFotos extends Component
{
    public $idPropuesta;
    //public $muestrafotos;
    public $fotoIzquierda;
    public $fotoCentro;
    public $fotoDerecha;

    public function mount($idPropuesta)
    {
        $this->idPropuesta = $idPropuesta;
        //$this->muestrafotos = InspeccionFoto::where('inspeccion_propuesta_id', $idPropuesta)->get();
        $fotos = InspeccionFoto::where('inspeccion_propuesta_id', $idPropuesta)->get();

        $this->fotoIzquierda = $fotos->firstWhere('tipo_foto', 'Izquierda');
        $this->fotoCentro     = $fotos->firstWhere('tipo_foto', 'Centro');
        $this->fotoDerecha    = $fotos->firstWhere('tipo_foto', 'Derecha');
    }

    public function render()
    {
        return view('livewire.form-fotos');
    }
}
