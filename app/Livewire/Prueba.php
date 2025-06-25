<?php

namespace App\Livewire;

use App\Models\Ambito;
use App\Models\Aseguradora;
use App\Models\Categoria;
use App\Models\Clase;
use App\Models\Expediente;
use App\Models\InspeccionAseguradora;
use App\Models\InspeccionComplementaria;
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
    public $servicios, $ambitos, $clases, $subclases, $categorias, $tiposVehiculo, $tiposDocumento, $aseguradoras;
    // variables para alta vehiculo
    public $servicio_id, $ambito_id, $clase_id, $subclase_id, $categoria_id, $tipovehiculo_id;
    public $tipodocumentoidentidad_id, $numero_documento;
    public $direccion, $celular, $correo;
    public $tipopoliza, $num_poliza, $fechaInicio, $fechaFin, $aseguradora_id;

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

            'aseguradora_id' => 'required|exists:aseguradora,id',
            'tipopoliza' => 'nullable|string',
            'num_poliza' => 'nullable|string',
            'fechaInicio' => 'nullable|date',
            'fechaFin' => 'nullable|date|after_or_equal:fechaInicio',

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
        $this->aseguradoras = Aseguradora::pluck('descripcion', 'id');
    }

    public function updatedServicioId($value)
    {
        if ($value == 2) {
            $this->ambito_id = 3;
            $this->clase_id = 1;
            $this->subclase_id = 2;
        } /*else {
            $this->ambito_id = null;
            $this->clase_id = null;
            $this->subclase_id = null;
        }*/
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
            $this->dispatch('minAlert', titulo: "AVISO DEL SISTEMA", mensaje: "Primero debes registrar un vehículo.", icono: "warning");
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
        $propuesta = InspeccionPropuesta::create([
            'num_propuesta' => $nuevoNumero,
            'fecha_creacion' => now(),
            'estado' => 1,
        ]);

        // 2. Crear relación servicio - ámbito
        InspeccionServicioAmbito::create([
            'idServicio' => $this->servicio_id,
            'idAmbito' => $this->ambito_id,
            'inspeccion_propuesta_id' => $propuesta->id,
        ]);

        // 3. Guardar datos del vehículo (asociados a la propuesta)
        InspeccionVehiculo::create([
            'idVehiculo' => $this->vehiculo->id,
            'idTipovehiculo' => $this->tipovehiculo_id,
            'idCategoria' => $this->categoria_id,
            'idTipodocumento' => $this->tipodocumentoidentidad_id,
            'num_documento' => $this->numero_documento,
            'direccion' => $this->direccion,
            'celular' => $this->celular,
            'correo' => $this->correo,
            'inspeccion_propuesta_id' => $propuesta->id,
        ]);

        // 4. Guardar información de la inspección aseguradora
        InspeccionAseguradora::create([
            'idAseguradora' => $this->aseguradora_id,
            'tipopoliza' => $this->tipopoliza,
            'num_poliza' => $this->num_poliza,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'inspeccion_propuesta_id' => $propuesta->id,
        ]);

        // 5. Guardar información de la inspección finalizada
        InspeccionFinalizada::create([
            'idVehiculo' => $this->vehiculo->id,
            'idClase' => $this->clase_id,
            'idSubclase' => $this->subclase_id,
            'inspeccion_propuesta_id' => $propuesta->id,
        ]);

        // 6. Si aplica, guardar inspección complementaria
        if ($this->servicio_id == 2) {
            InspeccionComplementaria::create([
                'idVehiculo' => $this->vehiculo->id,
                'idTipoComplementaria' => 12,
                'inspeccion_propuesta_id' => $propuesta->id,
            ]);
        }

        // 7. Crear expediente
        Expediente::create([
            'placa' => $this->vehiculo->placa,
            'num_propuesta' => $nuevoNumero,
            'estado' => 1,
        ]);



        // Confirmación
        $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Proceso de alta vehiculo registrado con éxito.", icono: "success");
        $this->resetExcept('vehiculo', 'servicios', 'ambitos', 'clases', 'subclases', 'categorias', 'tiposVehiculo', 'tiposDocumento', 'aseguradoras');
    }
}
