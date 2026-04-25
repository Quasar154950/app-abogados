<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Expediente - {{ $expediente->caratula }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
            padding: 32px;
        }

        .page {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 36px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
        }

        .actions {
            max-width: 850px;
            margin: 0 auto 18px auto;
            text-align: right;
        }

        button {
            background: #166534;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            border-bottom: 2px solid #111827;
            padding-bottom: 18px;
            margin-bottom: 28px;
        }

        .brand h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: .5px;
        }

        .brand p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .doc-info {
            text-align: right;
            font-size: 12px;
            color: #374151;
        }

        .title {
            margin-bottom: 24px;
        }

        .title h2 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #e5e7eb;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .section {
            margin-top: 24px;
        }

        .section h3 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 14px;
            background: #f9fafb;
        }

        .label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .value {
            font-size: 14px;
            font-weight: 600;
        }

        .observaciones {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            background: #f9fafb;
            line-height: 1.5;
            font-size: 14px;
        }

        .footer {
            margin-top: 36px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .page {
                border: none;
                border-radius: 0;
                padding: 20px;
                max-width: none;
            }
        }
    </style>
</head>
<body>

<div class="actions">
    <button onclick="window.print()">🖨️ Imprimir expediente</button>
</div>

<div class="page">

    <div class="header">
        <div class="brand" style="display: flex; align-items: center; gap: 12px;">
    
    <img src="/images/logo.png" alt="Logo" style="height: 40px;">

    <div>
        <h1 style="margin: 0;">Estudio Jurídico M. Vairo</h1>
        <p style="margin: 4px 0 0;">Ficha de expediente</p>
    </div>

</div>

        <div class="doc-info">
            <strong>Fecha de emisión</strong><br>
            {{ now()->format('d/m/Y') }}
        </div>
    </div>

    <div class="title">
        <h2>{{ $expediente->caratula }}</h2>
        <span class="badge">
            {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
        </span>
    </div>

    <div class="section">
        <h3>Datos del expediente</h3>

        <div class="grid">
            <div class="item">
                <span class="label">Número de expediente</span>
                <span class="value">{{ $expediente->numero_expediente ?: 'Sin cargar' }}</span>
            </div>

            <div class="item">
                <span class="label">Juzgado</span>
                <span class="value">{{ $expediente->juzgado ?: 'Sin cargar' }}</span>
            </div>

            <div class="item">
                <span class="label">Tipo</span>
                <span class="value">{{ $expediente->tipo ? ucfirst($expediente->tipo) : 'Sin definir' }}</span>
            </div>

            <div class="item">
                <span class="label">Fecha de inicio</span>
                <span class="value">
                    {{ $expediente->fecha_inicio ? \Carbon\Carbon::parse($expediente->fecha_inicio)->format('d/m/Y') : 'Sin fecha' }}
                </span>
            </div>

            <div class="item">
                <span class="label">Fecha de carga</span>
                <span class="value">{{ $expediente->created_at->format('d/m/Y') }}</span>
            </div>

            <div class="item">
                <span class="label">Cliente</span>
                <span class="value">{{ $expediente->cliente->nombre ?? 'Sin cliente asociado' }}</span>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Observaciones</h3>

        <div class="observaciones">
            {{ $expediente->observaciones ?: 'Sin observaciones cargadas.' }}
        </div>
    </div>

    <div class="footer">
        Documento generado desde el sistema de gestión de expedientes.
    </div>

</div>

</body>
</html>