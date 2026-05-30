<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Meteorológico</title>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
    }

    h1 {
        text-align: center;
        margin-bottom: 5px;
    }

    .info {
        text-align: center;
        margin-bottom: 10px;
        color: #666;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #333;
        padding: 4px;
        font-size: 8px;
    }

    th {
        background: #eeeeee;
    }

    td.num {
        text-align: right;
    }
</style>
</head>

<body>

<h1>Reporte Meteorológico - Microclima Tandil</h1>

<div class="info">
    Generado: {{ $fechaGen }} |
    Filas: {{ count($rows) }}
</div>

<table>

    <thead>
    <tr>
        @foreach($cols as $meta)
            <th>{{ $meta[0] }}</th>
        @endforeach
    </tr>
    </thead>

    <tbody>

    @forelse($rows as $row)

        <tr>

            @foreach($cols as $campo => $meta)

                <td>
                    {{ $row[$campo] ?? '' }}
                </td>

            @endforeach

        </tr>

    @empty

        <tr>
            <td colspan="{{ count($cols) }}">
                No hay datos disponibles.
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

</body>
</html>
