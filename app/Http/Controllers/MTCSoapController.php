<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MTCSoapService;
use Exception;

class MTCSoapController extends Controller
{
    protected $mtc;

    public function __construct(MTCSoapService $mtc)
    {
        $this->mtc = $mtc;
    }
    
    public function iniciarOperacion(Request $request)
    {
        // Los valores de ejemplo proporcionados en la documentación.
        // Cuando tengas las credenciales reales, deberás reemplazar estos valores.
        $params = [
            'CodEntidad' => 'EC000025', // Casing ajustado según el WSDL
            'CodLocal'   => 'L000036',  // Casing ajustado según el WSDL
            'CodIV'      => 'XYL54ENGMKA49FG21', // Casing ajustado según el WSDL
        ];

        try {
            // Llama al método del servicio que se encarga de la comunicación SOAP
            $response = $this->mtc->autenticarOperacion($params);

            // La respuesta del servicio será un objeto.
            // Según el WSDL y la documentación, la respuesta de AutentificaInicioOperacionResponse
            // contiene un elemento 'AutentificaInicioOperacionResult' de tipo 'Retorno'.
            // El objeto 'Retorno' tiene 'Codigo' y 'Mensaje'.
            // Accedemos a los resultados de la siguiente manera:
            $result = $response->AutentificaInicioOperacionResult;

            return response()->json([
                'status'  => 'success',
                'codigo'  => $result->Codigo,
                'mensaje' => $result->Mensaje,
                // Si 'RetVal' contiene algo útil, también podrías incluirlo:
                // 'retVal'  => $result->RetVal,
            ]);
        } catch (Exception $e) {
            // Captura cualquier excepción que ocurra durante la llamada SOAP
            // y devuelve una respuesta JSON con el error.
            return response()->json([
                'status' => 'error',
                'message' => 'Error al iniciar la operación: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para errores del servidor
        }
    }

    // 1. Autentificación de inicio de operaciones diarias
    public function iniciarOperacion2()
    {
        $params = [
            'CODENTIDA' => 'EC000025',
            'CODLOCAL' => 'L000036',
            'CODIV' => 'XYL54ENGMKA49FG21',
        ];

        try {
            $response = $this->mtc->autenticarOperacion($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }    
    /*public function iniciarOperacion()
    {
        $params = [
            'a' => 10,
            'b' => 20,
        ];

        try {
            $response = $this->mtc->autenticarOperacion($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }*/

    // 2. Generando el número de ficha por vehículo
    /*public function consultarVehiculo()
    {
        $params = [
            'CIOD_CITV'     => 'ABCDEF01072025XYZ', // Ejem código de inicio de operaciones diaria
            'PLACA'         => 'XYZ098',
            'CATEGORIA'     => 1,
            'TIPSERVICIO'   => 1,
            'TIPAMBITO'     => 2,
            'TIPINSPECCION' => 1,
        ];

        try {
            $response = $this->mtc->consultarVehiculo($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }*/
    public function consultarVehiculo(Request $request)
    {
        $params = [
            
            'PLACA'         => $request->input('placa'),
            'CATEGORIA'     => $request->input('categoria'),
            'TIPSERVICIO'   => $request->input('tipo_servicio'),
            'TIPAMBITO'     => $request->input('tipo_ambito'),
            'TIPINSPECCION' => $request->input('tipo_inspeccion', 1), // por defecto 1
            'CIOD_CITV'     => 'ABCDEF01072025XYZ', // Puedes luego cambiar por variable dinámica
        ];

        try {
            $response = $this->mtc->consultarVehiculo($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    // 3. Registro de Póliza
    public function registrarPoliza()
    {
        $params = [
            'CIOD_CITV'      => 'ABCDEF01072025XYZ',
            'NUM_FICHA'      => '20250701XYZ098000001',
            'PLACA'          => 'XYZ098',
            'TPPOLIZA'       => 'SOAT',
            'NUMPOLIZA'      => 'X45Y777444H55',
            'FECINIPOLIZA'   => '10/02/2025',
            'FECFINPOLIZA'   => '10/02/2026',
            'ASEGURADORA'    => '2'
        ];

        try {
            $response = $this->mtc->registrarPoliza($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // 4. Generando el número de certificado o informe
    public function registrarResultadoRevision()
    {
        $params = [
            'CIOD_CITV'    => 'ABCDEF01072025XYZ',
            'PLACA'        => 'XYZ098',
            'NUM_FICHA'    => '20250701XYZ098000001',
            'ESTADO'       => 1,
            'FECVIGINI'    => '10/07/2025',
            'FECVIGFIN'    => '10/07/2026',
            'OBSERVACION'  => 'SIN OBSERVACIONES'
        ];

        try {
            $response = $this->mtc->registrarResultadoRevision($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // 5. Actualiza póliza
    public function actualizarPoliza()
    {
        $params = [
            'CIOD_CITV'      => 'ABCDEF01072025XYZ',
            'NUM_FICHA'      => '20250701XYZ098000001',
            'PLACA'          => 'XYZ098',
            'TPPOLIZA'       => 'SOAT',
            'NUMPOLIZA'      => 'Z99X888YYY77',
            'FECINIPOLIZA'   => '15/07/2025',
            'FECFINPOLIZA'   => '15/07/2026',
            'ASEGURADORA'    => '2'
        ];

        try {
            $response = $this->mtc->actualizarPoliza($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // 6. Anulación de certificado
    public function anularCertificado()
    {
        $params = [
            'CIOD_CITV'     => 'ABCDEF01072025XYZ',
            'PLACA'         => 'XYZ098',
            'NUMERO'        => 'CITV2025070100012345',
            'MOTIVO'        => '1',
            'GENERAR_NUEVO' => '0'
        ];

        try {
            $response = $this->mtc->anularCertificado($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // 7. Cierre de transmisión diaria
    public function cerrarOperaciones()
    {
        $params = [
            'CIOD_CITV' => 'ABCDEF01072025XYZ'
        ];

        try {
            $response = $this->mtc->cerrarOperaciones($params);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
