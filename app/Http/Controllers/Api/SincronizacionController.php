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
        $tokenSeguridad = "CITV_ANCON_SECRET_2026";

        // 1. Verificación de Seguridad
        if ($request->header('X-Plant-Token') !== $tokenSeguridad) {
            Log::warning("Intento de acceso no autorizado desde IP: " . $request->ip());
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // 2. Extracción de Datos
        $datos = $request->json()->all() ?: $request->all();

        // Si recibimos un solo objeto (no una lista), lo envolvemos en un array
        if (is_array($datos) && isset($datos['id_inspeccion_local'])) {
            $datos = [$datos];
        }

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
            
                // 3.1 Guardar o actualizar la inspección
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

                        'kilometraje'        => $item['kilometraje'] ?? null,
                        'peso_bruto_v'       => $item['peso_bruto'] ?? 0,
                        'peso_neto_v'        => $item['peso_neto'] ?? 0,
                        'carga_util_v'       => $item['carga_util'] ?? 0,
                        'nro_asientos_v'     => $item['asientos'] ?? 0,
                        'nro_pasajeros_v'    => $item['pasajeros'] ?? 0,
                        'nro_ejes_v'         => $item['ejes'] ?? 0,
                        'nro_ruedas_v'       => $item['ruedas'] ?? 0,
                        'nro_motor_v'        => $item['motor'] ?? null,
                        'nro_vin_v'          => $item['vin'] ?? null,
                        'fecha_vencimiento'  => $item['fvencimiento'] ?? null,
                        'estado_inspeccion'  => $item['estado_nombre'] ?? null,
                        'tipo_inspeccion'    => $item['tipo_inspeccion'] ?? null,
                        'propietario_nombre' => $item['propietario'] ?? null,
                        'propietario_documento' => $item['documento'] ?? null,
                        'propietario_celular'   => $item['celular'] ?? null,
                        'nivel_defecto'      => $item['nivel_defecto'] ?? null,
                        'codigos_defectos'   => $item['codigos_defectos'] ?? null,
                    ]
                );

                // 3.2 Recuperar la inspección recién creada o actualizada
                $inspeccion = InspeccionMaestra::where(
                    'id_inspeccion_local',
                    (string) $item['id_inspeccion_local']
                )->first();

                // 3.3 Sincronizar el método de pago de planta
                // Si la web corrigió o dividió los pagos, planta ya no debe tocarlos.
                if ($inspeccion && !$inspeccion->pago_ajustado_web && !empty($item['metodo_pago'])) {

                    // Normaliza el nombre de planta al estándar de la web
                    $metodoPago = $this->normalizarMetodoPago($item['metodo_pago']);
                    $monto = (float) ($item['monto_total'] ?? 0);

                    if ($metodoPago !== '' && $monto > 0) {

                        // Fecha y hora del pago:
                        // 1) Usa la hora de fin de inspección.
                        // 2) Si no existe, usa la fecha de inspección a medianoche.
                        // 3) Como último recurso, usa la fecha actual.
                        $fechaPago = !empty($item['ffin'])
                            ? $item['ffin']
                            : (!empty($item['fecha']) 
                                ? $item['fecha'] . ' 00:00:00' 
                                : now());

                        // Busca o crea UN SOLO pago proveniente de planta
                        $inspeccion->pagos()->updateOrCreate(
                            [
                                'origen' => 'PLANTA'
                            ],
                            [
                                'metodo_pago'    => $metodoPago,
                                'monto'          => $monto,
                                'nro_referencia' => null,
                                'fecha_pago'     => $fechaPago,
                                'user_id'        => null,
                            ]
                        );

                        // Campo antiguo de la inspección, solo para compatibilidad
                        $inspeccion->update([
                            'metodo_pago' => $metodoPago,
                        ]);
                    }
                }

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

    /**
     * Normaliza los nombres de TipoPago de planta
     * a los valores usados por el sistema web.
     */
    private function normalizarMetodoPago(string $metodo): string
    {
        $metodo = mb_strtoupper(trim($metodo));

        return match ($metodo) {
            'EFECTIVO' => 'EFECTIVO',
            'YAPE' => 'YAPE',
            'TARJETA VISA', 'VISA', 'VISA (POS)' => 'VISA',
            'DEPÓSITO', 'DEPOSITO' => 'TRANSFERENCIA',
            'PLIN' => 'PLIN',
            default => $metodo,
        };
    }
}
