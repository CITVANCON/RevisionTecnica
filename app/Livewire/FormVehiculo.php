<?php

namespace App\Livewire;

use App\Models\Vehiculo;
use Livewire\WithDispatchable;
use Livewire\Component;

class FormVehiculo extends Component
{
    public $nombreDelInvocador;

    //VARIABLES PARA CREAR EL VEHICULO
    public $placa, $propietario, $categoria, $marca, $modelo, $anio_fabricacion,
        $kilometraje, $combustible, $vin_serie, $numero_motor, $carroceria,
        $marca_carroceria, $ejes, $ruedas, $asientos, $pasajeros, $largo, $ancho,
        $alto, $color, $peso_neto, $peso_bruto, $peso_util;

    //VARIABLES PARA MOSTRAR EL VEHICULO
    public $m_placa, $m_propietario, $m_categoria, $m_marca, $m_modelo, $m_anio_fabricacion,
        $m_kilometraje, $m_combustible, $m_vin_serie, $m_numero_motor, $m_carroceria,
        $m_marca_carroceria, $m_ejes, $m_ruedas, $m_asientos, $m_pasajeros, $m_largo, $m_ancho,
        $m_alto, $m_color, $m_peso_neto, $m_peso_bruto, $m_peso_util;

    //VARIABLES DEL COMPONENTE
    public Vehiculo $vehiculo;
    public $estado, $vehiculos, $busqueda;

    protected $listeners = ['cargarDatosVehiculo' => 'cargarDesdeRespuesta']; // cargamos datos de respuesta mtc
    public function cargarDesdeRespuesta(array $datos)
    {
        $this->placa = $datos['PLACA'] ?? $this->placa;
        $this->categoria = $datos['CATEGORIA'] ?? null;
        $this->marca = $datos['MARCA'] ?? null;
        $this->modelo = $datos['MODELO'] ?? null;
        $this->anio_fabricacion = $datos['AÑOFAB'] ?? null;
        $this->combustible = $datos['COMBUSTIBLE'] ?? null;
        $this->vin_serie = $datos['VINSERCHA'] ?? null;
        $this->numero_motor = $datos['NUMEROMOTOR'] ?? null;
        $this->carroceria = $datos['CARROCERIA'] ?? null;
        $this->ejes = $datos['NUMEROEJES'] ?? null;
        $this->ruedas = $datos['NUMERORUEDAS'] ?? null;
        $this->asientos = $datos['NUMEROASIENTOS'] ?? null;
        $this->pasajeros = $datos['NUMEROPASAJEROS'] ?? null;
        $this->largo = $datos['LARGO'] ?? null;
        $this->ancho = $datos['ANCHO'] ?? null;
        $this->alto = $datos['ALTO'] ?? null;
        $this->color = $datos['COLOR'] ?? null;
        $this->peso_neto = $datos['PESONETO'] ?? null;
        $this->peso_bruto = $datos['PESOBRUTO'] ?? null;
        $this->peso_util = $datos['PESOUTIL'] ?? null;
        // Emitir hacia componente Prueba
        $this->dispatch('actualizarCategoria', $this->categoria)->to('prueba');
    }

    public function buscarVehiculo()
    {
        if (strlen($this->placa) >= 6) {
            $this->dispatch('consultarDatosMTC', $this->placa)->to('prueba');
        }
    }


    public function mount()
    {
        $this->estado = "nuevo";
    }

    protected $rules = [

        "placa" => "required|min:6|max:7",
        "propietario" => "required",
        "categoria" => "nullable",
        "marca" => "required|min:2",
        "modelo" => "required|min:2",
        "anio_fabricacion" => "nullable|numeric|min:1900",
        "kilometraje" => "required|min:2",
        "combustible" => "nullable|min:2",
        "vin_serie" => "nullable|min:2",
        "numero_motor" => "nullable|min:2",
        "carroceria" => "nullable",
        "marca_carroceria" => "nullable",
        "ejes" => "nullable|numeric|min:1",
        "ruedas" => "nullable|numeric|min:1",
        "asientos" => "nullable|numeric|min:1",
        "pasajeros" => "nullable|numeric|min:1",
        "largo" => "nullable|numeric",
        "ancho" => "nullable|numeric",
        "alto" => "nullable|numeric",
        "color" => "nullable|min:2",
        "peso_neto" => "nullable|numeric",
        "peso_bruto" => "nullable|numeric",
        "peso_util" => "nullable|numeric",
        /* 
            "vehiculo.placa" => "required|min:6",
            "vehiculo.propietario" => "required",
            "vehiculo.categoria" => "nullable",
            "vehiculo.marca" => "required|min:2",
            "vehiculo.modelo" => "required|min:2",
            "vehiculo.anio_fabricacion" => "nullable|numeric|min:1900",
            "vehiculo.kilometraje" => "required|min:2",
            "vehiculo.combustible" => "nullable|min:2",
            "vehiculo.vin_serie" => "nullable|min:2",
            "vehiculo.numero_motor" => "nullable|min:2",
            "vehiculo.carroceria" => "nullable",
            "vehiculo.marca_carroceria" => "nullable",
            "vehiculo.ejes" => "nullable|numeric|min:1",
            "vehiculo.ruedas" => "nullable|numeric|min:1",
            "vehiculo.asientos" => "nullable|numeric|min:1",
            "vehiculo.pasajeros" => "nullable|numeric|min:1",
            "vehiculo.largo" => "nullable|numeric",
            "vehiculo.ancho" => "nullable|numeric",
            "vehiculo.alto" => "nullable|numeric",
            "vehiculo.color" => "nullable|min:2",
            "vehiculo.peso_neto" => "nullable|numeric",
            "vehiculo.peso_bruto" => "nullable|numeric",
            "vehiculo.peso_util" => "nullable|numeric",
        */
    ];

    public function render()
    {
        return view('livewire.form-vehiculo');
    }

    public function guardaVehiculo()
    {
        $this->validate(
            [
                "placa" => "required|min:6|max:7",
                "propietario" => "required",
                "categoria" => "nullable",
                "marca" => "required|min:2",
                "modelo" => "required|min:2",
                "anio_fabricacion" => "nullable|numeric|min:1900",
                "kilometraje" => "required|min:2",
                "combustible" => "nullable|min:2",
                "vin_serie" => "nullable|min:2",
                "numero_motor" => "nullable|min:2",
                "carroceria" => "nullable",
                "marca_carroceria" => "nullable",
                "ejes" => "nullable|numeric|min:1",
                "ruedas" => "nullable|numeric|min:1",
                "asientos" => "nullable|numeric|min:1",
                "pasajeros" => "nullable|numeric|min:1",
                "largo" => "nullable|numeric",
                "ancho" => "nullable|numeric",
                "alto" => "nullable|numeric",
                "color" => "nullable|min:2",
                "peso_neto" => "nullable|numeric",
                "peso_bruto" => "nullable|numeric",
                "peso_util" => "nullable|numeric",
            ]
        );

        if ($vehiculo = Vehiculo::create([
            "placa" => strtoupper($this->placa),
            "propietario" => strtoupper($this->propietario),
            "categoria" => strtoupper($this->categoria),
            "marca" => $this->retornaNE($this->marca),
            "modelo" => $this->retornaNE($this->modelo),
            "anio_fabricacion" => $this->retornaNulo($this->anio_fabricacion),
            "kilometraje" => $this->retornaNE($this->kilometraje),
            "combustible" => $this->retornaNE($this->combustible),
            "vin_serie" => $this->retornaNE($this->vin_serie),
            "numero_motor" => $this->retornaNE($this->numero_motor),
            "carroceria" => $this->retornaNE($this->carroceria),
            "marca_carroceria" => $this->retornaNE($this->marca_carroceria),
            "ejes" => $this->retornaNulo($this->ejes),
            "ruedas" => $this->retornaNulo($this->ruedas),
            "asientos" => $this->retornaNulo($this->asientos),
            "pasajeros" => $this->retornaNulo($this->pasajeros),
            "largo" => $this->retornaNulo($this->largo),
            "ancho" => $this->retornaNulo($this->ancho),
            "alto" => $this->retornaNulo($this->alto),
            "color" => $this->retornaNE($this->color),
            "peso_neto" => $this->retornaNulo($this->peso_neto),
            "peso_bruto" => $this->retornaNulo($this->peso_bruto),
            "peso_util" => $this->retornaNulo($this->peso_util),
        ])) {
            $this->vehiculo = $vehiculo;
            $this->m_placa = $vehiculo->placa;
            $this->m_propietario = $vehiculo->propietario;
            $this->m_categoria = $vehiculo->categoria;
            $this->m_marca = $vehiculo->marca;
            $this->m_modelo = $vehiculo->modelo;
            $this->m_anio_fabricacion = $vehiculo->anio_fabricacion;
            $this->m_kilometraje = $vehiculo->kilometraje;
            $this->m_combustible = $vehiculo->combustible;
            $this->m_vin_serie = $vehiculo->vin_serie;
            $this->m_numero_motor = $vehiculo->numero_motor;
            $this->m_carroceria = $vehiculo->carroceria;
            $this->m_marca_carroceria = $vehiculo->marca_carroceria;
            $this->m_ejes = $vehiculo->ejes;
            $this->m_ruedas = $vehiculo->ruedas;
            $this->m_asientos = $vehiculo->asientos;
            $this->m_pasajeros = $vehiculo->pasajeros;
            $this->m_largo = $vehiculo->largo;
            $this->m_ancho = $vehiculo->ancho;
            $this->m_alto = $vehiculo->alto;
            $this->m_color = $vehiculo->color;
            $this->m_peso_neto = $vehiculo->peso_neto;
            $this->m_peso_bruto = $vehiculo->peso_bruto;
            $this->m_peso_util = $vehiculo->peso_util;
            $this->estado = 'cargado';
            $this->dispatch(
                'minAlert',
                titulo: "¡BUEN TRABAJO!",
                mensaje: "El vehículo con placa {$vehiculo->placa} se registró correctamente.",
                icono: "success"
            );

            if ($this->nombreDelInvocador != null) {
                $this->dispatch('cargaVehiculo', $vehiculo->id)->to($this->nombreDelInvocador);
            } else {
                $this->dispatch('cargaVehiculo', $vehiculo->id)->to('prueba');
            }
        } else {
            $this->dispatch(
                'minAlert',
                titulo: "AVISO DEL SISTEMA",
                mensaje: "Ocurrio un error al guardar los datos del vehículo",
                icono: "warning"
            );
        }
    }

    public function actualizarVehiculo()
    {
        $this->validate([
            "m_placa" => "required|min:6",
            "m_propietario" => "required",
            "m_categoria" => "nullable",
            "m_marca" => "required|min:2",
            "m_modelo" => "required|min:2",
            "m_anio_fabricacion" => "nullable|numeric|min:1900",
            "m_kilometraje" => "required|min:2",
            "m_combustible" => "nullable|min:2",
            "m_vin_serie" => "nullable|min:2",
            "m_numero_motor" => "nullable|min:2",
            "m_carroceria" => "nullable",
            "m_marca_carroceria" => "nullable",
            "m_ejes" => "nullable|numeric|min:1",
            "m_ruedas" => "nullable|numeric|min:1",
            "m_asientos" => "nullable|numeric|min:1",
            "m_pasajeros" => "nullable|numeric|min:1",
            "m_largo" => "nullable|numeric",
            "m_ancho" => "nullable|numeric",
            "m_alto" => "nullable|numeric",
            "m_color" => "nullable|min:2",
            "m_peso_neto" => "nullable|numeric",
            "m_peso_bruto" => "nullable|numeric",
            "m_peso_util" => "nullable|numeric",
        ]);

        if ($this->vehiculo->update([
            "placa" => strtoupper($this->m_placa),
            "propietario" => strtoupper($this->m_propietario),
            "categoria" => strtoupper($this->m_categoria),
            "marca" => $this->retornaNE($this->m_marca),
            "modelo" => $this->retornaNE($this->m_modelo),
            "anio_fabricacion" => $this->retornaNulo($this->m_anio_fabricacion),
            "kilometraje" => $this->retornaNE($this->m_kilometraje),
            "combustible" => $this->retornaNE($this->m_combustible),
            "vin_serie" => $this->retornaNE($this->m_vin_serie),
            "numero_motor" => $this->retornaNE($this->m_numero_motor),
            "m_carroceria" => $this->retornaNE($this->m_carroceria),
            "m_marca_carroceria" => $this->retornaNE($this->m_marca_carroceria),
            "ejes" => $this->retornaNulo($this->m_ejes),
            "ruedas" => $this->retornaNulo($this->m_ruedas),
            "asientos" => $this->retornaNulo($this->m_asientos),
            "pasajeros" => $this->retornaNulo($this->m_pasajeros),
            "largo" => $this->retornaNulo($this->m_largo),
            "ancho" => $this->retornaNulo($this->m_ancho),
            "alto" => $this->retornaNulo($this->m_alto),
            "color" => $this->retornaNE($this->m_color),
            "peso_neto" => $this->retornaNulo($this->m_peso_neto),
            "peso_bruto" => $this->retornaNulo($this->m_peso_bruto),
            "peso_util" => $this->retornaNulo($this->m_peso_util),
        ])) {
            $this->estado = 'cargado';
            $this->dispatch('minAlert', titulo: "¡BUEN TRABAJO!", mensaje: "Datos del vehículo actualizados correctamente", icono: "success");
        } else {
            $this->dispatch('minAlert', titulo: "AVISO DEL SISTEMA", mensaje: "Ocurrio un error al actualizar los datos del vehículo", icono: "warning");
        }
    }

    //revisa la existencia del vehiculo en nuestra base de datos y los devuelve en caso de encontrarlo
    /*public function buscarVehiculo()
    {
        $this->validate(['placa' => 'min:3|max:7']);

        $vehiculos = vehiculo::where('placa', 'like', '%' . $this->placa . '%')->get();
        if ($vehiculos->count() > 0) {
            $this->busqueda = true;
            $this->vehiculos = $vehiculos;
        } else {
            $this->dispatch(
                'minAlert',
                titulo: "AVISO DEL SISTEMA",
                mensaje: "No se encontró ningún vehículo con la placa ingresada",
                icono: "warning"
            );
        }
    }*/

    public function seleccionaVehiculo(vehiculo $veh)
    {
        $this->vehiculo = $veh;
        $this->m_placa = $veh->placa;
        $this->m_propietario = $veh->propietario;
        $this->m_categoria = $veh->categoria;
        $this->m_marca = $veh->marca;
        $this->m_modelo = $veh->modelo;
        $this->m_anio_fabricacion = $veh->anio_fabricacion;
        $this->m_kilometraje = $veh->kilometraje;
        $this->m_combustible = $veh->combustible;
        $this->m_vin_serie = $veh->vin_serie;
        $this->m_numero_motor = $veh->numero_motor;
        $this->m_carroceria = $veh->carroceria;
        $this->m_marca_carroceria = $veh->marca_carroceria;
        $this->m_ejes = $veh->ejes;
        $this->m_ruedas = $veh->ruedas;
        $this->m_asientos = $veh->asientos;
        $this->m_pasajeros = $veh->pasajeros;
        $this->m_largo = $veh->largo;
        $this->m_ancho = $veh->ancho;
        $this->m_alto = $veh->alto;
        $this->m_color = $veh->color;
        $this->m_peso_neto = $veh->peso_neto;
        $this->m_peso_bruto = $veh->peso_bruto;
        $this->m_peso_util = $veh->peso_util;
        $this->estado = 'cargado';
        $this->dispatch('cargaVehiculo', $veh->id)->to($this->nombreDelInvocador ?? 'prueba');
        $this->vehiculos = null;
        $this->busqueda = false;
    }

    public function retornaNE($value)
    {
        if ($value) {
            return strtoupper($value);
        } else {
            return $value = 'NE';
        }
    }
    public function retornaSV($value)
    {
        if ($value) {
            return strtoupper($value);
        } else {
            return $value = 'S/V';
        }
    }
    public function retornaNulo($value)
    {
        if ($value) {
            return $value;
        } else {
            return Null;
        }
    }
}
