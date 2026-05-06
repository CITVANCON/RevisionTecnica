<?php

namespace App\Livewire;

use App\Models\InspeccionMaestra;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class AdministracionInspecciones extends Component
{
    use WithPagination;

    // Filtros
    public $placa_vehiculo;
    public $resultado_estado, $fecha_inicio, $fecha_fin;
    public $cant = '10';

    public $modalDetallesCaja = false; // Controla el modal de Jetstream
    public $selected_id, $metodo_pago, $nro_comprobante, $nro_orden, $comision_monto, $observaciones;

    // Nuevas propiedades para pagos múltiples
    public $lista_pagos = []; 
    public $monto_total_inspeccion = 0;

    protected $queryString = [
        'placa_vehiculo' => ['except' => ''],
        'resultado_estado' => ['except' => ''],
        'cant' => ['except' => '10'],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['placa_vehiculo', 'resultado_estado', 'fecha_inicio', 'fecha_fin', 'cant'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = InspeccionMaestra::query();

        if ($this->placa_vehiculo) {
            $query->where('placa_vehiculo', 'like', '%' . $this->placa_vehiculo . '%');
        }

        if ($this->resultado_estado) {
            $query->where('resultado_estado', $this->resultado_estado);
        }

        if ($this->fecha_inicio && $this->fecha_fin) {
            $query->whereBetween('fecha_inspeccion', [$this->fecha_inicio, $this->fecha_fin]);
        }

        return view('livewire.administracion-inspecciones', [
            'inspecciones' => $query->with('pagos')
                ->orderBy('fecha_inspeccion', 'desc')
                ->orderBy('id_inspeccion_local', 'desc')
                ->paginate($this->cant),
        ]);
    }

    public function editInspeccion($id)
    {
        //$inspeccion = InspeccionMaestra::findOrFail($id);
        $inspeccion = InspeccionMaestra::with('pagos')->findOrFail($id);
        $this->selected_id = $id;
        $this->monto_total_inspeccion = $inspeccion->monto_total;
        // caragar campos 
        $this->metodo_pago = $inspeccion->metodo_pago;
        //$this->nro_comprobante = $inspeccion->nro_comprobante;
        $this->nro_orden = $inspeccion->nro_orden ?? 1;
        $this->comision_monto = $inspeccion->comision_monto ?? 0;
        $this->observaciones = $inspeccion->observaciones;

        // Cargar pagos existentes o inicializar uno vacío
        if ($inspeccion->pagos->count() > 0) {
            $this->lista_pagos = $inspeccion->pagos->map(fn($p) => [
                'metodo_pago' => $p->metodo_pago,
                'monto' => $p->monto,
                'nro_referencia' => $p->nro_referencia
            ])->toArray();
        } else {
            $this->lista_pagos = [['metodo_pago' => '', 'monto' => $this->monto_total_inspeccion, 'nro_referencia' => '']];
        }

        $this->modalDetallesCaja = true;
    }
    public function agregarPago()
    {
        $this->lista_pagos[] = ['metodo_pago' => '', 'monto' => 0, 'nro_referencia' => ''];
    }
    public function quitarPago($index)
    {
        unset($this->lista_pagos[$index]);
        $this->lista_pagos = array_values($this->lista_pagos);
    }
    /*public function updateInspeccion()
    {
        $this->validate([
            'metodo_pago' => 'required',
            'nro_comprobante' => 'nullable',
            'nro_orden' => 'nullable|integer',
        ]);
        $inspeccion = InspeccionMaestra::find($this->selected_id);
        $inspeccion->update([
            'metodo_pago' => $this->metodo_pago,
            'nro_comprobante' => $this->nro_comprobante,
            'nro_orden' => $this->nro_orden,
            'comision_monto' => $this->comision_monto,
            'observaciones' => $this->observaciones,
        ]);
        $this->modalDetallesCaja = false;
        $this->dispatch('minAlert', titulo: 'ÉXITO', mensaje: 'Actualizado correctamente', icono: 'success');
    }
    */
    public function updateInspeccion()
    {
        $this->validate([
            'lista_pagos.*.metodo_pago' => 'required',
            'lista_pagos.*.monto' => 'required',//|numeric|min:0.1
            'nro_comprobante' => 'nullable',
            'nro_orden' => 'nullable|integer',
        ], [
            'lista_pagos.*.metodo_pago.required' => 'Seleccione método.',
            'lista_pagos.*.monto.required' => 'Ingrese monto.',
        ]);

        // Validación de suma total
        $sumaPagos = collect($this->lista_pagos)->sum('monto');
        if (round($sumaPagos, 2) != round($this->monto_total_inspeccion, 2)) {
            $this->addError('suma_pagos', "La suma de pagos (S/ $sumaPagos) no coincide con el total (S/ $this->monto_total_inspeccion)");
            return;
        }

        $inspeccion = InspeccionMaestra::find($this->selected_id);
        
        // 1. Actualizar datos de la inspección
        $inspeccion->update([
            'nro_comprobante' => $this->nro_comprobante,
            'nro_orden' => $this->nro_orden,
            'comision_monto' => $this->comision_monto,
            'observaciones' => $this->observaciones,
            // Opcional: podrías guardar el primer método en 'metodo_pago' para compatibilidad legacy
            //'metodo_pago' => $this->lista_pagos[0]['metodo_pago'] ?? null 
        ]);

        // 2. Sincronizar pagos
        $inspeccion->pagos()->delete(); // Limpiamos anteriores para evitar duplicidad al editar
        $userId = Auth::id();
        foreach ($this->lista_pagos as $pago) {
            $inspeccion->pagos()->create([
                'metodo_pago' => $pago['metodo_pago'],
                'monto' => $pago['monto'],
                'nro_referencia' => $pago['nro_referencia'],
                'user_id' => $userId,
            ]);
        }

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
            $inspeccion = InspeccionMaestra::findOrFail($id);

            $inspeccion->delete();

            $this->dispatch('minAlert', titulo: 'ELIMINADO', mensaje: 'Registro eliminado correctamente', icono: 'success');

            // Opcional: resetear paginación si se queda sin registros
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('minAlert', titulo: 'ERROR', mensaje: 'No se pudo eliminar el registro', icono: 'error');
        }
    }
}
