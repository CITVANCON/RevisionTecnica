<?php

namespace App\Livewire;

use App\Models\InspeccionMaestra;
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
            'inspecciones' => $query->orderBy('fecha_inspeccion', 'desc')
                ->orderBy('id_inspeccion_local', 'desc')
                ->paginate($this->cant),
        ]);
    }

    public function editInspeccion($id)
    {
        $inspeccion = InspeccionMaestra::findOrFail($id);
        $this->selected_id = $id;
        $this->metodo_pago = $inspeccion->metodo_pago;
        $this->nro_comprobante = $inspeccion->nro_comprobante;
        $this->nro_orden = $inspeccion->nro_orden ?? 1;
        $this->comision_monto = $inspeccion->comision_monto ?? 0;
        $this->observaciones = $inspeccion->observaciones;

        $this->modalDetallesCaja = true;
    }
    public function updateInspeccion()
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
