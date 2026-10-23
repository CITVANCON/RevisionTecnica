<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapturaPlacaController extends Controller
{
    public function registrarCaptura(Request $peticion)
    {
        $peticion->validate([
            'numero_placa'     => 'required|string',
            'fecha_captura'    => 'required|date_format:Y-m-d H:i:s',
            'nivel_confianza'  => 'nullable|numeric'
        ]);

        $numeroPlaca = strtoupper(trim(str_replace('-', '', $peticion->input('numero_placa'))));
        $fechaCaptura = $peticion->input('fecha_captura');
        $fechaLimpia = date('Y-m-d', strtotime($fechaCaptura));

        // Conciliación con la tabla inspecciones_maestras
        $coincidencia = DB::table('inspecciones_maestras')
            ->whereRaw("REPLACE(numero_placa, '-', '') = ?", [$numeroPlaca])
            ->whereDate('fecha_inspeccion', $fechaLimpia)
            ->first();

        $estadoConciliacion = $coincidencia ? 'coincide' : 'no_encontrado';
        $inspeccionId = $coincidencia ? $coincidencia->id : null;

        // Registrar en la tabla capturas_placas (ruta_imagen queda en null para ahorrar espacio en el VPS de 50GB)
        $idCaptura = DB::table('capturas_placas')->insertGetId([
            'numero_placa'          => $peticion->input('numero_placa'),
            'ruta_imagen'           => null,
            'fecha_captura'         => $fechaCaptura,
            'estado_conciliacion'   => $estadoConciliacion,
            'inspeccion_maestra_id' => $inspeccionId,
            'nivel_confianza'       => $peticion->input('nivel_confianza'),
            'created_at'            => now(),
            'updated_at'            => now()
        ]);

        return response()->json([
            'estado'                 => 'exito',
            'mensaje'                => 'Captura procesada y conciliada correctamente',
            'resultado_conciliacion' => $estadoConciliacion,
            'codigo_captura'         => $idCaptura
        ], 201);
    }
}
