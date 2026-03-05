<?php

namespace App\Livewire;

use App\Models\InspeccionMaestra;
use Livewire\Component;
use Livewire\WithPagination;

class AdministracionInspecciones extends Component
{
    use WithPagination;

    // Filtros
    public $placa_vehiculo;
    public $resultado_estado;
    public $fecha_inicio;
    public $fecha_fin;

    protected $queryString = [
        'placa_vehiculo' => ['except' => ''],
        'resultado_estado' => ['except' => ''],
    ];

    public function updating()
    {
        $this->resetPage();
    }

    public function updatedPlacaVehiculo()
    {
        $this->resetPage();
    }

    public function updatedResultadoEstado()
    {
        $this->resetPage();
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
            'inspecciones' => $query->orderBy('fecha_inspeccion', 'desc')->paginate(10),
        ]);
    }

}
