<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contrato de Trabajo - {{ $contrato->user->name }}</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            text-align: justify;
        }

        .header {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .clause {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 15px;
            display: block;
        }

        .sub-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 15px;
            display: block;
        }

        .content {
            margin-bottom: 10px;
        }

        .signatures {
            margin-top: 50px;
            width: 100%;
            border-collapse: collapse;
        }

        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto 5px auto;
        }

        .footer-data {
            font-size: 9px;
            margin-top: 5px;
        }

        .bold {
            font-weight: bold;
        }

        ul {
            margin-top: 5px;
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
        }

        .signatures-wrapper {
            margin-top: 80px; /* Aumentamos este valor para dar más espacio */
            page-break-inside: avoid;
        }

        .signature-container {
            position: relative;
            width: 100%;
            height: 120px; /* Aumentamos la altura para acomodar el sello circular */
            text-align: center;
        }

        .firma-img {
            position: absolute;
            /* Ajustamos para que el sello circular quede apenas rozando la línea */
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 180px; /* Bajamos un poco el ancho para que no sature el espacio */
            z-index: 1;
        }

        .line {
            border-top: 1px solid #000;
            width: 80%;
            /* El margen superior de la línea define dónde termina la firma */
            margin: 80px auto 5px auto;
            position: relative;
            z-index: 2;
        }
    </style>
</head>

<body>

    <div class="header">
        CONTRATO DE TRABAJO SUJETO A MODALIDAD POR NECESIDADES DEL MERCADO
    </div>

    <div class="content">
        Conste por el presente documento de <span class="bold">CONTRATO DE TRABAJO SUJETO A MODALIDAD POR NECESIDADES
            DEL MERCADO</span>,
        que celebran al amparo del Art. 57 de la ley de Productividad y competitividad Laboral aprobado por D.S.
        N°.003-97-TR y normas complementarias, de una parte, CITV ANCON S.A.C con RUC N° 20606636823, representado por
        KATHERINE LOPEZ HENRIQUEZ, identificado con DNI. N° 08884851 con domicilio fiscal Carretera Panamericana Norte
        Mz. 04 Lote 04 Asoc. Popular La Variante de Ancón, Ancón - Lima, a quien en adelante se le denominará EL
        EMPLEADOR; y de la otra parte el Sr(a) <span class="bold">{{ $contrato->user->name }}</span> con DNI N°
        <span class="bold">{{ $contrato->user->dni }}</span>, domiciliado en <span
            class="bold">{{ $contrato->user->direccion }}</span>,
        quien en adelante se le denominará EL TRABAJADOR en los términos y condiciones siguientes:
    </div>

    <span class="sub-title">ANTECEDENTES</span>
    <div class="content">
        <span class="bold">PRIMERO:</span> EL EMPLEADOR es una Empresa dedicada como centro de inspección técnica
        vehicular a nivel nacional de vehículos automotrices en general vinculados a su objeto social permitidos por
        Ley, y que cuenta resolución otorgada por el Ministerio de Transporte y Comunicaciones - MTC, que requiere
        cubrir las necesidades de recursos humanos con el objeto de atender incrementos de servicios originado por una
        mayor demanda en el mercado. <br>
        Así mismo cabe mencionar que hemos sido debidamente autorizados con número de registro <span class="bold">N°
            0002094988-2022</span>,
        con fecha de inscripción REMYPE el 15/11/2022.
    </div>

    <span class="sub-title">OBJETO DEL CONTRATO</span>
    <div class="content">
        <span class="bold">SEGUNDO:</span> Por el presente documento <span class="bold">EL EMPLEADOR</span> contrata
        a plazo fijo
        bajo la modalidad ya indicada a <span class="bold">EL TRABAJADOR</span> quien se desempeñará como <span
            class="bold">
            {{ $contrato->cargo }}</span> en relación con las causas objetivas señaladas en la cláusula anterior.
        Al amparo de lo dispuesto en el Art. 57 de la ley de Productividad y competitividad Laboral aprobado por D.S.
        N°.003-97-TR y normas complementarias; el presente contrato de trabajo se encuentra sujeta a modalidad por
        <span class="bold">NECESIDADES DEL MERCADO.</span>
    </div>

    <span class="sub-title">DE LA DURACIÓN Y HORARIO DEL CONTRATO LABORAL</span>
    <div class="content">
        <span class="bold">TERCERO:</span> El plazo del contrato regirá a partir del
        <span class="bold">{{ $contrato->fecha_inicio_contrato->translatedFormat('d \d\e F \d\e\l Y') }}</span>,
        venciendo
        automáticamente el <span
            class="bold">{{ $contrato->fecha_vencimiento->translatedFormat('d \d\e F \d\e\l Y') }}</span>,
        sin necesidad de pre aviso alguno. De renovarse, las partes fijaran por escrito el plazo y demás condiciones del
        contrato.
    </div>

    <div class="content">
        <span class="bold">CUARTO: EL TRABAJADOR</span> deberá cumplir una jornada diaria no inferior de ocho (08)
        horas diarias hasta completar las cuarenta y ocho (48) horas semanales, teniendo un refrigerio de 45 (minutos),
        que será tomado de 1:00 PM a 2:00 PM., dichos horarios sujetándose a los turnos de trabajo que establezca
        <span class="bold">EL EMPLEADOR.</span>
    </div>

    <div class="content">
        <span class="bold">QUINTO: EL TRABAJADOR</span> deberá cumplir con las normas propias del Centro de Trabajo,
        así como las contenidas en el Reglamento Interno de Trabajo y en las demás normas laborales, y las que se
        impartan por necesidades del servicio en la empresa, de conformidad con el Art. 9 de la Ley de Productividad y
        Competitividad Laboral aprobado por D.S. N° 003-97-TR.
    </div>

    <span class="sub-title">DE LA FORMA DE PAGO</span>
    <div class="content">
        <span class="bold">SEXTO:</span> En contraprestación a los servicios de <span class="bold">EL
            TRABAJADOR</span>;
        <span class="bold">EL EMPLEADOR</span> se obliga a pagar una remuneración <span class="bold">S/
            {{ number_format($contrato->sueldo_bruto, 2) }} ({{ $sueldo_letras }})</span>
        de manera mensual, de las cuales se deducen las aportaciones y descuentos por tributos establecidos por ley que
        le resulten aplicables; Igualmente se obliga a facilitar al trabajador los materiales necesarios para que
        desarrolle
        sus actividades, pacto o costumbre que tuviera el trabajador en el centro de trabajo contratado a plazo
        determinado.
    </div>

    <span class="sub-title">DE LAS OBLIGACIONES Y CLAUSULAS FINALES</span>
    <div class="content">
        <span class="bold">SEPTIMO: EL EMPLEADOR</span> se obliga a inscribir a EL TRABAJADOR en la Planilla
        Electrónica - PDT 601, así como poner a conocimiento de la Autoridad Administrativa de Trabajo el presente
        contrato, para su conocimiento y registro, en cumplimiento de lo dispuesto por el artículo 73° del Texto Único
        Ordenado del Decreto Legislativo N° 728, Ley de Productividad y Competitividad Laboral, aprobado mediante
        Decreto Supremo N° 003- 97-TR.
    </div>
    <div class="content">
        <span class="bold">OCTAVO:</span> Queda entendido que <span class="bold">EL EMPLEADOR</span> no está
        obligado a dar aviso alguno adicional referente al término del presente contrato, operando su extinción
        en la fecha de su vencimiento, conforme a la cláusula tercera, oportunidad en la cual se abonará a
        <span class="bold">EL TRABAJADOR</span> los beneficios sociales, que le pudieran corresponder de acuerdo
        a Ley de las Micro y Pequeñas Empresas.
    </div>

    <div class="content">
        <span class="bold">NOVENO: EL EMPLEADOR Y EL TRABAJADOR</span> asume las siguientes obligaciones que están
        relacionadas al vínculo laboral que se da inicio:
        <ul>
            <li>Del <span class="bold">Empleador</span>, estar sujeto al art. 22° del Texto Único Ordenado del Decreto
                Legislativo
                N° 728; referente a <span class="bold">"La Causa Justa"</span> del despido de un trabajador.
            </li>
            <li>Del <span class="bold">Trabajador</span>, estar sujeto al art. 25° literal a; b; c; d; e; f; g; h; del
                Texto Único
                Ordenado del Decreto Legislativo N° 728; referente a <span class="bold">"Faltas Graves"</span> como
                causa de despido
                del trabajador.
            </li>
            <li>Del <span class="bold">Trabajador</span>, no realizar actividad alguna que pueda perjudicar a la
                empresa; así como
                mantener la confidencialidad de la información de la empresa.
            </li>
            <li>Del <span class="bold">Trabajador</span>, deberá también tener disposición para efectuar temporalmente
                dichas labores
                en las distintas ciudades del país o en las que fuera autorizada la empresa.
            </li>
        </ul>
    </div>

    <span class="sub-title">DE LA CLAUSULA LABORAL</span>
    <div class="content">
        <span class="bold">DÉCIMO:</span> En todo lo no previsto por el presente contrato, se estará a las
        disposiciones laborales que regulan los contratos de trabajo sujetos a modalidad, contenidos en el Texto Único
        Ordenado del Decreto Legislativo N° 728, aprobado por el Decreto Supremo No 003- 97-TR, Ley de Productividad y
        Competitividad Laboral.
    </div>

    <span class="sub-title">CLAUSULA DE SOLUCION DE CONFLICTOS</span>
    <div class="content">
        <span class="bold">DÉCIMO PRIMERO:</span> Las partes contratantes renuncian expresamente al fuero judicial de
        sus domicilios y se someten a la jurisdicción de los jueces de Lima, para resolver cualquier controversia que el
        incumplimiento del presente contrato pudiera originar.
    </div>

    <div class="content" style="margin-top: 20px;">
        Conformes con todas las cláusulas del presente contrato, firman las partes por duplicado, a los
        <span class="bold">{{ now()->format('d') }}</span> días de
        <span class="bold">{{ now()->translatedFormat('F') }}</span>
        del año <span class="bold">{{ now()->format('Y') }}</span>.
    </div>

    <!-- Firmas -->
    <div class="signatures-wrapper">
        <table class="signatures" style="width: 100%;">
            <tr>
                <td>
                    <div class="signature-container">
                        <img src="{{ public_path('images/firmaDoctora2.png') }}" class="firma-img">

                        <div class="line"></div>
                        <span class="bold">KATHERINE LOPEZ HENRIQUEZ</span><br>
                        <span style="font-size: 10px;">DNI: 08884851</span><br>
                        <span style="font-size: 10px;">Gerente General</span>
                    </div>
                </td>
                <td>
                    <div class="signature-container">
                        <div class="line"></div>
                        <span class="bold">{{ $contrato->user->name }}</span><br>
                        <span style="font-size: 10px;">DNI: {{ $contrato->user->dni }}</span><br>
                        <span style="font-size: 10px;">EL TRABAJADOR</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
