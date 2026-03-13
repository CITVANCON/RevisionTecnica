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

        // 1. Verificación de Seguridad
        if ($request->header('X-Plant-Token') !== $tokenSeguridad) {
            Log::warning("Intento de acceso no autorizado desde IP: " . $request->ip());
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // 2. Extracción de Datos
        $datos = $request->json()->all() ?: $request->all();

        if (empty($datos) || !is_array($datos)) {
            Log::error("Sincronización fallida: El cuerpo de la petición está vacío o mal formado.");
            return response()->json(['mensaje' => 'Sin datos válidos recibidos', 'cantidad' => 0], 400);
        }

        $procesadosExitosamente = 0;
        $errores = [];

        // 3. Procesamiento en Lote
        foreach ($datos as $item) {
            try {
                if (!isset($item['id_inspeccion_local'])) {
                    Log::warning("Registro omitido: No se encontró id_inspeccion_local.");
                    continue;
                }

                InspeccionMaestra::updateOrCreate(
                    ['id_inspeccion_local' => (string)$item['id_inspeccion_local']],
                    [
                        'placa_vehiculo'          => $item['placa'] ?? 'SIN_PLACA',
                        'categoria_vehiculo'      => $item['categoria'] ?? null,
                        'fecha_inspeccion'        => $item['fecha'] ?? null,
                        'hora_inicio'             => $item['finicio'] ?? null,
                        'hora_fin'                => $item['ffin'] ?? null,
                        'resultado_estado'        => $item['resultado'] ?? null,
                        'es_reinspeccion'         => $item['reinsp'] ?? 'N',
                        'numero_reinspeccion'     => isset($item['nreinsp']) ? (int)$item['nreinsp'] : 0,
                        'monto_total'             => (float)($item['monto_total'] ?? 0),
                        'tipo_atencion'           => $item['tipo_atencion'] ?? 'Sin Servicio',
                        'numero_certificado_mtc'  => $item['ncertificado'] ?? null,
                        'serie_certificado'       => $item['serie'] ?? null,
                        'correlativo_certificado' => $item['correlativo'] ?? null,
                        'fecha_anulacion'         => $item['fanulacion'] ?? null,
                    ]
                );
                $procesadosExitosamente++;

            } catch (\Exception $e) {
                $msgError = "ID Local " . ($item['id_inspeccion_local'] ?? 'unk') . ": " . $e->getMessage();
                Log::error("Error en sincronización: " . $msgError);
                $errores[] = $msgError;
            }
        }

        // 4. Respuesta Profesional
        return response()->json([
            'mensaje'  => 'Sincronización finalizada',
            'cantidad' => $procesadosExitosamente,
            'fallidos' => count($errores),
            'status'   => 'success'
        ], 200);
    }
}
