<?php

namespace App\Livewire;

use App\Models\InspeccionMaestra;
use Carbon\Carbon;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;

class ReporteMtc extends Component
{
    public $mes;
    public $anio;

    public function mount()
    {
        $this->mes = Carbon::now()->month;
        $this->anio = Carbon::now()->year;
    }

    private function obtenerDatosReporte()
    {
        $mesInt = (int) $this->mes;
        $anioInt = (int) $this->anio;
        // 1. Consulta base para el mes seleccionado
        $queryBase = InspeccionMaestra::whereYear('fecha_inspeccion', $anioInt)
            ->whereMonth('fecha_inspeccion', $mesInt)
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
        $anulados = InspeccionMaestra::whereYear('fecha_anulacion', $anioInt)
            ->whereMonth('fecha_anulacion', $mesInt)
            ->whereNotNull('fecha_anulacion')
            ->orderBy('fecha_anulacion', 'asc')
            ->get();
        // 5. CORRECCIÓN: Los 9 huérfanos (Basado en tu consulta SQL)
        // Registros que NO tienen A ni D, son NULL y NO están anulados.
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
            'mes_nombre'     => Carbon::create()->month($mesInt)->translatedFormat('F'),
            'anio'           => $anioInt,
        ];
    }

    public function descargarPdf()
    {
        $data = $this->obtenerDatosReporte();

        $pdf = Pdf::loadView('pdf.reporte-mtc-documento', $data)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "Reporte_MTC_{$this->mes}_{$this->anio}.pdf");
    }

    /*public function descargarPdf()
    {
        $data = $this->obtenerDatosReporte();

        // 1. Generar el PDF normal con DomPDF
        // Importante: La vista debe tener el comunicado primero y luego las tablas
        $domPdf = Pdf::loadView('pdf.reporte-mtc-documento', $data)
                    ->setPaper('a4', 'portrait') // Empezamos en vertical
                    ->output();

        // Guardar temporalmente el PDF generado para procesarlo con FPDI
        $tempPath = storage_path('app/public/temp_reporte.pdf');
        file_put_contents($tempPath, $domPdf);

        // 2. Usar FPDI para fusionar con la hoja membretada
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($tempPath);
        $fondoPath = public_path('images/HOJAMEMBRETADA_CITV.pdf');

        for ($i = 1; $i <= $pageCount; $i++) {
            // Importar la página del reporte generado
            $template = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($template);

            // Añadir página (Si es la pág 1 es Vertical, las otras pueden ser Landscape si lo definiste)
            // Por ahora, asumamos Vertical para el comunicado
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

            // Si es la PRIMERA PÁGINA, ponemos el fondo membretado
            if ($i == 1 && file_exists($fondoPath)) {
                $pdf->setSourceFile($fondoPath);
                $fondoImportado = $pdf->importPage(1);
                $pdf->useTemplate($fondoImportado, 0, 0, 210, 297); // Ajustar a A4
                
                // Volvemos al archivo del reporte para traer el texto
                $pdf->setSourceFile($tempPath);
                $template = $pdf->importPage($i);
            }

            // Colocar el contenido del reporte encima
            $pdf->useTemplate($template);
        }

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('S');
        }, "Reporte_MTC_{$this->mes}_{$this->anio}.pdf");
    }*/

    public function render()
    {
        return view('livewire.reporte-mtc', $this->obtenerDatosReporte());
    }
}


/*
 public function render()
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
    }
*/