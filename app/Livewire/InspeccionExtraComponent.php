<?php

namespace App\Livewire;

use App\Models\TipoServicioExtra;
use App\Models\InspeccionExtra;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class InspeccionExtraComponent extends Component
{
    public $servicio_id; // ID del select
    public $servicio_seleccionado; // Objeto del modelo

    // Propiedades generales
    public $cliente_id, $vehiculo_id, $numero_certificado, $fecha_inspeccion;
    public $metodo_pago, $nro_comprobante, $comision_monto = 0, $resultado_final = 'APTO';
    public $vigencia_meses = 6;

    public $hermeticidadData = [];
    public $opacidadData = [];

    public $paso = 1; // 1: Formulario, 2: Documentos
    public $inspeccion_id; // Para los enlaces de impresión

    public function mount()
    {
        $this->fecha_inspeccion = date('Y-m-d');
    }

    // Escuchar el ID del cliente enviado desde FormCliente (Livewire 3)
    #[On('cargaCliente')]
    public function setCliente($clienteId)
    {
        $this->cliente_id = $clienteId;
    }

    // Escuchar el ID del vehículo enviado desde FormVehiculo
    #[On('vehiculoSeleccionado')]
    public function setVehiculo($id)
    {
        $this->vehiculo_id = $id;
    }

    // Escuchar cuando el hijo tenga los datos listos
    #[On('datosHermeticidadListos')]
    public function setDatosHermeticidad($datos)
    {
        $this->hermeticidadData = $datos;
        $this->procesarGuardado();
    }

    // Listener para recibir datos del hijo de opacidad
    #[On('datosOpacidadListos')]
    public function setDatosOpacidad($datos)
    {
        $this->opacidadData = $datos;
        $this->procesarGuardado();
    }

    // Al cambiar el select, buscamos el objeto para el switch
    public function updatedServicioId($value)
    {
        $this->servicio_seleccionado = TipoServicioExtra::find($value);
    }

    /*public function guardarInspeccion()
    {
        $this->validate([
            'cliente_id' => 'required',
            'vehiculo_id' => 'required',
            'servicio_id' => 'required',
            //'numero_certificado' => 'required',
            'numero_certificado' => 'required|unique:inspecciones_extras,numero_certificado',
            'metodo_pago' => 'required',
        ], [
            'cliente_id.required' => 'Debe seleccionar o registrar un cliente.',
            'vehiculo_id.required' => 'Debe seleccionar un vehículo.',
            'numero_certificado.required' => 'El número de certificado es obligatorio.',
            'numero_certificado.unique' => 'Este número de certificado ya ha sido utilizado anteriormente.',
        ]);

        if ($this->servicio_id == 1) {
            $this->dispatch('solicitarDatosHermeticidad');
        } elseif ($this->servicio_id == 2) {
            $this->dispatch('solicitarDatosOpacidad');
        } else {
            $this->procesarGuardado();
        }
    }*/

    public function guardarInspeccion()
    {
        try {
            $this->validate([
                'cliente_id' => 'required',
                'vehiculo_id' => 'required',
                'servicio_id' => 'required',
                'numero_certificado' => 'required|unique:inspecciones_extras,numero_certificado',
                'metodo_pago' => 'required',
            ], [
                'cliente_id.required' => 'Debe seleccionar o registrar un cliente.',
                'vehiculo_id.required' => 'Debe seleccionar un vehículo.',
                'numero_certificado.required' => 'El número de certificado es obligatorio.',
                'numero_certificado.unique' => 'Este número de certificado ya ha sido utilizado.',
                'metodo_pago.required' => 'Seleccione un método de pago.',
            ]);

            // Si pasa la validación, procedemos
            if ($this->servicio_id == 1) {
                $this->dispatch('solicitarDatosHermeticidad');
            } elseif ($this->servicio_id == 2) {
                $this->dispatch('solicitarDatosOpacidad');
            } else {
                $this->procesarGuardado();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturamos el primer error para mostrarlo en la alerta
            $primerError = collect($e->errors())->flatten()->first();

            $this->dispatch('minAlert', titulo: "¡ATENCIÓN!", mensaje: $primerError, icono: "warning");

            throw $e;
        }
    }

    public function procesarGuardado()
    {
        // Calculamos la fecha de próxima inspección
        // Si la fecha de inspección es hoy, le sumamos los meses de vigencia
        $proxima = date('Y-m-d', strtotime($this->fecha_inspeccion . " + {$this->vigencia_meses} month"));

        $inspeccion = InspeccionExtra::create([
            'cliente_id' => $this->cliente_id,
            'vehiculo_id' => $this->vehiculo_id,
            'tipo_servicio_id' => $this->servicio_id,
            'numero_certificado' => $this->numero_certificado,
            'fecha_inspeccion' => $this->fecha_inspeccion,
            'hora_inspeccion' => date('H:i:s'),
            'metodo_pago' => $this->metodo_pago,
            'comision_monto' => $this->comision_monto,
            'resultado_final' => $this->resultado_final,
            'vigencia_meses'     => $this->vigencia_meses, // Enviamos vigencia
            'proxima_inspeccion' => $proxima,
            'usuario_id' => Auth::id(),
        ]);

        if ($this->servicio_id == 1) {
            $inspeccion->detalleHermeticidad()->create($this->hermeticidadData);
        } elseif ($this->servicio_id == 2) {
            $inspeccion->detalleOpacidad()->create($this->opacidadData);
        }


        $this->inspeccion_id = $inspeccion->id; // Guardamos el ID
        $this->paso = 2; // Cambiamos al estado de Documentos
        $this->dispatch('minAlert', titulo: "¡ÉXITO!", mensaje: "Inspección guardada correctamente.", icono: "success");
    }


    public function calcularPromedioOpacidad()
    {
        $suma = $this->opacidad['ciclo1_k'] + $this->opacidad['ciclo2_k'] + $this->opacidad['ciclo3_k'] + $this->opacidad['ciclo4_k'];
        $this->opacidad['promedio_k'] = round($suma / 4, 3);
    }

    public function render()
    {
        return view('livewire.inspeccion-extra-component', [
            'servicios' => TipoServicioExtra::where('estado', 1)->get()
        ]);
    }
}
