<?php

namespace App\Livewire\RRHH;

use Livewire\Component;
use App\Models\Planilla;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MisPlanillas extends Component
{
    public function descargar($ruta, $nombre)
    {
        return Storage::disk('public')->download($ruta, $nombre);
    }

    /*public function render()
    {
        // Obtenemos las planillas a través del contrato del usuario autenticado
        $planillas = Planilla::whereHas('contrato', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['archivos'])
            ->orderBy('fecha_pago', 'desc') // Suponiendo que tienes este campo
            ->get();

        return view('livewire.r-r-h-h.mis-planillas', compact('planillas'));
    }*/

    public function render()
    {
        $planillas = Planilla::whereHas('contrato', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['archivos'])
            ->orderBy('periodo', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->periodo->format('Y'); // Agrupar por Año
            })
            ->map(function ($yearGroup) {
                return $yearGroup->groupBy(function ($item) {
                    return $item->periodo->translatedFormat('F'); // Agrupar por Mes
                });
            });

        return view('livewire.r-r-h-h.mis-planillas', compact('planillas'));
    }
}
