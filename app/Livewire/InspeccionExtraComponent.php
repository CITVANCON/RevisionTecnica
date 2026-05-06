<?php

namespace App\Livewire;

use App\Models\TipoServicioExtra;
use App\Models\InspeccionExtra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\On;

class InspeccionExtraComponent extends Component
{
    public $servicio_id; // ID del select
    public $servicio_seleccionado; // Objeto del modelo

    // Propiedades generales
    public $cliente_id, $vehiculo_id, $numero_certificado, $fecha_inspeccion;
    public $monto_total = 0, $metodo_pago, $nro_comprobante, $comision_monto = 0, $resultado_final;
    public $vigencia_meses = 6;
    public $estado = 'COMPLETADO';

    public $hermeticidadData = [];
    public $opacidadData = [];

    public $paso = 1; // 1: Formulario, 2: Documentos
    public $inspeccion_generada;
    public $inspeccion_id; // Para los enlaces de impresión

    // Reemplazamos $metodo_pago por un array para pagos múltiples
    public $pagos_multiples = [];

    public function mount()
    {
        $this->fecha_inspeccion = date('Y-m-d');
        $this->resultado_final = "";
        // Inicializar con una fila de pago vacía
        $this->agregarPago();
    }

    public function agregarPago()
    {
        //$this->pagos_multiples[] = ['metodo_pago' => '', 'monto' => 0];
        $this->pagos_multiples[] = [
            'metodo_pago' => '', 
            'monto' => 0, 
            'nro_referencia' => ''
        ];
    }
    public function eliminarPago($index)
    {
        unset($this->pagos_multiples[$index]);
        $this->pagos_multiples = array_values($this->pagos_multiples);
        $this->actualizarMontoTotal();
    }
    public function actualizarMontoTotal()
    {
        $this->monto_total = array_sum(array_column($this->pagos_multiples, 'monto'));
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

        // 1. Verificamos si hay algún elemento "Observado" (O)
        // Buscamos en todo el array si existe el valor 'O'
        $tieneObservaciones = in_array('O', $datos);

        // 2. Verificamos si hay faltantes en la cuantificación
        $faltantes = (int)$datos['faltas_bisagras'] + (int)$datos['faltas_pistones'] + (int)$datos['faltas_mangueras'] + (int)$datos['faltas_remaches'];
        // LÓGICA DE NEGOCIO:
        // Si tiene observaciones O si tiene elementos faltantes, es NO APTO.
        $this->resultado_final = ($tieneObservaciones || $faltantes > 0) ? 'DESAPROBADO' : 'APROBADO';

        $this->procesarGuardado();
    }
    #[On('calculoPreliminarHermeticidad')]
    public function actualizarResultadoHermeticidad($resultado)
    {
        $this->resultado_final = $resultado;
    }


    // Listener para recibir datos del hijo de opacidad
    #[On('datosOpacidadListos')]
    public function setDatosOpacidad($datos)
    {
        $this->opacidadData = $datos;
        // LÓGICA DE NEGOCIO: Determinamos el resultado basado en el límite
        // Si el promedio_k es menor o igual al límite, es APTO.
        if ((float)$datos['promedio_k'] <= (float)$datos['limite_permitido']) {
            $this->resultado_final = 'APROBADO';
        } else {
            $this->resultado_final = 'DESAPROBADO';
        }

        $this->procesarGuardado();
    }
    #[On('calculoPreliminarOpacidad')]
    public function actualizarResultadoPreliminar($promedio, $limite = 2.5)
    {
        $this->resultado_final = ($promedio <= $limite) ? 'APROBADO' : 'DESAPROBADO';
    }

    // Al cambiar el select, buscamos el objeto para el switch
    public function updatedServicioId($value)
    {
        $this->servicio_seleccionado = TipoServicioExtra::find($value);
        // Limpiamos los datos previos para que no haya cruces
        $this->resultado_final = "";
        $this->hermeticidadData = [];
        $this->opacidadData = [];
        if ($this->servicio_seleccionado) {
            // Lógica para obtener el último certificado de este servicio
            $ultimo = InspeccionExtra::where('tipo_servicio_id', $value)->latest('id')->first();

            if ($ultimo) {
                // Si existe, extraemos el número, le sumamos 1 y lo formateamos (ej. 5 dígitos)
                $nuevoNumero = (int)$ultimo->numero_certificado + 1;
                $this->numero_certificado = str_pad($nuevoNumero, 5, '0', STR_PAD_LEFT);
            } else {
                // Si es el primer certificado del servicio, empezamos en 00001 o el que prefieras
                $this->numero_certificado = '00001';
            }
        } else {
            $this->numero_certificado = "";
        }
    }

    public function guardarInspeccion()
    {
        try {
            $this->validate([
                'cliente_id' => 'required',
                'vehiculo_id' => 'required',
                'servicio_id' => 'required',
                'numero_certificado' => ['required', Rule::unique('inspecciones_extras', 'numero_certificado')->where('tipo_servicio_id', $this->servicio_id)],
                'resultado_final' => 'required|in:APROBADO,DESAPROBADO',
                'pagos_multiples.*.metodo_pago' => 'required',
                'pagos_multiples.*.monto' => 'required|numeric|min:0.1',
            ], [
                'cliente_id.required' => 'Debe seleccionar o registrar un cliente.',
                'vehiculo_id.required' => 'Debe seleccionar un vehículo.',
                'numero_certificado.required' => 'El número de certificado es obligatorio.',
                'numero_certificado.unique' => 'Este número de certificado ya existe para el servicio seleccionado.',
                'resultado_final.required' => 'El resultado de la inspección no ha sido determinado.',
                'pagos_multiples.*.metodo_pago.required' => 'Seleccione el método para todos los pagos.',
                'pagos_multiples.*.monto.required' => 'Falta ingresar el monto en uno de los pagos.',
                'pagos_multiples.*.monto.numeric' => 'El monto debe ser un número.',
                'pagos_multiples.*.monto.min' => 'El monto de cada pago debe ser mayor a 0.',
            ]);

            // Validar que la suma sea coherente (Opcional pero recomendado)
            if ($this->monto_total <= 0) {
                $this->dispatch('minAlert', titulo: "¡ATENCIÓN!", mensaje: "El monto total debe ser mayor a cero.", icono: "warning");
                return;
            }

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
        try {
            DB::beginTransaction(); // Importar DB al inicio

            $proxima = date('Y-m-d', strtotime($this->fecha_inspeccion . " + {$this->vigencia_meses} month"));

            $this->inspeccion_generada = InspeccionExtra::create([
                'cliente_id' => $this->cliente_id,
                'vehiculo_id' => $this->vehiculo_id,
                'tipo_servicio_id' => $this->servicio_id,
                'numero_certificado' => $this->numero_certificado,
                'fecha_inspeccion' => $this->fecha_inspeccion,
                'hora_inspeccion' => date('H:i:s'),
                'monto_total' => $this->monto_total,
                //'metodo_pago' => $this->metodo_pago,
                'comision_monto' => $this->comision_monto,
                'resultado_final' => $this->resultado_final,
                'vigencia_meses'     => $this->vigencia_meses,
                'proxima_inspeccion' => $proxima,
                'usuario_id' => Auth::id(),
                'estado' => 'COMPLETADO', // Nuevo
            ]);

            // GUARDAR CADA PAGO EN LA TABLA RELACIONADA
            foreach ($this->pagos_multiples as $pago) {
                $this->inspeccion_generada->pagos()->create([
                    'metodo_pago' => $pago['metodo_pago'],
                    'monto' => $pago['monto'],
                    'nro_referencia' => $pago['nro_referencia'],
                    'user_id' => Auth::id(),
                ]);
            }

            if ($this->servicio_id == 1) {
                $this->inspeccion_generada->detalleHermeticidad()->create($this->hermeticidadData);
            } elseif ($this->servicio_id == 2) {
                $this->inspeccion_generada->detalleOpacidad()->create($this->opacidadData);
            }

            DB::commit();

            //$this->inspeccion_id = $inspeccion->id;
            $this->inspeccion_generada->refresh();
            $this->paso = 2;
            $this->dispatch('minAlert', titulo: "¡ÉXITO!", mensaje: "Inspección guardada correctamente.", icono: "success");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('minAlert', titulo: "ERROR", mensaje: "No se pudo guardar: " . $e->getMessage(), icono: "error");
        }
    }

    public function render()
    {
        return view('livewire.inspeccion-extra-component', [
            'servicios' => TipoServicioExtra::where('estado', 1)->get()
        ]);
    }
}


/*  
    public function guardarInspeccion()
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
*/