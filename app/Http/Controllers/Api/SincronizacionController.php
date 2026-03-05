<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspeccionMaestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            try {
                InspeccionMaestra::updateOrCreate(
                    ['id_inspeccion_local' => $item['id_inspeccion_local']],
                    [
                        'placa_vehiculo'          => $item['placa'] ?? 'SIN_PLACA',
                        'categoria_vehiculo'      => $item['categoria'] ?? null,
                        'fecha_inspeccion'        => $item['fecha'] ?? null,
                        'hora_inicio'             => $item['finicio'] ?? null,
                        'hora_fin'                => $item['ffin'] ?? null,
                        'resultado_estado'        => $item['resultado'] ?? null,
                        'numero_certificado_mtc'  => $item['ncertificado'] ?? null,
                        'serie_certificado'       => $item['serie'] ?? null,
                        'correlativo_certificado' => $item['correlativo'] ?? null,
                        'fecha_anulacion'         => $item['fanulacion'] ?? null,
                    ]
                );
            } catch (\Exception $e) {
                // Si un registro falla, lo logueamos y seguimos con el siguiente
                Log::error("Error procesando ID Local " . ($item['id_inspeccion_local'] ?? 'unk') . ": " . $e->getMessage());
                continue;
            }
        }

        return response()->json(['mensaje' => 'Sincronización exitosa', 'cantidad' => count($datos)], 200);
    }
}
