<?php

namespace App\Livewire;

use App\Models\InspeccionFoto;
use App\Models\InspeccionPropuesta;
use Illuminate\Support\Facades\Storage;
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

                $extension = $archivo->getClientOriginalExtension();
                $nombreLimpio = strtolower(str_replace(' ', '_', $tipo)); // Por si hay espacios
                $nombreArchivo = $this->propuesta->id . '-' . $nombreLimpio . '.' . $extension;

                // Guardamos en public/inspeccion_fotos con nombre personalizado
                $path = $archivo->storeAs('inspeccion_fotos', $nombreArchivo, 'public');

                // Si ya existe una foto de este tipo, actualizarla
                $foto = InspeccionFoto::updateOrCreate(
                    [
                        'inspeccion_propuesta_id' => $this->propuesta->id,
                        'tipo_foto' => $tipo,
                    ],
                    [
                        'nombre' => $nombreArchivo,
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

    public function deleteFile($id)
    {
        $foto = InspeccionFoto::find($id);
        if ($foto) {
            // Borra el archivo físicamente si existe
            if (Storage::disk('public')->exists($foto->ruta)) {
                Storage::disk('public')->delete($foto->ruta);
            }
            $foto->delete(); // Borra el registro en la BD
            $this->files = InspeccionFoto::where('inspeccion_propuesta_id', $this->propuesta->id)->get();
            $this->dispatch('minAlert', titulo: "Eliminado", mensaje: "Foto eliminada correctamente", icono: "success");
        }
    }

    public function removePreview($tipo)
    {
        if ($tipo === 'Izquierda') {
            $this->fotoIzquierda = null;
        } elseif ($tipo === 'Centro') {
            $this->fotoCentro = null;
        } elseif ($tipo === 'Derecha') {
            $this->fotoDerecha = null;
        }
    }
}
