<?php

namespace App\Services;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Exception;
use Illuminate\Support\Facades\Log;

class MTCSoapService
{
    /**
     * @var \Artisaninweb\SoapWrapper\SoapWrapper La instancia de SoapWrapper.
     * Este docblock ayuda a Intelephense a reconocer los métodos de SoapWrapper.
     */
    protected $soap;
    protected $serviceName = 'MTCService';
    protected $wsdlUrl; // Almacenar la URL del WSDL para posible depuración

    public function __construct(SoapWrapper $soap)
    {
        $this->soap = $soap;
        //$wsdlUrl = 'https://wscitv.mtc.gob.pe/WSInterOperabilidadCITV.svc?wsdl'; // WSDL real
        $this->wsdlUrl = 'https://wscitv.mtc.gob.pe/WSInterOperabilidadCITV.svc?singleWsdl';
        //$wsdlUrl = 'http://www.dneonline.com/calculator.asmx?WSDL';

        $this->soap->add($this->serviceName, function ($service) {
            $service
                ->wsdl($this->wsdlUrl)
                ->trace(true) // Habilita el rastreo para depuración
                ->cache(0);   // Deshabilita el caché para ver siempre los cambios
        });
    }

    /**
     * Autentifica el inicio de operaciones diarias con el servicio del MTC.
     *
     * @param array $params Los parámetros de autenticación: CodEntidad, CodLocal, CodIV.
     * Ej: ['CodEntidad' => '...', 'CodLocal' => '...', 'CodIV' => '...']
     * @return mixed La respuesta del servicio SOAP.
     * @throws Exception Si ocurre un error durante la llamada SOAP.
     */
    public function autenticarOperacion(array $params)
    {
        try {
            $soapParams = [
                'entLocalLogin' => [
                    'CodEntidad' => $params['CodEntidad'],
                    'CodLocal'   => $params['CodLocal'],
                    'CodIV'      => $params['CodIV'],
                ],
            ];

            $response = $this->soap->call("{$this->serviceName}.AutentificaInicioOperacion", $soapParams);

            // Opcional: Para depuración, puedes obtener el último request y response XML
            // dd($this->soap->getLastRequest(), $this->soap->getLastResponse());

            return $response;
        } catch (Exception $e) {
            // Asegúrate de que estas llamadas estén dentro del try-catch para que getLastRequest/Response
            // tengan un contexto si la excepción ocurre antes de que se puedan obtener.
            // Si el error es de conexión o WSDL, es posible que getLastRequest/Response devuelvan null o vacío.
            Log::error("Error en autenticarOperacion: " . $e->getMessage(), [
                'request' => $this->soap->getLastRequest(),
                'response' => $this->soap->getLastResponse(),
                'exception' => $e
            ]);
            throw new Exception("Error al autenticar la operación con el MTC: " . $e->getMessage());
        }
    }

    // 1. Autentificación de inicio de operaciones diarias
    public function autenticarOperacion2(array $params)
    {
        return $this->soap->call("{$this->serviceName}.autentificaInicioOperacion", [
            'parameters' => $params,
        ]);
    }    
    /*public function autenticarOperacion(array $params)
    {
        return $this->soap->call("{$this->serviceName}.Add", [
            'parameters' => [
                'intA' => $params['a'] ?? 5,
                'intB' => $params['b'] ?? 3,
            ],
        ]);
    }*/

    // 2. Generando el número de ficha por vehículo
    public function consultarVehiculo(array $params)
    {
        return $this->soap->call("{$this->serviceName}.valVehiculoPoliza", [
            //'parameters' => $params,
            'entVehiculoInspeccion' => $params, // importante: nombre correcto de la estructura
        ]);
    }
    /*public function consultarVehiculo(array $params)
    {
        // Simulación de respuesta
        return [
            'NUM_FICHA' => 'aaaammdd+Placa+000001',
            'PLACA' => $params['PLACA'],
            'CATEGORIA' => 'M3',
            'MARCA' => 'TOYOTA',
            'MODELO' => 'HIACE',
            'AÑOFAB' => 2020,
            'COMBUSTIBLE' => 'GASOLINA',
            'VINSERCHA' => 'JHFSK123456789',
            'NUMEROMOTOR' => 'ENG987654',
            'CARROCERIA' => 'MINIBUS',
            'NUMEROEJES' => 2,
            'NUMERORUEDAS' => 6,
            'NUMEROASIENTOS' => 15,
            'NUMEROPASAJEROS' => 14,
            'LARGO' => 5.1,
            'ANCHO' => 1.9,
            'ALTO' => 2.0,
            'COLOR' => 'ROJO',
            'PESONETO' => 1500,
            'PESOBRUTO' => 2500,
            'PESOUTIL' => 1000,
            'NUMDOC_ULTREV' => 'C-2018-000-000-548754 I-2018-000-000-000145',
            'FECDOC_ULTREV' => 'ddmmaaaa',
            'RAZSOCCITV_ULTREV' => 'Empresa CITV S.A.C.',
            'OBS_ULTREV' => 'H.3.1.4 D.1.7',
            'TPPOLIZA' => 'SOAT',
            'NUMPOLIZA' => 'X45Y777444H55',
            'FECINIPOLIZA' => '10/02/2018',
            'FECFINPOLIZA' => '10/02/2018',
            'MENSAJE' => 'MSJ01',
        ];
    }*/




    // 3. Registro de Póliza
    public function registrarPoliza(array $params)
    {
        return $this->soap->call("{$this->serviceName}.registraPoliza", [
            'parameters' => $params,
        ]);
    }

    // 4. Generando el número de certificado o informe
    public function registrarResultadoRevision(array $params)
    {
        return $this->soap->call("{$this->serviceName}.valRevisionAprobada", [
            'parameters' => $params,
        ]);
    }

    // 5. Actualiza póliza
    public function actualizarPoliza(array $params)
    {
        return $this->soap->call("{$this->serviceName}.actualizarPoliza", [
            'parameters' => $params,
        ]);
    }

    // 6. Anulación de certificado}
    public function anularCertificado(array $params)
    {
        return $this->soap->call("{$this->serviceName}.anulaCITV", [
            'parameters' => $params,
        ]);
    }

    // 7. Cierre de transmisión diaria
    public function cerrarOperaciones(array $params)
    {
        return $this->soap->call("{$this->serviceName}.cierreOperaciones", [
            'parameters' => $params,
        ]);
    }
}
