<!DOCTYPE html>
<html>

<head>
    <title>Reporte MTC</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .title-seccion {
            background: #eee;
            padding: 5px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .aprobado {
            color: green;
            font-weight: bold;
        }

        .desaprobado {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Reporte Mensual MTC - {{ $mes_nombre }} {{ $anio }}</h2>
    </div>

    <div class="title-seccion">VEHÍCULOS INSPECCIONADOS (APROBADOS)</div>
    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>PLACA</th>
                <th>CERTIFICADO</th>
                <th>RESULTADO</th>
                <th>CATEGORÍA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inspeccionados as $item)
                <tr>
                    <td>{{ $item->fecha_inspeccion?->format('d/m/Y') }}</td>
                    <td>{{ $item->placa_vehiculo }}</td>
                    <td>{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                    <td class="aprobado">{{ $item->resultado_estado }}</td>
                    <td>{{ $item->categoria_vehiculo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-seccion">VEHÍCULOS DESAPROBADOS</div>
    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>PLACA</th>
                <th>P. BRUTO</th>
                <th>DEFECTOS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($desaprobados as $item)
                <tr>
                    <td>{{ $item->fecha_inspeccion?->format('d/m/Y') }}</td>
                    <td>{{ $item->placa_vehiculo }}</td>
                    <td>{{ number_format($item->peso_bruto_v, 2) }}</td>
                    <td class="desaprobado">{{ $item->codigos_defectos }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="title-seccion">CERTIFICADOS ANULADOS</div>
    <table>
        <thead>
            <tr>
                <th>N° FORMATO</th>
                <th>TIPO ERROR</th>
                <th>ACCION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anulados as $item)
                <tr>
                    <td>{{ $item->serie_certificado }}-{{ $item->correlativo_certificado }}</td>
                    <td>ERROR DE IMPRESIÓN</td>
                    <td>ANULADO</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
