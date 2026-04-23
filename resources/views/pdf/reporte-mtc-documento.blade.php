<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte MTC</title>
    <style>
        @page {
            margin: 0.8cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5px;
            color: #222;
            line-height: 1.2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 14px;
            color: #1a202c;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
            /* Crucial para que respete los anchos definidos */
            page-break-inside: auto;
        }

        thead {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th,
        td {
            border: 0.5px solid #a0aec0;
            padding: 4px 2px;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #edf2f7;
            color: #2d3748;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7px;
        }

        .title-seccion {
            background: #2d3748;
            color: white;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 0;
            page-break-after: avoid;
        }

        .nowrap {
            white-space: nowrap;
        }

        .text-left {
            text-align: left;
            padding-left: 4px;
        }

        .aprobado {
            color: #2f855a;
            font-weight: bold;
        }

        .desaprobado {
            color: #c53030;
            font-weight: bold;
        }

        /* --- CONTROL DE ANCHOS TABLA 1 --- */
        .col-fecha {
            width: 50px;
        }

        .col-placa {
            width: 45px;
        }

        .col-serie {
            width: 60px;
        }

        .col-resultado {
            width: 28px;
        }

        /* ANCHO PEQUEÑO */
        .col-servicio {
            width: 105px;
        }

        /* ANCHO GRANDE */
        .col-categoria {
            width: 28px;
        }

        /* ANCHO PEQUEÑO */
        .col-formato {
            width: 75px;
        }

        .col-reinsp {
            width: 28px;
        }

        /* ANCHO PEQUEÑO */
    </style>
</head>

<body>
    {{-- 
    <div class="header">
        <h2>Reporte Mensual MTC - {{ $mes_nombre }} {{ $anio }}</h2>
    </div>
    --}}
    @if($seccion == 1)
        <div class="title-seccion">1. VEHÍCULOS INSPECCIONADOS (APROBADOS)</div>
        <table>
            <thead>
                <tr>
                    <th class="col-fecha">FECHA ING.</th>
                    <th class="col-placa">N° PLACA</th>
                    <th class="col-serie">SERIE HOJA</th>
                    <th class="col-resultado">RESULTADO</th>
                    <th class="col-servicio">SERVICIO BRINDADO</th>
                    <th class="col-servicio">SERVICIO DESTINADO</th>
                    <th class="col-categoria">CATEGORIA</th>
                    <th class="col-fecha">PROX. CERT.</th>
                    <th class="col-formato">NUM. FORMATO</th>
                    <th class="col-reinsp">REINSP.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inspeccionados as $item)
                    <tr>
                        <td class="nowrap">{{ $item->fecha_inspeccion?->format('d/m/Y') }}</td>
                        <td class="nowrap" style="font-weight: bold;">{{ $item->placa_vehiculo }}</td>
                        <td class="nowrap">{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                        <td class="aprobado">{{ $item->resultado_estado }}</td>
                        <td class="text-left">{{ $item->tipo_inspeccion }}</td>
                        <td class="text-left">{{ $item->tipo_atencion }}</td>
                        <td>{{ $item->categoria_vehiculo }}</td>
                        <td class="nowrap">{{ $item->fecha_vencimiento?->format('d/m/Y') }}</td>
                        <td class="nowrap">{{ $item->numero_certificado_mtc }}</td>
                        <td>{{ $item->es_reinspeccion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($seccion == 2)
        <div class="title-seccion">2. VEHÍCULOS DESAPROBADOS</div>
        <table>
            <thead>
                <tr>
                    <th class="col-fecha">FECHA</th>
                    <th class="col-placa">N° PLACA</th>
                    <th style="width: 50px;">P. BRUTO</th>
                    <th style="width: 40px;">CATEGORIA</th>
                    <th style="width: 80px;">N° INFORME</th>
                    <th>DEFECTOS (GRAVE / M. GRAVE)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($desaprobados as $item)
                    <tr>
                        <td class="nowrap">{{ $item->fecha_inspeccion?->format('d/m/Y') }}</td>
                        <td class="nowrap" style="font-weight: bold;">{{ $item->placa_vehiculo }}</td>
                        <td>{{ number_format($item->peso_bruto_v, 2) }}</td>
                        <td>{{ $item->categoria_vehiculo }}</td>
                        <td class="nowrap">{{ $item->numero_certificado_mtc }}</td>
                        <td class="desaprobado text-left">{{ $item->codigos_defectos }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($seccion == 3)
        <div class="title-seccion">3. CERTIFICADOS ANULADOS</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">ITEM</th>
                    <th>N° FORMATO</th>
                    <th>N° CALCOMANIA</th>
                    <th>TIPO ERROR</th>
                    <th>ACCIÓN TOMADA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anulados as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="nowrap">{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                        <td class="nowrap">{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                        <td class="text-left">ERROR DE IMPRESIÓN</td>
                        <td class="text-left">SD‐388‐0018618</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>

</html>


{{-- 
<!DOCTYPE html>
<html>
<head>
    <title>Reporte MTC</title>
    <style>
        /* Estilo general */
        body { font-family: Helvetica; font-size: 10pt; }

        /* Configuración para el Comunicado (Pág 1) */
        .primera-pagina {
            padding-top: 4.5cm; /* Ajusta según el alto de tu encabezado membretado */
            padding-bottom: 3cm; /* Ajusta según el pie de página membretado */
            padding-left: 1cm;
            padding-right: 1cm;
            page-break-after: always;
        }

        /* Estilos para las tablas (Pág 2 en adelante) */
        .seccion-tablas {
            margin: 0.8cm;
        }
        table { width: 100%; border-collapse: collapse; font-size: 8px; }
        th, td { border: 0.5px solid #ccc; padding: 4px; }
    </style>
</head>
<body>
    <div class="primera-pagina">
        <div style="text-align: right;">Ancón, {{ now()->format('d/m/Y') }}</div>
        
        <p>Señor:<br>
        <strong>JORGE CAYO ESPINOZA GALARZA</strong><br>
        Director de la Dirección de Circulación Vial...<br>
        Presente.-</p>

        <p><strong>ASUNTO: INFORME DE LOS VEHICULOS INSPECCIONADOS...</strong></p>

        <p>Yo, <strong>KATHERINE LOPEZ HENRIQUEZ</strong>, Gerente General de CITV ANCÓN S.A.C...</p>
        
        <p>Presentamos la información de <strong>{{ strtoupper($mes_nombre) }} {{ $anio }}</strong>...</p>

        <div style="margin-top: 50px; text-align: center;">
            Atentamente,<br><br><br>
            ___________________________<br>
            <strong>KATHERINE LOPEZ HENRIQUEZ</strong>
        </div>
    </div>

    <div class="seccion-tablas">
        <h3>1. VEHÍCULOS INSPECCIONADOS (APROBADOS)</h3>
        <table>
            <thead>
                <tr>
                    <th class="col-fecha">FECHA ING.</th>
                    <th class="col-placa">N° PLACA</th>
                    <th class="col-serie">SERIE HOJA</th>
                    <th class="col-resultado">RESULTADO</th>
                    <th class="col-servicio">SERVICIO BRINDADO</th>
                    <th class="col-servicio">SERVICIO DESTINADO</th>
                    <th class="col-categoria">CATEGORIA</th>
                    <th class="col-fecha">PROX. CERT.</th>
                    <th class="col-formato">NUM. FORMATO</th>
                    <th class="col-reinsp">REINSP.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inspeccionados as $item)
                    <tr>
                        <td class="nowrap">{{ $item->fecha_inspeccion?->format('d/m/Y') }}</td>
                        <td class="nowrap" style="font-weight: bold;">{{ $item->placa_vehiculo }}</td>
                        <td class="nowrap">{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                        <td class="aprobado">{{ $item->resultado_estado }}</td>
                        <td class="text-left">{{ $item->tipo_inspeccion }}</td>
                        <td class="text-left">{{ $item->tipo_atencion }}</td>
                        <td>{{ $item->categoria_vehiculo }}</td>
                        <td class="nowrap">{{ $item->fecha_vencimiento?->format('d/m/Y') }}</td>
                        <td class="nowrap">{{ $item->numero_certificado_mtc }}</td>
                        <td>{{ $item->es_reinspeccion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
--}}
