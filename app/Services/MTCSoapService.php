<?php

namespace App\Services;

use Artisaninweb\SoapWrapper\SoapWrapper;

class MTCSoapService
{
    protected $soap;
    protected $serviceName = 'MTCService';

    public function __construct(SoapWrapper $soap)
    {
        $this->soap = $soap;

        //$wsdlUrl = 'https://interoperabilidad.mtc.gob.pe/wsinteroperabilidadcitv?wsdl'; // WSDL real
        $wsdlUrl = 'http://www.dneonline.com/calculator.asmx?WSDL';

        $this->soap->add($this->serviceName, function ($service) use ($wsdlUrl) {
            $service
                ->wsdl($wsdlUrl)
                ->trace(true)
                //->cache(WSDL_CACHE_NONE);
                ->cache(0);
        });
    }

    /*public function autenticarOperacion(array $params)
    {
        return $this->soap->call("{$this->serviceName}.autentificaInicioOperacion", [
            'parameters' => $params,
        ]);
    }*/

    // 1. Autentificación de inicio de operaciones diarias
    public function autenticarOperacion(array $params)
    {
        return $this->soap->call("{$this->serviceName}.Add", [
            'parameters' => [
                'intA' => $params['a'] ?? 5,
                'intB' => $params['b'] ?? 3,
            ],
        ]);
    }

    // 2. Generando el número de ficha por vehículo
    /*public function consultarVehiculo(array $params)
    {
        return $this->soap->call("{$this->serviceName}.valVehiculoPoliza", [
            'parameters' => $params,
        ]);
    }*/
    public function consultarVehiculo(array $params)
    {
        return $this->soap->call("{$this->serviceName}.Add", [
            'parameters' => [
                'intA' => 11,
                'intB' => 4,
            ],
        ]);
    }



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
