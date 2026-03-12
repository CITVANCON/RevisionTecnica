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

        // Método más robusto para obtener el array directamente de la petición JSON
        $datos = $request->json()->all();

        // Si llega vacío, intentamos con el método tradicional por si acaso
        if (empty($datos)) {
            $datos = $request->all();
        }

        if (empty($datos) || !is_array($datos)) {
            Log::error("Sincronización fallida: No se recibieron datos o formato inválido.");
            return response()->json(['mensaje' => 'Sin datos recibidos', 'cantidad' => 0], 200);
        }

        $procesadosExitosamente = 0;

        foreach ($datos as $item) {
            try {
                // Asegúrate de que id_inspeccion_local no venga nulo
                if (!isset($item['id_inspeccion_local'])) continue;

                InspeccionMaestra::updateOrCreate(
                    ['id_inspeccion_local' => (string)$item['id_inspeccion_local']], // Forzamos a String
                    [
                        'placa_vehiculo'          => $item['placa'] ?? 'SIN_PLACA',
                        'categoria_vehiculo'      => $item['categoria'] ?? null,
                        'fecha_inspeccion'        => $item['fecha'] ?? null,
                        'hora_inicio'             => $item['finicio'] ?? null,
                        'hora_fin'                => $item['ffin'] ?? null,
                        'resultado_estado'        => $item['resultado'] ?? null,
                        'monto_total'             => (float)($item['monto_total'] ?? 0), // Forzamos a Float
                        'tipo_atencion'           => $item['tipo_atencion'] ?? 'Sin Servicio',
                        'numero_certificado_mtc'  => $item['ncertificado'] ?? null,
                        'serie_certificado'       => $item['serie'] ?? null,
                        'correlativo_certificado' => $item['correlativo'] ?? null,
                        'fecha_anulacion'         => $item['fanulacion'] ?? null,
                    ]
                );
                $procesadosExitosamente++;
            } catch (\Exception $e) {
                Log::error("Error procesando ID Local " . ($item['id_inspeccion_local'] ?? 'unk') . ": " . $e->getMessage());
            }
        }

        return response()->json([
            'mensaje' => 'Sincronización exitosa',
            'cantidad' => $procesadosExitosamente
        ], 200);
    }
}
