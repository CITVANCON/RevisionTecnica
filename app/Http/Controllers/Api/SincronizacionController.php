<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspeccionMaestra;
use Illuminate\Http\Request;

class SincronizacionController extends Controller
{
    public function recibirDatos(Request $request)
    {
        // Una llave simple que solo tú y el script de la planta conocerán
        $tokenSeguridad = "CITV_ANCON_SECRET_2026";

        if ($request->header('X-Plant-Token') !== $tokenSeguridad) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // 1. Validar que la data sea un arreglo
        $datos = $request->all();

        foreach ($datos as $item) {
            // 2. Usamos updateOrCreate para evitar duplicados basándonos en el ID de la planta
            InspeccionMaestra::updateOrCreate(
                ['id_inspeccion_local' => $item['id_inspeccion_local']],
                [
                    'placa_vehiculo'          => $item['placa'],
                    'categoria_vehiculo'      => $item['categoria'] ?? null,
                    'fecha_inspeccion'        => $item['fecha'],
                    'hora_inicio'             => $item['finicio'],
                    'hora_fin'                => $item['ffin'],
                    'resultado_estado'        => $item['resultado'],
                    'numero_certificado_mtc'  => $item['ncertificado'] ?? null,
                    'serie_certificado'       => $item['serie'] ?? null,
                    'correlativo_certificado' => $item['correlativo'] ?? null,
                    'fecha_anulacion'         => $item['fanulacion'] ?? null,
                ]
            );
        }

        return response()->json(['mensaje' => 'Sincronización exitosa', 'cantidad' => count($datos)], 200);
    }
}
