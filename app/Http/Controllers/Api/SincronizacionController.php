<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspeccionMaestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SincronizacionController extends Controller
{
    /*public function recibirDatos(Request $request)
    {
        // llave simple que solo codigo y el script del servidor conocen
        $tokenSeguridad = "CITV_ANCON_SECRET_2026";

        if ($request->header('X-Plant-Token') !== $tokenSeguridad) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

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
                        'monto_total'             => $item['monto_total'] ?? 0,
                        'tipo_atencion'           => $item['tipo_atencion'] ?? null,
                        'numero_certificado_mtc'  => $item['ncertificado'] ?? null,
                        'serie_certificado'       => $item['serie'] ?? null,
                        'correlativo_certificado' => $item['correlativo'] ?? null,
                        'fecha_anulacion'         => $item['fanulacion'] ?? null,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error procesando ID Local " . ($item['id_inspeccion_local'] ?? 'unk') . ": " . $e->getMessage());
                continue;
            }
        }

        return response()->json(['mensaje' => 'Sincronización exitosa', 'cantidad' => count($datos)], 200);
    }*/

    public function recibirDatos(Request $request)
    {
        $tokenSeguridad = "CITV_ANCON_SECRET_2026";

        if ($request->header('X-Plant-Token') !== $tokenSeguridad) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Obtenemos el contenido bruto y lo decodificamos manualmente
        $json = $request->getContent();
        $datos = json_decode($json, true);

        // Si no es un array, algo llegó mal en el formato JSON
        if (!is_array($datos)) {
            Log::error("JSON Inválido recibido: " . substr($json, 0, 500));
            return response()->json(['mensaje' => 'Formato JSON inválido', 'cantidad' => 0], 400);
        }

        $procesadosExitosamente = 0;

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
                        'monto_total'             => $item['monto_total'] ?? 0,
                        'tipo_atencion'           => $item['tipo_atencion'] ?? null,
                        'numero_certificado_mtc'  => $item['ncertificado'] ?? null,
                        'serie_certificado'       => $item['serie'] ?? null,
                        'correlativo_certificado' => $item['correlativo'] ?? null,
                        'fecha_anulacion'         => $item['fanulacion'] ?? null,
                    ]
                );
                $procesadosExitosamente++;
            } catch (\Exception $e) {
                Log::error("Error procesando ID Local " . ($item['id_inspeccion_local'] ?? 'unk') . ": " . $e->getMessage());
                continue;
            }
        }

        return response()->json([
            'mensaje' => 'Sincronización exitosa',
            'cantidad' => $procesadosExitosamente
        ], 200);
    }
}
