<?php

namespace App\Livewire;

use App\Models\InspeccionExtra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Component;

class AdministracionInspeccionExtra extends Component
{
    use WithPagination;

    // Filtros
    public $placa_vehiculo;
    public $resultado_final, $fecha_inicio, $fecha_fin;
    public $cant = '10';

    public $modalDetallesCaja = false; // Controla el modal de Jetstream
    public $selected_id, $metodo_pago, $nro_comprobante, $comision_monto, $observaciones;

    public $pagos_registrados = [];
    public $monto_total_inspeccion = 0;

    protected $queryString = [
        'placa_vehiculo' => ['except' => ''],
        'resultado_final' => ['except' => ''],
        'cant' => ['except' => '10'],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['placa_vehiculo', 'resultado_final', 'fecha_inicio', 'fecha_fin', 'cant'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = InspeccionExtra::query();

        if ($this->placa_vehiculo) {
            $query->whereHas('vehiculo', function ($q) {
                $q->where('placa', 'like', '%' . $this->placa_vehiculo . '%');
            });
        }

        if ($this->resultado_final) {
            $query->where('resultado_final', $this->resultado_final);
        }

        if ($this->fecha_inicio && $this->fecha_fin) {
            $query->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
        }

        return view('livewire.administracion-inspeccion-extra', [
            'inspecciones' => $query->with('pagos')
                ->orderBy('fecha_inspeccion', 'desc')
                ->orderBy('id', 'desc')
                ->paginate($this->cant),
        ]);
    }

    public function editInspeccion($id)
    {
        //$inspeccion = InspeccionExtra::findOrFail($id);
        $inspeccion = InspeccionExtra::with('pagos', 'vehiculo')->findOrFail($id);
        $this->selected_id = $id;
        $this->monto_total_inspeccion = $inspeccion->monto_total;
        // Cargamos los datos básicos
        $this->metodo_pago = $inspeccion->metodo_pago;
        //$this->nro_comprobante = $inspeccion->nro_comprobante;
        $this->comision_monto = $inspeccion->comision_monto ?? 0;
        $this->observaciones = $inspeccion->observaciones;

        // Cargamos los pagos para visualizarlos en el modal
        //$this->pagos_registrados = $inspeccion->pagos->toArray();
        // Cargar pagos existentes o inicializar uno vacío
        if ($inspeccion->pagos->count() > 0) {
            $this->pagos_registrados = $inspeccion->pagos->map(fn($p) => [
                'metodo_pago' => $p->metodo_pago,
                'monto' => $p->monto,
                'nro_referencia' => $p->nro_referencia
            ])->toArray();
        } else {
            $this->pagos_registrados = [['metodo_pago' => '', 'monto' => $this->monto_total_inspeccion, 'nro_referencia' => '']];
        }

        $this->modalDetallesCaja = true;
    }
    public function agregarPago()
    {
        $this->pagos_registrados[] = ['metodo_pago' => '', 'monto' => 0, 'nro_referencia' => ''];
    }
    public function quitarPago($index)
    {
        unset($this->pagos_registrados[$index]);
        $this->pagos_registrados = array_values($this->pagos_registrados);
    }
    public function updateInspeccion()
    {
        /*$this->validate([
            //'metodo_pago' => 'required',
            'nro_comprobante' => 'nullable',
            'comision_monto' => 'numeric|min:0',
        ]);*/

        $this->validate([
            'nro_comprobante' => 'nullable',
            'comision_monto' => 'numeric|min:0',
            'pagos_registrados.*.metodo_pago' => 'required', // Evita que guarden métodos vacíos
            'pagos_registrados.*.monto' => 'required|numeric|min:0.1', // Evita montos cero
        ], [
            'pagos_registrados.*.metodo_pago.required' => 'Seleccione un método.',
            'pagos_registrados.*.monto.min' => 'El monto debe ser mayor a 0.',
        ]);

        // Validación de suma total con casteo a float
        $sumaPagos = collect($this->pagos_registrados)->sum(function($pago) {
            return (float) $pago['monto'];
        });

        if (round($sumaPagos, 2) != round((float)$this->monto_total_inspeccion, 2)) {
            $this->addError('suma_pagos', "La suma (S/ " . number_format($sumaPagos, 2) . ") no coincide con el total (S/ " . number_format($this->monto_total_inspeccion, 2) . ")");
            return;
        }

        $inspeccion = InspeccionExtra::find($this->selected_id);
        // Actualizar datos de la inspección
        $inspeccion->update([
            //'metodo_pago' => $this->metodo_pago,
            'nro_comprobante' => $this->nro_comprobante,
            'comision_monto' => $this->comision_monto,
            'observaciones' => $this->observaciones,
        ]);

        // Sincronizar pagos (Transacción recomendada para evitar quedar a medias si falla algo)
        DB::transaction(function () use ($inspeccion) {
            $inspeccion->pagos()->delete(); 
            $userId = Auth::id();

            foreach ($this->pagos_registrados as $pago) {
                $inspeccion->pagos()->create([
                    'metodo_pago' => $pago['metodo_pago'],
                    'monto' => $pago['monto'],
                    'nro_referencia' => $pago['nro_referencia'],
                    'user_id' => $userId,
                ]);
            }
        });

        $this->modalDetallesCaja = false;
        $this->dispatch('minAlert', titulo: 'ÉXITO', mensaje: 'Actualizado correctamente', icono: 'success');
    }


    #[On('deleteConfirmed')]
    public function deleteConfirmed($id)
    {
        $this->deleteInspeccion($id);
    }
    public function deleteInspeccion($id)
    {
        try {
            $inspeccion = InspeccionExtra::findOrFail($id);

            $inspeccion->delete();

            $this->dispatch('minAlert', titulo: 'ELIMINADO', mensaje: 'Registro eliminado correctamente', icono: 'success');

            // Opcional: resetear paginación si se queda sin registros
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('minAlert', titulo: 'ERROR', mensaje: 'No se pudo eliminar el registro', icono: 'error');
        }
    }
}
