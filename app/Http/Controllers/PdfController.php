<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\InspeccionExtra;
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

    //vista y descarga de Contrato
    public function generarContrato($id)
    {
        // Cargamos el contrato con su usuario y la info de vacaciones si fuera necesario
        $contrato = Contrato::with('user')->findOrFail($id);

        // Datos adicionales para el PDF
        $data = [
            'contrato' => $contrato,
            'fecha_actual' => now()->format('d/m/Y'),
            'sueldo_letras' => $this->convertirSueldoALetras($contrato->sueldo_bruto),
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.contrato_laboral', $data);

        // Retornamos el PDF para previsualizar en el navegador
        return $pdf->stream('Contrato_' . $contrato->user->name . '.pdf');
    }
    private function convertirSueldoALetras($numero)
    {
        $enteros = floor($numero);
        $decimales = round(($numero - $enteros) * 100);

        $letras = $this->numerosALetrasLogica($enteros);

        return ucfirst($letras) . " con " . str_pad($decimales, 2, '0', STR_PAD_LEFT) . "/100 soles";
    }

    private function numerosALetrasLogica($num)
    {
        $unidades = ['', 'un', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
        $decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $especiales = [11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince', 16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve', 21 => 'veintiuno', 22 => 'veintidós', 23 => 'veintitrés', 24 => 'veinticuatro', 25 => 'veinticinco'];

        if ($num == 0) return 'cero';
        if ($num == 100) return 'cien';
        if ($num < 10) return $unidades[$num];

        if ($num < 30) {
            return $especiales[$num] ?? ($num == 20 ? 'veinte' : 'veinti' . $unidades[$num % 10]);
        }

        if ($num < 100) {
            $u = $num % 10;
            return $decenas[floor($num / 10)] . ($u > 0 ? ' y ' . $unidades[$u] : '');
        }

        if ($num < 1000) {
            $centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];
            $resto = $num % 100;
            return ($num == 100 ? 'cien' : $centenas[floor($num / 100)]) . ($resto > 0 ? ' ' . $this->numerosALetrasLogica($resto) : '');
        }

        if ($num < 1000000) {
            $miles = floor($num / 1000);
            $resto = $num % 1000;
            $t_miles = ($miles == 1) ? 'mil' : $this->numerosALetrasLogica($miles) . ' mil';
            return $t_miles . ($resto > 0 ? ' ' . $this->numerosALetrasLogica($resto) : '');
        }

        return (string)$num; // Para montos mayores a un millón, se necesitaría ampliar la lógica
    }


    public function generarPdfHermeticidad($id)
    {
        // Cargamos la inspección asegurándonos que el detalle sea de hermeticidad
        $inspeccion = InspeccionExtra::with(['cliente', 'vehiculo', 'detalleHermeticidad', 'usuario'])
            ->where('tipo_servicio_id', 1) // Seguridad: solo hermeticidad
            ->findOrFail($id);

        $data = [
            'inspeccion' => $inspeccion,
            'detalle'    => $inspeccion->detalleHermeticidad,
            'fecha'      => now()->format('d/m/Y'),
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.servicios.hermeticidad', $data);

        return $pdf->stream("Certificado_Hermeticidad_{$inspeccion->numero_certificado}.pdf");
    }

    public function generarPdfOpacidad($id)
    {
        // Cargamos la inspección asegurándonos que el detalle sea de opacidad
        $inspeccion = InspeccionExtra::with(['cliente', 'vehiculo', 'detalleOpacidad', 'usuario'])
            ->where('tipo_servicio_id', 2) // Seguridad: solo opacidad
            ->findOrFail($id);

        $data = [
            'inspeccion' => $inspeccion,
            'detalle'    => $inspeccion->detalleOpacidad,
            'fecha'      => now()->format('d/m/Y'),
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.servicios.opacidad', $data);

        return $pdf->stream("Certificado_Opacidad_{$inspeccion->numero_certificado}.pdf");
    }
}
