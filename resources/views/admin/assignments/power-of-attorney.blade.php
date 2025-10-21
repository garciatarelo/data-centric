<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carta Poder</title>
    <!-- Estos son los estilos del PDF -->
    <!-- Los pongo aquí mismo porque DomPDF no puede cargar archivos CSS externos -->
    <style>
        /* Estilo base del documento */
        body {
            font-family: Arial, sans-serif; /* Uso Arial porque es una fuente que siempre funciona en PDFs */
            line-height: 1.4; /* Espacio entre líneas para que se vea bien */
            margin: 30px; /* Margen para que no quede pegado a los bordes */
            color: #333; /* Gris oscuro para el texto, no uso negro puro */
            font-size: 11pt; /* Tamaño de letra legible */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 18pt;
        }
        .content {
            margin-bottom: 20px;
        }
        .device-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .device-info h3 {
            margin: 0 0 10px 0;
            font-size: 12pt;
        }
        .user-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .user-info h3 {
            margin: 0 0 10px 0;
            font-size: 12pt;
        }
        .conditions {
            margin: 15px 0;
        }
        .conditions h3 {
            margin: 0 0 10px 0;
            font-size: 12pt;
        }
        .conditions ul {
            padding-left: 20px;
            margin: 10px 0;
        }
        .conditions li {
            margin-bottom: 5px;
        }
        .signatures {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-container {
            margin-top: 20px;
        }
        .signature-box {
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
        }
        .signature-line {
            border-top: 1px solid black;
            width: 250px;
            margin: 30px auto 8px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table, th, td {
            border: 1px solid #dee2e6;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
        }
        .qr-code p {
            margin: 5px 0;
            font-size: 10pt;
        }
        .footer {
            text-align: center;
            font-size: 9pt;
            color: #6c757d;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .last-page-footer {
            break-after: page;
            page-break-after: always;
        }
        @page :first {
            margin-bottom: 0;
        }
        @page {
            margin-bottom: 0;
        }
        @page :last {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CARTA PODER - ASIGNACIÓN DE DISPOSITIVO</h1>
    </div>

    <div class="content">
        @php
            \Carbon\Carbon::setLocale('es');
        @endphp
        <p>
            En Nuevo Casas Grandes, Chihuahua, México, a los {{ now()->format('d') }} días del mes de 
            {{ ucfirst(now()->isoFormat('MMMM')) }} de {{ now()->format('Y') }}.
        </p>

        <div class="device-info">
            <h3>Información del Dispositivo</h3>
            <table>
                <tr>
                    <th width="30%">Marca</th>
                    <td>{{ $assignment->device->brand }}</td>
                </tr>
                <tr>
                    <th>Modelo</th>
                    <td>{{ $assignment->device->model }}</td>
                </tr>
                <tr>
                    <th>Número de Serie</th>
                    <td>{{ $assignment->device->serial }}</td>
                </tr>
            </table>
        </div>

        <div class="user-info">
            <h3>Información del Usuario</h3>
            <p>
                <strong>Nombre:</strong> {{ $assignment->user->name }}<br>
                <strong>Email:</strong> {{ $assignment->user->email }}
            </p>
        </div>

        <div class="conditions">
            <h3>Términos y Condiciones</h3>
            <p>El usuario que recibe el dispositivo se compromete a:</p>
            <ul>
                <li>Hacer uso responsable del dispositivo asignado.</li>
                <li>Mantener el dispositivo en buen estado y reportar cualquier daño o mal funcionamiento de manera inmediata.</li>
                <li>No instalar software no autorizado ni realizar modificaciones al hardware.</li>
                <li>Utilizar el dispositivo únicamente para fines laborales.</li>
                <li>Devolver el dispositivo en las mismas condiciones en que fue recibido cuando sea requerido o al terminar su relación laboral.</li>
                <li>Mantener la confidencialidad de la información almacenada en el dispositivo.</li>
            </ul>
        </div>
    </div>

    <div class="signatures">
        <div class="signature-container">
            <div class="signature-box">
                <div class="signature-line"></div>
                <strong>Firma del Usuario</strong><br>
                {{ $assignment->user->name }}
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <strong>Entregado por</strong><br>
                {{ $assignment->assignedBy->name }}
            </div>
        </div>
    </div>

    <div class="qr-code">
        <p>Escanea para verificar la autenticidad:</p>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('admin.assignments.show', $assignment->id)) }}" 
             style="width: 150px; height: 150px;">
        <p style="font-size: 12px; margin-top: 10px;">
            Este código QR contiene el enlace de verificación de esta asignación
        </p>
    </div>

    <div class="footer">
        <hr style="width: 50%; margin: 10px auto; border-top: 1px solid #ddd;">
        ID de Asignación: {{ $assignment->id }} | 
        Fecha de Asignación: {{ $assignment->assigned_at->locale('es')->isoFormat('DD [de] MMMM [de] YYYY, HH:mm') }} | 
        Generado el: {{ now()->locale('es')->isoFormat('DD [de] MMMM [de] YYYY, HH:mm') }}
    </div>
</body>
</html>
