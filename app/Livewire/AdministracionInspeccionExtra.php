<?php

namespace App\Livewire;

use App\Models\InspeccionExtra;
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
            'inspecciones' => $query->orderBy('fecha_inspeccion', 'desc')
                ->orderBy('id', 'desc')
                ->paginate($this->cant),
        ]);
    }

    public function editInspeccion($id)
    {
        $inspeccion = InspeccionExtra::findOrFail($id);
        $this->selected_id = $id;
        $this->metodo_pago = $inspeccion->metodo_pago;
        $this->nro_comprobante = $inspeccion->nro_comprobante;
        $this->comision_monto = $inspeccion->comision_monto ?? 0;
        $this->observaciones = $inspeccion->observaciones;

        $this->modalDetallesCaja = true;
    }
    public function updateInspeccion()
    {
        $this->validate([
            'metodo_pago' => 'required',
            'nro_comprobante' => 'nullable',
        ]);

        $inspeccion = InspeccionExtra::find($this->selected_id);
        $inspeccion->update([
            'metodo_pago' => $this->metodo_pago,
            'nro_comprobante' => $this->nro_comprobante,
            'comision_monto' => $this->comision_monto,
            'observaciones' => $this->observaciones,
        ]);

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
