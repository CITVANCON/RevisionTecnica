<?php

namespace App\Livewire;

use App\Models\InspeccionFoto;
use App\Models\InspeccionPropuesta;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Component;

class SubirFotografias extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search, $cant, $sort, $direction;

    public $propuesta;
    public $editando = false;
    public $propuestaSeleccionada;

    public $fotosnuevas = [], $files = [];
    public $fotoIzquierda, $fotoCentro, $fotoDerecha;

    protected $queryString = [
        'search' => ['except' => ''],
        'cant' => ['except' => '10'],
        'sort' => ['except' => 'created_at'],
        'direction' => ['except' => 'desc'],
    ];

    protected $rules = [
        'fotoIzquierda' => 'nullable|image|mimes:jpeg,png,jpg,gif,heic,heif|max:5120',
        'fotoCentro' => 'nullable|image|mimes:jpeg,png,jpg,gif,heic,heif|max:5120',
        'fotoDerecha' => 'nullable|image|mimes:jpeg,png,jpg,gif,heic,heif|max:5120',
    ];

    public function mount()
    {
        $this->cant = "10";
        $this->search = '';
        $this->sort = "created_at";
        $this->direction = 'desc';
    }

    public function order($sort)
    {
        if ($this->sort === $sort) {
            $this->direction = $this->direction === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function render()
    {
        $propuestas = InspeccionPropuesta::propuesta($this->search)
            ->orderBy($this->sort, $this->direction)
            ->paginate($this->cant);
        return view('livewire.subir-fotografias', compact('propuestas'));
    }

    public function edit(InspeccionPropuesta $propuesta)
    {
        $this->reset(['fotoIzquierda', 'fotoCentro', 'fotoDerecha']);
        $this->propuesta = $propuesta;
        $this->files = InspeccionFoto::where('inspeccion_propuesta_id', $propuesta->id)->get();
        $this->propuestaSeleccionada = $propuesta;
        $this->editando = true;
    }

    public function guardarFotos()
    {
        $this->validate();

        $tipos = [
            'Izquierda' => $this->fotoIzquierda,
            'Centro' => $this->fotoCentro,
            'Derecha' => $this->fotoDerecha,
        ];

        foreach ($tipos as $tipo => $archivo) {
            if ($archivo) {
                $path = $archivo->store('public/inspeccion_fotos');
                $nombre = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();

                // Si ya existe una foto de este tipo, actualizarla
                $foto = InspeccionFoto::updateOrCreate(
                    [
                        'inspeccion_propuesta_id' => $this->propuesta->id,
                        'tipo_foto' => $tipo,
                    ],
                    [
                        'nombre' => $nombre,
                        'ruta' => $path,
                        'extension' => $extension,
                        'estado' => 1,
                    ]
                );
            }
        }

        $this->editando = false;
        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Fotos guardadas correctamente.", icono: "success");
    }
}
