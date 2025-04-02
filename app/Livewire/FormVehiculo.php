<?php

namespace App\Livewire;

use App\Models\Vehiculo;
use Livewire\Component;

class FormVehiculo extends Component
{
    public $nombreDelInvocador;

    //VARIABLES DEL VEHICULO
    public $placa, $propietario, $categoria, $marca, $modelo, $anio_fabricacion,
        $kilometraje, $combustible, $vin_serie, $numero_motor, $carroceria,
        $marca_carroceria, $ejes, $ruedas, $asientos, $pasajeros, $largo, $ancho,
        $alto, $color, $peso_neto, $peso_bruto, $peso_util;
    //VARIABLES DEL COMPONENTE
    public Vehiculo $vehiculo;
    public $estado, $vehiculos, $busqueda, $tipoServicio;

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

        if (
            $vehiculo = vehiculo::create(
                [
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
                ]
            )
        ) {
            $this->vehiculo = $vehiculo;
            $this->estado = 'cargado';
            //$this->emit("minAlert", ["titulo" => "¡BUEN TRABAJO!", "mensaje" => 'El vehículo con placa ' . $vehiculo->placa . ' se registro correctamente.', "icono" => "success"]);
            $this->dispatch('minAlert',
                titulo: "¡BUEN TRABAJO!",
                mensaje: "El vehículo con placa {$vehiculo->placa} se registró correctamente.",
                icono: "success"
            );

            if ($this->nombreDelInvocador != null) {
                //$this->emitTo($this->nombreDelInvocador, 'cargaVehiculo', $vehiculo->id);
                $this->dispatch('cargaVehiculo', $vehiculo->id)->to($this->nombreDelInvocador);
            } else {
                //$this->emitTo('prueba', 'cargaVehiculo', $vehiculo->id);
                $this->dispatch('cargaVehiculo', $vehiculo->id)->to('prueba');
            }
        } else {
            //$this->emit("minAlert", ["titulo" => "AVISO DEL SISTEMA", "mensaje" => "Ocurrio un error al guardar los datos del vehículo", "icono" => "warning"]);
            $this->dispatch('minAlert',
                titulo: "AVISO DEL SISTEMA",
                mensaje: "Ocurrio un error al guardar los datos del vehículo",
                icono: "warning"
            );
        }
    }

    public function actualizarVehiculo()
    {
        $this->validate(
            [
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
            ]
        );

        if (
            $this->vehiculo
            ->update([
                "placa" => strtoupper($this->vehiculo->placa),
                "propietario" => strtoupper($this->vehiculo->propietario),
                "categoria" => strtoupper($this->vehiculo->categoria),
                "marca" => $this->retornaNE($this->vehiculo->marca),
                "modelo" => $this->retornaNE($this->vehiculo->modelo),
                "anio_fabricacion" => $this->retornaNulo($this->vehiculo->anio_fabricacion),
                "kilometraje" => $this->retornaNE($this->vehiculo->kilometraje),
                "combustible" => $this->retornaNE($this->vehiculo->combustible),
                "vin_serie" => $this->retornaNE($this->vehiculo->vin_serie),
                "numero_motor" => $this->retornaNE($this->vehiculo->numero_motor),
                "ejes" => $this->retornaNulo($this->vehiculo->ejes),
                "ruedas" => $this->retornaNulo($this->vehiculo->ruedas),
                "asientos" => $this->retornaNulo($this->vehiculo->asientos),
                "pasajeros" => $this->retornaNulo($this->vehiculo->pasajeros),
                "largo" => $this->retornaNulo($this->vehiculo->largo),
                "ancho" => $this->retornaNulo($this->vehiculo->ancho),
                "alto" => $this->retornaNulo($this->vehiculo->alto),
                "color" => $this->retornaNE($this->vehiculo->color),
                "peso_neto" => $this->retornaNulo($this->vehiculo->peso_neto),
                "peso_bruto" => $this->retornaNulo($this->vehiculo->peso_bruto),
                "peso_util" => $this->retornaNulo($this->vehiculo->peso_util),
            ])
        ) {
            $this->estado = 'cargado';
            //$this->emit("minAlert", ["titulo" => "¡BUEN TRABAJO!", "mensaje" => "Datos del vehículo actualizados correctamente", "icono" => "success"]);
            $this->dispatch('minAlert',
                titulo: "¡BUEN TRABAJO!",
                mensaje: "Datos del vehículo actualizados correctamente",
                icono: "success"
            );
        } else {
            //$this->emit("minAlert", ["titulo" => "AVISO DEL SISTEMA", "mensaje" => "Ocurrio un error al actualizar los datos del vehículo", "icono" => "warning"]);
            $this->dispatch('minAlert',
                titulo: "AVISO DEL SISTEMA",
                mensaje: "Ocurrio un error al actualizar los datos del vehículo",
                icono: "warning"
            );
        }
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

    //revisa la existencia del vehiculo en nuestra base de datos y los devuelve en caso de encontrarlo
    public function buscarVehiculo()
    {
        $this->validate(['placa' => 'min:3|max:7']);

        $vehiculos = vehiculo::where('placa', 'like', '%' . $this->placa . '%')->get();
        if ($vehiculos->count() > 0) {
            $this->busqueda = true;
            $this->vehiculos = $vehiculos;
        } else {
            //$this->emit("minAlert", ["titulo" => "AVISO DEL SISTEMA", "mensaje" => "No se encontro ningún vehículo con la placa ingresada", "icono" => "warning"]);
            $this->dispatch(
                'minAlert',
                titulo: "AVISO DEL SISTEMA",
                mensaje: "No se encontró ningún vehículo con la placa ingresada",
                icono: "warning"
            );
        }
    }

    public function seleccionaVehiculo(vehiculo $veh)
    {
        $this->vehiculo = $veh;
        $this->estado = 'cargado';
        /*if ($this->nombreDelInvocador != null) {
            //$this->emitTo($this->nombreDelInvocador, 'cargaVehiculo', $veh->id);
            $this->dispatch('cargaVehiculo', $veh->id)->to($this->nombreDelInvocador);
        } else {
            $this->dispatch('prueba', 'cargaVehiculo', $veh->id);
            //$this->dispatch('cargaVehiculo', $veh->id)->to('prueba');
        }*/
        $this->dispatch('cargaVehiculo', $veh->id)->to($this->nombreDelInvocador ?? 'prueba');
        $this->vehiculos = null;
        $this->busqueda = false;
    }
}
