<?php

namespace App\Http\Controllers;

use App\Models\InspeccionFinalizada;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    //vista y descarga de Inspecciones
    public function generaPdfInspeccion($id)
    {
        // Asegura que la inspección exista o lanza 404
        $inspeccion = InspeccionFinalizada::findOrFail($id);

        $data = [
            'fecha' => $inspeccion->created_at,
            'empresa' => 'CITV ANCON SAC',
            'inspeccion' => $inspeccion, // Si necesitas más datos en la vista
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.inspeccion', $data);
        return $pdf->stream($inspeccion->id . '-inspeccion.pdf');
    }
}
