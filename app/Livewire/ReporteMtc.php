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
        // 5. CORRECCIÓN: Los huérfanos (Basado en tu consulta SQL)
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

    /*public function descargarPdf()
    {
        $data = $this->obtenerDatosReporte();

        $pdf = Pdf::loadView('pdf.reporte-mtc-documento', $data)
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "Reporte_MTC_{$this->mes}_{$this->anio}.pdf");
    }*/
    
    public function descargarPdf()
    {
        $data = $this->obtenerDatosReporte();
        $imagenBase64 = $this->getImagenBase64();
        $finalPdf = new \setasign\Fpdi\Fpdi();

        // Definimos los asuntos específicos para cada sección
        $asuntos = [
            1 => "INFORME DE LOS VEHÍCULOS INSPECCIONADOS (APROBADOS)",
            2 => "INFORME DE LOS VEHÍCULOS INSPECCIONADOS (DESAPROBADOS)",
            3 => "INFORME DE LOS FORMATOS Y CALCOMANIAS (ANULADAS)",
        ];

        for ($seccion = 1; $seccion <= 3; $seccion++) {
            
            // --- A. GENERAR HOJA MEMBRETADA CON COMUNICADO ESPECÍFICO ---
            $htmlCarta = $this->generarHtmlCarta($asuntos[$seccion], $imagenBase64);
            
            $pdfVertical = Pdf::loadHTML($htmlCarta)->setPaper('a4', 'portrait')->output();
            $tmpVertical = tempnam(sys_get_temp_dir(), 'vert');
            file_put_contents($tmpVertical, $pdfVertical);

            $finalPdf->setSourceFile($tmpVertical);
            $finalPdf->AddPage('P', 'A4');
            $finalPdf->useTemplate($finalPdf->importPage(1));
            unlink($tmpVertical);

            // --- B. GENERAR TABLA (HORIZONTAL) ---
            $data['seccion'] = $seccion;
            $pdfTablas = Pdf::loadView('pdf.reporte-mtc-documento', $data)
                ->setPaper('a4', 'landscape')->output();

            $tmpHorizontal = tempnam(sys_get_temp_dir(), 'horz');
            file_put_contents($tmpHorizontal, $pdfTablas);
            
            $pageCount = $finalPdf->setSourceFile($tmpHorizontal);
            for ($i = 1; $i <= $pageCount; $i++) {
                $finalPdf->AddPage('L', 'A4');
                $finalPdf->useTemplate($finalPdf->importPage($i));
            }
            unlink($tmpHorizontal);
        }

        return response()->streamDownload(function () use ($finalPdf) {
            echo $finalPdf->Output('S');
        }, "Reporte_MTC_{$this->mes}_{$this->anio}.pdf");
    }

    private function generarHtmlCarta($asunto, $imagenBase64)
    {
        // Formateamos el mes actual para el texto (puedes usar $this->mes)
        $mesTexto = Carbon::create(null, $this->mes)->translatedFormat('F');

        return "
        <html>
        <head>
            <style>
                @page { margin: 0px; }
                body { 
                    margin: 0px; padding: 0px; font-family: 'Helvetica', Arial; font-size: 13px; line-height: 1.5;
                }
                .fondo {
                    width: 210mm; height: 297mm; position: absolute; top: 0; left: 0; z-index: -1;
                }
                .contenido {
                    margin-top: 4.5cm; /* Ajusta según el membrete superior */
                    margin-left: 2.5cm;
                    margin-right: 2.5cm;
                }
                .negrita { font-weight: bold; }
                .derecha { text-align: right; }
                .justificado { text-align: justify; }
            </style>
        </head>
        <body>
            <img class='fondo' src='{$imagenBase64}'>
            <div class='contenido'>
                <p class='negrita'>Carta N° 000025 /2026-CITV/ANCÓN</p>
                <p>Lima, " . Carbon::now()->translatedFormat('d \d\e F \d\e\l Y') . "</p>
                
                <p>Señor:<br>
                <span class='negrita'>JORGE CAYO ESPINOZA GALARZA</span><br>
                Director de la Dirección de Circulación Vial de la General de Autorizaciones en Transportes (e)<br>
                <span class='negrita'>Pte.-</span></p>

                <p><span class='negrita'>ASUNTO:</span> <span class='negrita'>{$asunto} EN “CITV ANCÓN S.A.C\".</span></p>
                <p><span class='negrita'>REFERENCIA: RESOLUCIÓN DIRECTORAL N° 287-2022-MTC/17.03</span></p>

                <p>De mi consideración.</p>

                <div class='justificado'>
                    Yo, <span class='negrita'>KATHERINE LOPEZ HENRIQUEZ</span>, con DNI N.° 08884851 en calidad de Gerente General de CITV ANCÓN S.A.C, con RUC N.° 20606636823, con Partida Registral N° 14541103, con dirección Fiscal en: Mz. 04 lote.04 – Autopista Panamericana Norte – Variante – Asociación Popular la Variante de Ancón – Distrito de Ancón – Provincia de Lima – Departamento de Lima, tengo el gusto de dirigirme a su distinguida persona para manifestarle lo siguiente:
                </div>

                <p class='justificado'>Que estamos enviando a su despacho la información estadística de los vehículos inspeccionados en la planta de Revisión Técnica Vehicular CITV ANCON S.A.C. lo cual está incluida la cantidad de rangos de los números de la series de los formatos y calcomanías del mes de <span class='negrita'>" . strtoupper($mesTexto) . " {$this->anio}</span> (De acuerdo a R.D.N° 5453-2017-MTC/15.)</p>

                <p>Aprovecho la oportunidad para expresarle los sentimientos de mi especial consideración y estima personal.</p>

                <p style='margin-top: 2cm;'>Atentamente.</p>
            </div>
        </body>
        </html>";
    }

    private function getImagenBase64()
    {
        // Usamos public_path para asegurar la ruta en el proyecto Laravel
        $ruta = public_path('images/fondomembretada.png');

        if (!file_exists($ruta)) {
            // Esto te ayudará a debuguear si la ruta está mal
            return '';
        }

        $tipo = pathinfo($ruta, PATHINFO_EXTENSION);
        $data = file_get_contents($ruta);
        return 'data:image/' . $tipo . ';base64,' . base64_encode($data);
    }


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