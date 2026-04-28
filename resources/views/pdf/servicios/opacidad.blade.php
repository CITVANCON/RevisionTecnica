<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 0cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("{{ public_path('images/fondo_membrana2.jpg') }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            font-size: 11px;
            /* Tamaño reducido para que quepa toda la estructura */
        }

        .container {
            margin-top: 3.3cm;
            /* Ajustado según la imagen */
            margin-left: 0.8cm;
            margin-right: 0.8cm;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 2px;
        }

        .tabla-full {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .tabla-full td,
        .tabla-full th {
            border: 1px solid #777;
            padding: 3px;
        }

        .bg-gray {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-title">CERTIFICADO DE PRUEBA DE OPACIDAD</div>
        <div class="text-center" style="margin-bottom: 5px;">Certificado N° {{ $inspeccion->numero_certificado }} - {{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->format('Y') }}</div>

        {{-- Fila de Tiempos y Empresa --}}
        <table class="tabla-full">
            <tr>
                <td class="bg-gray">Tipo de Inspección:</td>
                <td class="text-center">Prueba de Opacidad</td>
                <td class="bg-gray">Fecha de prueba:</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->format('d/m/Y') }}</td>
                <td class="bg-gray">N° de prueba:</td>
                <td class="text-center">{{ $inspeccion->numero_certificado }}</td>
            </tr>
        </table>

        {{-- I. Características del Vehículo --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="6">I. CARACTERISTICAS DEL VEHICULO</td>
            </tr>
        </table>
        <table class="tabla-full">
            <tr>
                <td class="bg-gray" style="width: 15%;">TITULAR:</td>
                <td colspan="5" class="uppercase">{{ $inspeccion->vehiculo->propietario }}</td>
            </tr>
            <tr>
                <td class="bg-gray">PLACA:</td>
                <td>{{ $inspeccion->vehiculo->placa ?? 'NE' }}</td>
                <td class="bg-gray">COMBUSTIBLE:</td>
                <td>{{ $inspeccion->vehiculo->combustible ?? 'NE' }}</td>
                <td class="bg-gray">ASIENTOS/PASAJEROS:</td>
                <td>{{ ($inspeccion->vehiculo->asientos ?? '0') . ' / ' . ($inspeccion->vehiculo->pasajeros ?? '0') }}</td>
            </tr>
            <tr>
                <td class="bg-gray">CATEGORIA:</td>
                <td>{{ $inspeccion->vehiculo->categoria ?? 'NE' }}</td>
                <td class="bg-gray">VIN N° SERIE:</td>
                <td>{{ $inspeccion->vehiculo->vin_serie ?? 'NE'}}</td>
                <td class="bg-gray">LARGO/ANCHO/ALTO:</td>
                <td>{{-- $inspeccion->vehiculo->largo . '/' . $inspeccion->vehiculo->ancho . '/' . $inspeccion->vehiculo->alto --}}
                    {{ ($inspeccion->vehiculo->largo ?? 'NE') . ' / ' . ($inspeccion->vehiculo->ancho ?? 'NE') . ' / ' . ($inspeccion->vehiculo->alto ?? 'NE') }}
                </td>
            </tr>
            <tr>
                <td class="bg-gray">MARCA:</td>
                <td class="uppercase">{{ $inspeccion->vehiculo->marca ?? 'NE' }}</td>
                <td class="bg-gray">CARROCERIA:</td>
                <td class="uppercase">{{ $inspeccion->vehiculo->carroceria ?? 'NE' }}</td>
                <td class="bg-gray">COLOR:</td>
                <td class="uppercase">{{ $inspeccion->vehiculo->color ?? 'NE' }}</td>
            </tr>
            <tr>
                <td class="bg-gray">MODELO:</td>
                <td class="uppercase">{{ $inspeccion->vehiculo->modelo ?? 'NE' }}</td>
                <td class="bg-gray">N° MOTOR:</td>
                <td>{{ $inspeccion->vehiculo->numero_motor ?? 'NE' }}</td>
                <td class="bg-gray">PESO NETO:</td>
                <td>{{ $inspeccion->vehiculo->peso_neto ?? 'NE' }}</td>
            </tr>
            <tr>
                <td class="bg-gray">AÑO FABRICACION</td>
                <td>{{ $inspeccion->vehiculo->anio_fabricacion ?? 'NE' }}</td>
                <td class="bg-gray">MARCA CARROCERIA:</td>
                <td>{{ $inspeccion->vehiculo->marca_carroceria ?? 'NE' }}</td>
                <td class="bg-gray">PESO BRUTO:</td>
                <td>{{ $inspeccion->vehiculo->peso_bruto ?? 'NE' }}</td>
            </tr>
            <tr>
                <td class="bg-gray">KILOMETRAJE</td>
                <td>{{ $inspeccion->vehiculo->kilometraje ?? 'NE' }}</td>
                <td class="bg-gray">EJES/RUEDAS:</td>
                <td>{{ ($inspeccion->vehiculo->ejes ?? 'NE') . ' / ' . ($inspeccion->vehiculo->ruedas ?? 'NE') }}</td>
                <td class="bg-gray">CARGA UTIL:</td>
                <td>{{ $inspeccion->vehiculo->peso_util ?? 'NE' }}</td>
            </tr>
        </table>

        {{-- II. Datos del Equipo (Opacímetro) --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="6">II. DATOS DEL EQUIPO (OPACIMETRO)</td>
            </tr>
        </table>
        <table class="tabla-full text-center">
            <tr class="bg-gray" style="font-size: 10px;">
                <td>C.I.T.V.</td>
                <td>LINEA</td>
                <td>MAQUINA</td>
                <td>MARCA</td>
                <td>MODELO</td>
                <td>N° SERIE</td>
            </tr>
            <tr>
                <td>CITV ANCÓN S.A.C.</td>
                <td>MIXTA</td>
                <td>OPACIMETRO</td>
                <td>JEVOL</td>
                <td>JVS-600</td>
                <td>20090803</td>
            </tr>
            <tr>
                <td style="font-size: 8.4px;" colspan="6">
                    Direccion. MZ.4 LT 4-AUTOPISTA PANAMERICANA NORTE – VARIANTE – ASOCIACION POPULAR LA VARIANTE DE ANCON, DISTRITO DE ANCON, PROVINCIA DE LIMA
                </td>
            </tr>
        </table>

        {{-- III. Resultados Obtenidos --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="3">III. RESULTADOS OBTENIDOS EN OPACIMETRO</td>
            </tr>
        </table>
        <table class="tabla-full text-center">
            <tr class="bg-gray">
                <td>MEDICION</td>
                <td>VALOR K (M-1)</td>
                <td>TEMP. ACEITE (°C)</td>
            </tr>
            <tr>
                <td>01</td>
                <td>{{ number_format($detalle->ciclo1_k, 2) }}</td>
                <td>{{ intval($detalle->ciclo1_t) }}</td>
            </tr>
            <tr>
                <td>02</td>
                <td>{{ number_format($detalle->ciclo2_k, 2) }}</td>
                <td>{{ intval($detalle->ciclo2_t) }}</td>
            </tr>
            <tr>
                <td>03</td>
                <td>{{ number_format($detalle->ciclo3_k, 2) }}</td>
                <td>{{ intval($detalle->ciclo3_t) }}</td>
            </tr>
             <tr>
                <td>04</td>
                <td>{{ number_format($detalle->ciclo4_k, 2) }}</td>
                <td>{{ intval($detalle->ciclo4_t) }}</td>
            </tr>
            <tr class="bg-gray">
                <td>VALOR K (Media Aritmética)</td>
                <td colspan="2">{{ number_format($detalle->promedio_k, 2) }}</td>
            </tr>
        </table>

        {{-- IV. Valores Vigentes --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="2">IV. VALORES VIGENTES</td>
            </tr>
        </table>
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="2">VEHICULOS MAYOR A DIESEL (LIVIANOS, MEDIANOS Y PESADOS )</td>
            </tr>
            <tr class="bg-gray">
                <td>AÑO DE FABRICACION</td>
                <td>OPACIDAD K(M-1)</td>
            </tr>
            <tr>
                <td>Vehículos hasta 1995:</td>
                <td>Menor a 3.0 m-1</td>
            </tr>
            <tr>
                <td>Vehículos 1996 en adelante:</td>
                <td>Menor a 2.5 m-1</td>
            </tr>
            <tr>
                <td>Vehículos 2003 en adelante:</td>
                <td>Menor a 2.1 m-1</td>
            </tr>
        </table>

        {{-- V. Resultado de la Prueba --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="2">V. RESULTADO DE LA PRUEBA</td>
            </tr>
        </table>
        <table class="tabla-full text-center">
            <tr>
                <td colspan="2">LOS RESULTADOS OBTENIDOS CUMPLEN DE LOS VALORES LEGALES VIGENTES </td>
            </tr>
            <tr>
                <td class="bg-gray">RESULTADO: {{ $inspeccion->resultado_final }}</td>
                <!-- Necesito que se marque con una X el resultado obtenido, si inspeccion->resultado_final es igual APROBADO se marca si ,
                    si es DESAPROBADO se marca no,  -->
                <td class="bg-gray">
                    APROBADO: 
                    SI ({{ $inspeccion->resultado_final == 'APROBADO' ? 'X' : ' ' }}) 
                    NO ({{ $inspeccion->resultado_final == 'DESAPROBADO' ? 'X' : ' ' }})
                </td>
            </tr>
        </table>

        <div style="margin-top: 10px; font-size: 10px; text-align: justify;">
            Se expide el presente Certificado en la ciudad de Lima a los
            {{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->day }} días del mes de
            {{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->translatedFormat('F') }} del
            {{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->year }}.
        </div>
    </div>
</body>

</html>
