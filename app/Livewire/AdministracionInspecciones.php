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

    public $cant = '10';

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

}
