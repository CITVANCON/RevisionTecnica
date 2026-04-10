<?php

namespace App\Livewire;

use App\Models\TipoServicioExtra;
use App\Models\InspeccionExtra;
use App\Models\Cliente;
use Livewire\Component;

class InspeccionExtraComponent extends Component
{
    public $servicio_id; // ID del select
    public $servicio_seleccionado; // Objeto del modelo
    
    // Propiedades generales
    public $cliente_id, $vehiculo_id, $numero_certificate, $fecha_inspeccion;
    public $metodo_pago, $nro_comprobante, $comision_monto = 0, $resultado_final = 'APTO';

    // Arrays de datos específicos (tal cual los definimos antes)
    public $hermeticidad = [
        'tapa' => 'A', 'compuerta' => 'A', 'tolva' => 'A', 'sellos' => 'A',
        'bisagras' => 'A', 'pistones' => 'A', 'mangueras' => 'A', 'remaches' => 'A',
        'tiempo_prueba' => '5:00', 'cant_bisagras' => 0, 'cant_pistones' => 0, 
        'cant_mangueras' => 0, 'cant_remaches' => 0
    ];

    public $opacidad = [
        'maquina' => '', 'marca_equipo' => '', 'modelo_equipo' => '', 'serie_equipo' => '',
        'ciclo1_k' => 0, 'ciclo2_k' => 0, 'ciclo3_k' => 0, 'ciclo4_k' => 0,
        'ciclo1_t' => 0, 'ciclo2_t' => 0, 'ciclo3_t' => 0, 'ciclo4_t' => 0,
        'promedio_k' => 0, 'limite_permitido' => 2.5
    ];

    // Escuchar cuando el componente 'form-vehiculo' emita el ID del vehículo
    protected $listeners = ['vehiculoSeleccionado' => 'setVehiculo'];

    public function mount() {
        $this->fecha_inspeccion = date('Y-m-d');
    }

    public function setVehiculo($id) {
        $this->vehiculo_id = $id;
    }

    // Al cambiar el select, buscamos el objeto para el switch
    public function updatedServicioId($value) {
        $this->servicio_seleccionado = TipoServicioExtra::find($value);
    }

    public function render() {
        return view('livewire.inspeccion-extra-component', [
            'servicios' => TipoServicioExtra::where('estado', 1)->get()
        ]);
    }
}
