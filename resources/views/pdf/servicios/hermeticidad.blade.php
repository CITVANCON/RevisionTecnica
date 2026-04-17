<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0cm; }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0; padding: 0;
            background-image: url("{{ public_path('images/fondo_membrana2.jpg') }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            font-size: 11px; /* Tamaño reducido para que quepa toda la estructura */
        }

        .container {
            margin-top: 3.3cm; /* Ajustado según la imagen */
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

        .tabla-full td, .tabla-full th {
            border: 1px solid #777;
            padding: 3px;
        }

        .bg-gray { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .uppercase { text-transform: uppercase; }

        /* Estilo para las definiciones A, O, N.A */
        .definiciones {
            font-size: 9px;
            vertical-align: top;
            width: 18%;
        }

        .cell-small { width: 40px; text-align: center; }

        .diagonal-cell {
            position: relative;
            width: 95px; /* Ajustado para que el texto no se amontone */
            height: 40px;
            padding: 0 !important;
            overflow: hidden;
            border-right: 1px solid #777;
            border-bottom: 1px solid #777;
        }

        /* Dibujamos la línea usando un div con borde inferior e inclinación manual */
        .linea-manual {
            position: absolute;
            top: 0;
            left: 0;
            width: 110px; /* Longitud de la hipotenusa aproximada */
            height: 0;
            border-top: 1px solid #777;
            
            /* Aumentamos el ángulo a 24-25 grados para que baje más */
            transform: rotate(23deg); 
            transform-origin: 0 0;
            z-index: 1;
        }

        .diag-top {
            position: absolute;
            top: 2px;
            right: 5px; /* Pegado a la derecha */
            font-weight: bold;
            z-index: 10;
        }

        .diag-bottom {
            position: absolute;
            bottom: 2px;
            left: 5px; /* Pegado a la izquierda */
            font-weight: bold;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-title">CERTIFICADO DE PRUEBA DE HERMETICIDAD</div>
        <div class="text-center" style="margin-bottom: 5px;">Certificado N° {{ $inspeccion->numero_certificado }} - {{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->format('Y') }}</div>

        {{-- Fila de Tiempos y Empresa --}}
        <table class="tabla-full">
            <tr>
                <td class="bg-gray">Fecha de Inspección:</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->format('d/m/Y') }}</td>
                <td class="bg-gray">Hora de Inspección:</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($inspeccion->hora_inspeccion)->format('h:i A') }}</td>
                <td class="bg-gray">Empresa que Certifica:</td>
                <td class="text-center">CITV ANCÓN S.A.C</td>
            </tr>
        </table>

        {{-- Datos del Vehículo --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="7">Datos del Vehículo.- {{ $inspeccion->cliente->nombre_razon_social }}</td>
            </tr>
        </table>
        <table class="tabla-full" style=" border: none;">
            <tr>
                <td class="bg-gray">Titular del Vehículo:</td>
                <td colspan="7">{{ $inspeccion->vehiculo->propietario }}</td>
            </tr>
            <tr>
                <td class="bg-gray">Placa N°:</td>
                <td>{{ $inspeccion->vehiculo->placa }}</td>
                <td colspan="3" class="bg-gray">Carga Habitual:</td>
                <td colspan="3">C. MINERALES Cu, Pb, Zn, </td>
            </tr>
            <tr>
                <td class="bg-gray">Año de Fabricación:</td>
                <td>{{ $inspeccion->vehiculo->anio_fabricacion }}</td>
                <td class="bg-gray">Ejes:</td>
                <td>{{ $inspeccion->vehiculo->ejes }}</td>
                <td class="bg-gray">Ruedas:</td>
                <td>{{ $inspeccion->vehiculo->ruedas }}</td>
                <td class="bg-gray">Capacidad:</td>
                <td>{{ $inspeccion->vehiculo->peso_neto }}</td>
            </tr>
            <tr>
                <!-- Considerar un dia anterior a la fecha de inspección para el último mantenimiento y certificación -->
                <td class="bg-gray">Último Mantenimiento:</td>
                <td>{{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->subDay()->format('d/m/Y') }}</td>
                <td colspan="3" class="bg-gray">Última Certificación de Hermeticidad:</td>
                <td colspan="3">{{ \Carbon\Carbon::parse($inspeccion->fecha_inspeccion)->subDay()->format('d/m/Y') }}</td>
            </tr>
        </table>

        {{-- I) RESULTADOS DEL PROTOCOLO --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="7">I) Resultados Obtenidos del Protocolo de Certificación: &nbsp;&nbsp; Apto: A &nbsp;&nbsp;&nbsp; Observado: O &nbsp;&nbsp;&nbsp; NO Apto: N.A</td>
            </tr>
        </table>
        <table class="tabla-full">
            <tr>
                <td class="definiciones">
                    *A.- Apto para circular libremente.<br><br>
                    *O.- Observación, no compromete Hermeticidad. Subsanable antes de la siguiente revisión. No restringe libre Tránsito.<br><br>
                    *N.A.- No debe circular. Peligro de dispersión de material contaminante.
                </td>
                <td colspan="6" style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr class="bg-gray text-center">
                            <td rowspan="2" class="diagonal-cell" style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">
                                <div class="linea-manual"></div>
                                <div class="diag-top">Observación</div>
                                <div class="diag-bottom">Elemento</div>
                            </td>
                            <td rowspan="2" style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">DEFORMIDAD</td>
                            <td rowspan="2" style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">FISURA O ROTURA</td>
                            <td rowspan="2" style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">ÓXIDO</td>
                            <td rowspan="2" style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">RESEQUEDAD</td>
                            <td rowspan="2" style="border: none; border-bottom: 1px solid #777;">LUBRICACIÓN</td>
                        </tr>
                        <tr class="bg-gray text-center">
                            <td colspan="5" style="border: none; border-bottom: 1px solid #777; height: 1px; padding: 0;"></td>
                        </tr>
                        @php
                            $elementos = [
                                'Tapa' => 'tapa', 'Compuerta' => 'compuerta', 'Tolva' => 'tolva', 
                                'Sellos' => 'sellos', 'Bisagras' => 'bisagras', 'Pistones' => 'pistones',
                                'Mangueras y/o Lineas de aire' => 'mangueras', 'Remaches (Únicamente si la tapa está estructurada por paños)' => 'remaches'
                            ];
                        @endphp
                        @foreach($elementos as $label => $key)
                            <tr class="text-center">
                                <td class="bg-gray" style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">{{ $label }}</td>
                                <td style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">{{ $detalle->{$key.'_deformidad'} ?? 'NA' }}</td>
                                <td style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">{{ $detalle->{$key.'_fisura'} ?? 'NA' }}</td>
                                <td style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">{{ $detalle->{$key.'_oxido'} ?? 'NA' }}</td>
                                <td style="border: none; border-right: 1px solid #777; border-bottom: 1px solid #777;">{{ $detalle->{$key.'_resequedad'} ?? 'NA' }}</td>
                                <td style="border: none; border-bottom: 1px solid #777;">{{ $detalle->{$key.'_lubricacion'} ?? 'NA' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>

        {{-- Prueba de Esfuerzo y Cuantificación --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td colspan="3">Prueba de esfuerzo:</td>
                <td colspan="5">* Cuantificar elementos consignados.</td>
            </tr>
        </table>
        <table class="tabla-full">
            <tr class="text-center">
                <td class="bg-gray"></td>
                <td class="bg-gray">APTO</td>
                <td class="bg-gray">NO APTO</td>
                <td class="bg-gray">TIEMPO</td>
                <td class="bg-gray"></td>
                <td class="bg-gray">Bisagras</td>
                <td class="bg-gray">Pistones</td>
                <td class="bg-gray">Mangueras y/o Lineas de aire</td>
                <td class="bg-gray">Remaches (Únicamente si la tapa está estructurada por paños)</td>
            </tr>
            <tr class="text-center">
                <td class="bg-gray">Tapa de apertura y cierre neumático</td>
                <td>{{ $inspeccion->resultado_final == 'APROBADO' ? 'APTO' : null }}</td>
                <td>{{ $inspeccion->resultado_final == 'DESAPROBADO' ? 'NO APTO' : null }}</td>
                <td>{{ $detalle->tiempo_prueba }} min</td>
                <td class="bg-gray">Número</td>
                <td>{{ $detalle->cant_bisagras ?? '0' }}</td>
                <td>{{ $detalle->cant_pistones ?? '0' }}</td>
                <td>{{ $detalle->cant_mangueras ?? '0' }}</td>
                <td>{{ $detalle->cant_remaches ?? '0' }}</td>
            </tr>
            <tr class="text-center">
                <td class="bg-gray">Tapa de apertura</td>
                <td class="bg-gray"></td>
                <td class="bg-gray"></td>
                <td class="bg-gray"></td>
                <td class="bg-gray">Faltantes</td>
                <td>{{ $detalle->faltas_bisagras ?? '0' }}</td>
                <td>{{ $detalle->faltas_pistones ?? '0' }}</td>
                <td>{{ $detalle->faltas_mangueras ?? '0' }}</td>
                <td>{{ $detalle->faltas_remaches ?? '0' }}</td>
            </tr>
        </table>

        {{-- II) Observaciones detectadas --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td >II).- Observaciones detectadas:</td>
            </tr>
        </table>
        <table class="tabla-full" style="table-layout: fixed;">
            <tr class="text-center">
                <td class="bg-gray" style="width: 75%;">Interpretacion de defectos</td>
                <td class="bg-gray" style="width: 25%;">Calificación (A, O, N.A)</td>                
            </tr>
            <tr class="text-center">
                <td style="font-size: 11px; font-weight: bold; text-align: left; padding-left: 10px; width: 75%;">{{ $inspeccion->observaciones }}</td>
                <td style="width: 25%;">@if($inspeccion->resultado_final == 'APROBADO')
                        A
                    @elseif($inspeccion->resultado_final == 'DESAPROBADO')
                        D
                    @else
                        N.A
                    @endif
                </td>
            </tr>

        </table>

        {{-- III) RESULTADO FINAL --}}
        <table class="tabla-full">
            <tr class="bg-gray">
                <td >III).- Resultado del Protocolo de Certificación de Hermeticidad:</td>
            </tr>
        </table>
        <table class="tabla-full text-center">
            <tr class="bg-gray">
                <td>Resultado de inspección</td>
                <td>Vigencia del Certificado</td>
                <td>Fecha de la Próxima Inspección</td>
            </tr>
            <tr>
                <td style="font-size: 12px; font-weight: bold;">{{ $inspeccion->resultado_final == 'APROBADO' ? 'APTO' : 'NO APTO' }}</td>
                <td>{{ $inspeccion->vigencia_meses }} MESES</td>
                <td>{{ \Carbon\Carbon::parse($inspeccion->proxima_inspeccion)->format('d/m/Y') }}</td>
            </tr>
        </table>

        <div style="font-size: 8px; border: 1px solid #777; padding: 5px; margin-top: 5px;">
            Mediante el presente documento, se certifica que el vehículo materia de la inspección, ha sido sometido a la misma, por lo que el conductor
            del vehiculo, se encuentra obligado a portar el mismo. De lo contrario se procederá de acuerdo a lo tipificado en el anexo de la ordenanza
            regional N° 000022 interviniendose el vehiculo y procediendo conforme a lo dispuesto en dicha norma. <br>
            * Las Observaciones han de ser subsanadas antes de la siguiente inspección, de lo contrario se obtendrá el resultado de NO APTO.
        </div>
    </div>
</body>
</html>