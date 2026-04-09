<?php

namespace App\Livewire;

use App\Models\InspeccionMaestra;
use Carbon\Carbon;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteMtc extends Component
{
    public $mes;
    public $anio;

    public function mount()
    {
        $this->mes = Carbon::now()->month;
        $this->anio = Carbon::now()->year;
    }

    /*public function render()
    {
        // 1. Consulta base para el mes seleccionado
        $queryBase = InspeccionMaestra::whereYear('fecha_inspeccion', $this->anio)
            ->whereMonth('fecha_inspeccion', $this->mes)
            ->orderBy('fecha_inspeccion', 'asc')
            ->orderBy('id_inspeccion_local', 'asc');

        // 2. Aprobados (Válidos y sin anulación)
        $inspeccionados = (clone $queryBase)
            ->where('resultado_estado', 'A')
            ->whereNull('fecha_anulacion')
            ->get();

        // 3. Desaprobados (Válidos y sin anulación)
        $desaprobados = (clone $queryBase)
            ->where('resultado_estado', 'D')
            ->whereNull('fecha_anulacion')
            ->get();

        // 4. Anulados (Basado en fecha de anulación para el reporte MTC)
        $anulados = InspeccionMaestra::whereYear('fecha_anulacion', $this->anio)
            ->whereMonth('fecha_anulacion', $this->mes)
            ->whereNotNull('fecha_anulacion')
            ->orderBy('fecha_anulacion', 'asc')
            ->get();

        // 5. CORRECCIÓN: Los 9 huérfanos (Basado en tu consulta SQL)
        // Registros que NO tienen A ni D, son NULL y NO están anulados.
        $huerfanos = (clone $queryBase)
            ->whereNull('fecha_anulacion')
            ->where(function($query) {
                $query->whereNotIn('resultado_estado', ['A', 'D'])
                      ->orWhereNull('resultado_estado');
            })
            ->get();

        return view('livewire.reporte-mtc', [
            'inspeccionados' => $inspeccionados,
            'desaprobados'   => $desaprobados,
            'anulados'       => $anulados,
            'huerfanos'      => $huerfanos,
            'total_mes'      => $inspeccionados->count() + $desaprobados->count() + $anulados->count() + $huerfanos->count()
        ]);
    }*/


    private function obtenerDatosReporte()
    {
        $queryBase = InspeccionMaestra::whereYear('fecha_inspeccion', $this->anio)
            ->whereMonth('fecha_inspeccion', $this->mes)
            ->orderBy('fecha_inspeccion', 'asc')
            ->orderBy('id_inspeccion_local', 'asc');

        $inspeccionados = (clone $queryBase)
            ->where('resultado_estado', 'A')
            ->whereNull('fecha_anulacion')
            ->get();

        $desaprobados = (clone $queryBase)
            ->where('resultado_estado', 'D')
            ->whereNull('fecha_anulacion')
            ->get();

        $anulados = InspeccionMaestra::whereYear('fecha_anulacion', $this->anio)
            ->whereMonth('fecha_anulacion', $this->mes)
            ->whereNotNull('fecha_anulacion')
            ->orderBy('fecha_anulacion', 'asc')
            ->get();

        $huerfanos = (clone $queryBase)
            ->whereNull('fecha_anulacion')
            ->where(function ($query) {
                $query->whereNotIn('resultado_estado', ['A', 'D'])
                    ->orWhereNull('resultado_estado');
            })
            ->get();

        return [
            'inspeccionados' => $inspeccionados,
            'desaprobados'   => $desaprobados,
            'anulados'       => $anulados,
            'huerfanos'      => $huerfanos,
            'total_mes'      => $inspeccionados->count() + $desaprobados->count() + $anulados->count() + $huerfanos->count(),
            'mes_nombre'     => Carbon::create()->month($this->mes)->translatedFormat('F'),
            'anio'           => $this->anio,
        ];
    }

    public function descargarPdf()
    {
        $data = $this->obtenerDatosReporte();

        $pdf = Pdf::loadView('pdf.reporte-mtc-documento', $data)
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "Reporte_MTC_{$this->mes}_{$this->anio}.pdf");
    }

    public function render()
    {
        return view('livewire.reporte-mtc', $this->obtenerDatosReporte());
    }
}
