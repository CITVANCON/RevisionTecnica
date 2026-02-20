<?php

namespace App\Livewire\RRHH;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Planilla;

class ListaPlanilla extends Component
{
    use WithPagination;

    public $search = '';
    public $cant = '10';
    public $periodoSeleccionado;

    //protected $listeners = ['refresh-planilla' => '$refresh'];

    public function mount()
    {
        // Por defecto, sugerimos la fecha de hoy para el filtro
        $this->periodoSeleccionado = now()->format('Y-m-d');
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingPeriodoSeleccionado() { $this->resetPage(); }

    #[On('refresh-planilla')]
    public function render()
    {
        $query = Planilla::query();

        if ($this->periodoSeleccionado) {
            // Filtro por FECHA EXACTA del periodo
            $query->whereDate('periodo', $this->periodoSeleccionado)
                ->whereHas('contrato.user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('dni', 'like', '%' . $this->search . '%');
                })
                ->with(['contrato.user', 'archivos'])
                ->orderBy('created_at', 'desc');

            $planillas = $query->paginate($this->cant);
        } else {
            // Si limpian el filtro de fecha, no mostramos nada para mantener el orden
            $planillas = Planilla::where('id', 0)->paginate($this->cant);
        }

        return view('livewire.r-r-h-h.lista-planilla', [
            'planillas' => $planillas
        ]);
    }
}
