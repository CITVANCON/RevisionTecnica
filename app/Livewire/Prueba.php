<?php

namespace App\Livewire;

use App\Models\Ambito;
use App\Models\Categoria;
use App\Models\Clase;
use App\Models\InspeccionFinalizada;
use App\Models\InspeccionPropuesta;
use App\Models\InspeccionServicioAmbito;
use App\Models\InspeccionVehiculo;
use App\Models\Servicio;
use App\Models\Subclase;
use App\Models\Tipodocumentoidentidad;
use App\Models\Tipovehiculo;
use App\Models\Vehiculo;
use Livewire\Component;

class Prueba extends Component
{
    // variable para cargar el vehiculo
    public $vehiculo;
    // variables para los tipos (selects)
    public $servicios, $ambitos, $clases, $subclases, $categorias, $tiposVehiculo, $tiposDocumento;
    // variables para alta vehiculo
    public $servicio_id, $ambito_id, $clase_id, $subclase_id, $categoria_id, $tipovehiculo_id;
    public $tipodocumentoidentidad_id, $numero_documento;
    public $direccion, $celular, $correo;

    protected $listeners = ['cargaVehiculo' => 'carga'];

    protected function rules()
    {
        return [
            'servicio_id' => 'required|exists:servicio,id',
            'ambito_id' => 'required|exists:ambito,id',
            'clase_id' => 'required|exists:clase,id',
            'subclase_id' => 'required|exists:subclase,id',
            'categoria_id' => 'required|exists:categoria,id',

            'tipovehiculo_id' => 'required|exists:tipovehiculo,id',
            'tipodocumentoidentidad_id' => 'required|exists:tipodocumentoidentidad,id',
            'numero_documento' => 'required|string|max:8',
            'direccion' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:9',
            'correo' => 'nullable|email|max:50',
        ];
    }

    public function mount()
    {
        $this->servicios = Servicio::pluck('descripcion', 'id');
        $this->ambitos = Ambito::pluck('descripcion', 'id');
        $this->clases = Clase::pluck('descripcion', 'id');
        $this->subclases = Subclase::pluck('descripcion', 'id');
        //$this->categorias = Categoria::pluck('descripcion', 'id');
        $this->categorias = Categoria::all();
        $this->tiposVehiculo = Tipovehiculo::pluck('descripcion', 'id');
        $this->tiposDocumento = Tipodocumentoidentidad::pluck('descripcion', 'id');
    }

    public function carga($id)
    {
        $this->vehiculo = Vehiculo::find($id);
    }

    public function render()
    {
        return view('livewire.prueba');
    }

    public function certificar()
    {
        $this->validate();

        if (!$this->vehiculo) {
            $this->dispatch(
                'minAlert',
                titulo: "AVISO DEL SISTEMA",
                mensaje: "Primero debes registrar un vehículo.",
                icono: "warning"
            );
            return;
        }

        $añoActual = now()->year;
        $ultimaPropuesta = InspeccionPropuesta::whereYear('fecha_creacion', $añoActual)
            ->orderByDesc('fecha_creacion')
            ->first();

        $ultimoNumero = $ultimaPropuesta ? (int)$ultimaPropuesta->num_propuesta : 0;

        // Nueva lógica segura
        do {
            $ultimoNumero++;
            $nuevoNumero = str_pad($ultimoNumero, 6, '0', STR_PAD_LEFT);
            $existe = InspeccionPropuesta::where('num_propuesta', $nuevoNumero)->exists();
        } while ($existe);


        // 1. Crear propuesta
        $propuesta = new InspeccionPropuesta();
        $propuesta->num_propuesta = $nuevoNumero;
        $propuesta->fecha_creacion = now();
        $propuesta->estado = 1;
        $propuesta->save();

        // 2. Crear relación servicio - ámbito
        $servicioAmbito = new InspeccionServicioAmbito();
        $servicioAmbito->idServicio = $this->servicio_id;
        $servicioAmbito->idAmbito = $this->ambito_id;
        $servicioAmbito->inspeccion_propuesta_id = $propuesta->id;
        $servicioAmbito->save();

        // 3. Guardar datos del vehículo (asociados a la propuesta)
        $inspeccionVehiculo = new InspeccionVehiculo();
        $inspeccionVehiculo->idVehiculo = $this->vehiculo->id;
        $inspeccionVehiculo->idTipovehiculo = $this->tipovehiculo_id;
        $inspeccionVehiculo->idCategoria = $this->categoria_id;
        $inspeccionVehiculo->idTipodocumento = $this->tipodocumentoidentidad_id;
        $inspeccionVehiculo->num_documento = $this->numero_documento;
        $inspeccionVehiculo->direccion = $this->direccion;
        $inspeccionVehiculo->celular = $this->celular;
        $inspeccionVehiculo->correo = $this->correo;
        $inspeccionVehiculo->inspeccion_propuesta_id = $propuesta->id;
        $inspeccionVehiculo->save();

        // 4. Guardar información de la inspección finalizada
        $inspeccionFinalizada = new InspeccionFinalizada();
        $inspeccionFinalizada->idVehiculo = $this->vehiculo->id;
        $inspeccionFinalizada->idClase = $this->clase_id;
        $inspeccionFinalizada->idSubclase = $this->subclase_id;
        $inspeccionFinalizada->inspeccion_propuesta_id = $propuesta->id;
        $inspeccionFinalizada->save();

        // Confirmación
        $this->dispatch(
            'minAlert',
            titulo: "¡BUEN TRABAJO!",
            mensaje: "Proceso de alta vehiculo registrado con éxito.",
            icono: "success"
        );

        $this->resetExcept('vehiculo', 'servicios', 'ambitos', 'clases', 'subclases', 'categorias', 'tiposVehiculo', 'tiposDocumento'); //
    }
}
