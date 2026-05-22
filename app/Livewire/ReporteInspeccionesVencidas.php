<?php

namespace App\Livewire;

use App\Models\InspeccionMaestra;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InspeccionesVencidasExport;

class ReporteInspeccionesVencidas extends Component
{
    use WithPagination;

    public $filtro = 'vencidos';
    public $search = '';
    public $fecha_inicio = null;
    public $fecha_fin = null;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltro()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = InspeccionMaestra::query()
            ->whereNull('fecha_anulacion')
            ->where('resultado_estado', 'A');

        // 🔎 BUSQUEDA
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('placa_vehiculo', 'like', '%' . $this->search . '%')
                    ->orWhere('propietario_nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('propietario_documento', 'like', '%' . $this->search . '%');
            });
        }

        // 📅 RANGO DE FECHAS (prioridad alta)
        if ($this->fecha_inicio || $this->fecha_fin) {

            if ($this->fecha_inicio && $this->fecha_fin) {
                $query->whereBetween('fecha_vencimiento', [
                    $this->fecha_inicio,
                    $this->fecha_fin
                ]);
            } elseif ($this->fecha_inicio) {
                $query->whereDate('fecha_vencimiento', '>=', $this->fecha_inicio);
            } elseif ($this->fecha_fin) {
                $query->whereDate('fecha_vencimiento', '<=', $this->fecha_fin);
            }
        } else {

            // 🔴 FILTROS SOLO SI NO HAY RANGO
            if ($this->filtro === 'vencidos') {
                $query->whereDate('fecha_vencimiento', '<', now());
            }

            if ($this->filtro === '7dias') {
                $query->whereBetween('fecha_vencimiento', [
                    now(),
                    now()->addDays(7)
                ]);
            }

            if ($this->filtro === '15dias') {
                $query->whereBetween('fecha_vencimiento', [
                    now(),
                    now()->addDays(15)
                ]);
            }

            if ($this->filtro === '30dias') {
                $query->whereBetween('fecha_vencimiento', [
                    now(),
                    now()->addDays(30)
                ]);
            }
        }

        $inspecciones = $query
            ->orderBy('fecha_vencimiento', 'asc')
            ->paginate(15);

        // 📌 luego agregamos el campo calculado SIN romper paginate
        $inspecciones->getCollection()->transform(function ($item) {

            $dias = \Carbon\Carbon::parse($item->fecha_vencimiento)
                ->diffInDays(now(), false);

            $item->dias = (int) $dias;

            return $item;
        });

        return view('livewire.reporte-inspecciones-vencidas', [
            'inspecciones' => $inspecciones
        ]);
    }

    public function exportar()
    {
        return Excel::download(
            new InspeccionesVencidasExport(
                $this->filtro,
                $this->search,
                $this->fecha_inicio,
                $this->fecha_fin
            ),
            'inspecciones_vencidas.xlsx'
        );
    }
}
