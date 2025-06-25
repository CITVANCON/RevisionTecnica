<?php

namespace App\Livewire;

use App\Models\Expediente;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Component;

class Expedientes extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $fotosnuevas = [];
    public $documentosnuevos = [];

    public $files = [];
    public $documentos = [];

    public $idus, $expediente, $identificador, $es;

    public $placa, $num_propuesta;

    public $search = "", $cant = "", $sort = "created_at", $direction = 'desc';

    public $readyToLoad = false;
    public $editando = false;


    protected $queryString = [
        'cant' => ['except' => '10'],
        'sort' => ['except' => 'created_at'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => ''],
        'es' => ['except' => ''],
    ];

    protected $rules = [
        'placa' => 'required',
        'num_propuesta' => 'required',
        'fotosnuevas.*' => 'image|mimes:jpeg,png,jpg,gif,heic,heif|max:5120',
        'documentosnuevos.*' => 'mimes:pdf,xls,xlsx,doc,docx,txt|max:2048',
    ];

    public function loadExpedientes()
    {
        $this->readyToLoad = true;
    }
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
        //return view('livewire.expedientes');
        $expedientes = Expediente::orderBy($this->sort, $this->direction)
            ->paginate($this->cant);
        return view('livewire.expedientes', compact('expedientes'));
    }

    public function edit(Expediente $expediente)
    {
        $this->expediente = $expediente;
        $this->placa = $expediente->placa;
        $this->num_propuesta = $expediente->num_propuesta;
        $this->files = Imagen::where('idExpediente', '=', $expediente->id)->get();
        $this->editando = true;
    }

    public function actualizar()
    {

        $this->validate();
        // Primero actualizamos los datos del expediente
        $this->expediente->placa = $this->placa;
        $this->expediente->num_propuesta = $this->num_propuesta;
        $this->expediente->estado = 1;
        $this->expediente->save();

        if (count($this->fotosnuevas) > 0) {
            foreach ($this->fotosnuevas as $key => $fto) {

                /*$file_sa= new Imagen();
                $file_sa->nombre=trim($this->expediente->placa).'-foto'.($key+1).$this->identificador.'-'.$this->expediente->certificado;
                $file_sa->extension=$fto->extension();
                $file_sa->ruta = $fto->storeAs('expedientes', $file_sa->nombre.'.'.$fto->extension(), 'public');
                $file_sa->Expediente_idExpediente=$this->expediente->id;*/

                $extension = $fto->extension();
                $nombre = trim($this->placa) . '-foto' . ($key + 1) . '-' . uniqid() . '.' . $extension;
                $ruta = $fto->storeAs('expedientes', $nombre, 'public');

                Imagen::create([
                    'nombre' => $nombre,
                    'ruta' => $ruta,
                    'extension' => $extension,
                    'idExpediente' => $this->expediente->id,
                ]);
            }
        }
        // Refrescamos galería
        $this->files = Imagen::where('idExpediente', $this->expediente->id)->get();
        // Limpiamos estados
        $this->reset(['editando', 'expediente', 'fotosnuevas', 'placa', 'num_propuesta', 'files']);
        // Alerta de éxito
        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Se actualizaron los datos correctamente.", icono: "success");
    }

    public function deleteFile($id)
    {
        $imagen = Imagen::find($id);
        if ($imagen) {
            Storage::disk('public')->delete($imagen->ruta);
            $imagen->delete();
            $this->files = Imagen::where('idExpediente', $this->expediente->id)->get();
        }
    }

    public function deleteFileUpload($key)
    {
        if (isset($this->fotosnuevas[$key])) {
            // Eliminamos la imagen temporalmente seleccionada
            unset($this->fotosnuevas[$key]);
            // Reindexamos el array para evitar errores en el renderizado
            $this->fotosnuevas = array_values($this->fotosnuevas);
        }
    }
}
