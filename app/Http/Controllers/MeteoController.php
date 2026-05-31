<?php

namespace App\Http\Controllers;

use PDO;
use PDOException;
use Barryvdh\DomPDF\Facade\Pdf;

class MeteoController extends Controller
{
    public function datos()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        try {
            $host = env('METEO_DB_HOST', '127.0.0.1');
            $port = env('METEO_DB_PORT', '5432');
            $user = env('METEO_DB_USERNAME', 'postgres');
            $pass = env('METEO_DB_PASSWORD', '');
            $dbMeteo = env('METEO_DB_DATABASE', 'meteotandil');
            $dbAstro = env('ASTRO_DB_DATABASE', 'almanaque');

            $pdoMeteo = new PDO("pgsql:host=$host;port=$port;dbname=$dbMeteo", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            $pdoAstro = new PDO("pgsql:host=$host;port=$port;dbname=$dbAstro", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            $pdoMeteo->exec("SET CLIENT_ENCODING TO 'UTF8'");
            $pdoAstro->exec("SET CLIENT_ENCODING TO 'UTF8'");

            $meteo = $pdoMeteo->query("
                SELECT id, tiempo,
                       temperatura, sensacion_termica, humedad,
                       punto_rocio, presion, techo_nubes, lluvia_acumulada,
                       angulo_viento, direccion_viento, velocidad_viento, rafagas_viento,
                       indice_uv, uv_peligrosidad, luminosidad,
                       latitud, longitud, altitud,
                       intensidad_lluvia_maxima_diaria,
                       intensidad_lluvia_promedio_diaria
                FROM public.clima_tandil
                ORDER BY tiempo DESC
                LIMIT 1
            ")->fetch();

            $astro = $pdoAstro->query("
                SELECT fecha_hora, alba, salida_sol, puesta_sol, anochecer, duracion_dia,
                       salida_luna, puesta_luna, fase_lunar, ciclo_lunar, visibilidad_lunar
                FROM public.astronomia
                ORDER BY fecha_hora DESC
                LIMIT 1
            ")->fetch();

            $out = ['ok' => true];

            if ($meteo) {
                $out['tiempo'] = $meteo['tiempo'];
                $out['temperatura'] = $this->floatOrNull($meteo['temperatura']);
                $out['sensacion_termica'] = $this->floatOrNull($meteo['sensacion_termica']);
                $out['humedad'] = $this->floatOrNull($meteo['humedad']);
                $out['punto_rocio'] = $this->floatOrNull($meteo['punto_rocio']);
                $out['presion'] = $this->floatOrNull($meteo['presion']);
                $out['techo_nubes'] = $this->floatOrNull($meteo['techo_nubes']);
                $out['lluvia_acumulada'] = $this->floatOrNull($meteo['lluvia_acumulada']);

                $out['indice_uv'] = $this->floatOrNull($meteo['indice_uv']);
                $out['uv_peligrosidad'] = $meteo['uv_peligrosidad'];

                $out['direccion_viento'] = $meteo['direccion_viento'];
                $out['angulo_viento'] = $this->floatOrNull($meteo['angulo_viento']);
                $out['velocidad_viento'] = $this->floatOrNull($meteo['velocidad_viento']);
                $out['rafagas_viento'] = $this->floatOrNull($meteo['rafagas_viento']);

                $out['luminosidad'] = $this->floatOrNull($meteo['luminosidad']);
                $out['latitud'] = $this->floatOrNull($meteo['latitud']);
                $out['longitud'] = $this->floatOrNull($meteo['longitud']);
                $out['altitud'] = $this->floatOrNull($meteo['altitud']);

                $out['intensidad_lluvia_maxima_diaria'] = $this->floatOrNull($meteo['intensidad_lluvia_maxima_diaria']);
                $out['intensidad_lluvia_promedio_diaria'] = $this->floatOrNull($meteo['intensidad_lluvia_promedio_diaria']);
            }

            if ($astro) {
                $out['alba'] = $astro['alba'];
                $out['salida_sol'] = $astro['salida_sol'];
                $out['puesta_sol'] = $astro['puesta_sol'];
                $out['anochecer'] = $astro['anochecer'];
                $out['duracion_dia'] = $astro['duracion_dia'];
                $out['salida_luna'] = $astro['salida_luna'];
                $out['puesta_luna'] = $astro['puesta_luna'];
                $out['fase_lunar'] = $astro['fase_lunar'];
                $out['ciclo_lunar'] = $astro['ciclo_lunar'];
                $out['visibilidad_lunar'] = $this->floatOrNull($astro['visibilidad_lunar']);
            }

            return response()->json($out, 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (\Exception $e) {
    return response()->json([
        'ok' => false,
        'error' => 'DB: ' . $e->getMessage(),
    ], 500);
}

}

public function lecturaActual()
{
    try {
        $host = env('METEO_DB_HOST', '127.0.0.1');
        $port = env('METEO_DB_PORT', '5432');
        $user = env('METEO_DB_USERNAME', 'postgres');
        $pass = env('METEO_DB_PASSWORD', '');
        $dbMeteo = env('METEO_DB_DATABASE', 'meteotandil');

        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbMeteo", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $row = $pdo->query("
            SELECT
                tiempo AS ts,
                temperatura AS temp,
                humedad AS hum
            FROM public.clima_tandil
            ORDER BY tiempo DESC
            LIMIT 1
        ")->fetch();

        if (!$row) {
            return response()->json([
                'ts' => now()->format('Y-m-d H:i:s'),
                'temp' => null,
                'hum' => null,
            ]);
        }

        return response()->json([
            'ts' => $row['ts'],
            'temp' => is_null($row['temp']) ? null : (float)$row['temp'],
            'hum' => is_null($row['hum']) ? null : (float)$row['hum'],
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function reportePdf()
{
    ini_set('memory_limit', '512M');
    set_time_limit(60);

    $limit = request()->integer('limit', 200);
    $limit = max(1, min($limit, 5000));

    $cols = [
        'id' => ['ID','0'],
        'tiempo' => ['Tiempo','dt'],
        'temperatura' => ['Temp (°C)','1'],
        'sensacion_termica' => ['Sens. (°C)','1'],
        'humedad' => ['HR (%)','0'],
        'punto_rocio' => ['P. rocío (°C)','1'],
        'presion' => ['Pres. (hPa)','1'],
        'techo_nubes' => ['Techo (m)','0'],
        'lluvia_acumulada' => ['Lluvia (mm)','1'],
        'intensidad_lluvia_maxima_diaria' => ['Int. máx (mm/h)','1'],
        'intensidad_lluvia_promedio_diaria' => ['Int. prom (mm/h)','1'],
        'angulo_viento' => ['Ángulo (°)','0'],
        'direccion_viento' => ['Dir. viento','str'],
        'velocidad_viento' => ['Viento (km/h)','1'],
        'rafagas_viento' => ['Ráfagas (km/h)','1'],
        'indice_uv' => ['Índice UV','1'],
        'uv_peligrosidad' => ['Pelig. UV','str'],
        'luminosidad' => ['Lum. (lx)','0'],
        'latitud' => ['Lat.','6'],
        'longitud' => ['Long.','6'],
        'altitud' => ['Alt. (m)','1'],
    ];

    $select = implode(', ', array_map(fn($c) => "public.clima_tandil.$c", array_keys($cols)));

    $host = env('METEO_DB_HOST', '127.0.0.1');
    $port = env('METEO_DB_PORT', '5432');
    $user = env('METEO_DB_USERNAME', 'postgres');
    $pass = env('METEO_DB_PASSWORD', '');
    $dbMeteo = env('METEO_DB_DATABASE', 'meteotandil');

    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbMeteo", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sql = "SELECT $select FROM public.clima_tandil ORDER BY public.clima_tandil.tiempo DESC LIMIT :lim";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll();

    $html = view('meteo.reporte-pdf', [
        'cols' => $cols,
        'rows' => $rows,
        'limit' => $limit,
        'fechaGen' => now('America/Argentina/Buenos_Aires')->format('Y-m-d H:i'),
    ])->render();

    $pdf = Pdf::loadHTML($html)->setPaper('a3', 'landscape');

    $nombre = 'Reporte_Meteo_Tandil_' . now()->format('Y-m-d_His') . '.pdf';

    return $pdf->download($nombre);
}

public function actualizarAstronomia()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $latitude = -37.3215;
    $longitude = -59.1365;
    $timezone = -3;
    $fecha = now('America/Argentina/Buenos_Aires')->format('Y-m-d');

    try {
        $url = 'https://aa.usno.navy.mil/api/rstt/oneday?' . http_build_query([
            'date' => $fecha,
            'coords' => $latitude . ',' . $longitude,
            'tz' => $timezone,
        ]);

        $response = @file_get_contents($url);

        if ($response === false) {
            return response()->json([
                'ok' => false,
                'error' => 'No se pudo consultar la API USNO.',
                'url' => $url,
            ], 500);
        }

        $json = json_decode($response, true);

        if (!$json || isset($json['error'])) {
            return response()->json([
                'ok' => false,
                'error' => $json['error'] ?? 'Respuesta inválida de USNO.',
                'raw' => $json,
            ], 500);
        }

        $data = $json['properties']['data'] ?? null;

        if (!$data) {
            return response()->json([
                'ok' => false,
                'error' => 'USNO no devolvió properties.data.',
                'raw' => $json,
            ], 500);
        }

        $sunData = $data['sundata'] ?? [];
        $moonData = $data['moondata'] ?? [];

        $buscarHora = function (array $items, array $nombres) {
            foreach ($items as $item) {
                $phen = strtolower($item['phen'] ?? '');
                foreach ($nombres as $nombre) {
                    if (str_contains($phen, strtolower($nombre))) {
                        return $item['time'] ?? null;
                    }
                }
            }
            return null;
        };

        $alba = $buscarHora($sunData, ['Begin Civil Twilight', 'BC']);
        $salidaSol = $buscarHora($sunData, ['Rise', 'Sunrise']);
        $puestaSol = $buscarHora($sunData, ['Set', 'Sunset']);
        $anochecer = $buscarHora($sunData, ['End Civil Twilight', 'EC']);

        $salidaLuna = $buscarHora($moonData, ['Rise', 'Moonrise']);
        $puestaLuna = $buscarHora($moonData, ['Set', 'Moonset']);

        $faseIngles = $data['curphase'] ?? null;
        $visibilidadRaw = $data['fracillum'] ?? null;

        $faseLunar = $this->traducirFaseLunar($faseIngles);
        $visibilidadLunar = $this->normalizarIluminacionLunar($visibilidadRaw);
        $cicloLunar = $this->calcularCicloLunar($faseLunar);

        $duracionDia = null;

        if ($salidaSol && $puestaSol) {
            $inicio = strtotime($fecha . ' ' . $salidaSol);
            $fin = strtotime($fecha . ' ' . $puestaSol);

            if ($inicio && $fin && $fin > $inicio) {
                $duracionDia = gmdate('H:i:s', $fin - $inicio);
            }
        }

        $host = env('METEO_DB_HOST', '127.0.0.1');
        $port = env('METEO_DB_PORT', '5432');
        $user = env('METEO_DB_USERNAME', 'postgres');
        $pass = env('METEO_DB_PASSWORD', '');
        $dbAstro = env('ASTRO_DB_DATABASE', 'almanaque');

        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbAstro", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $pdo->exec("SET CLIENT_ENCODING TO 'UTF8'");

        $sql = "INSERT INTO public.astronomia
            (fecha_hora, alba, salida_sol, puesta_sol, anochecer, duracion_dia,
             salida_luna, puesta_luna, fase_lunar, ciclo_lunar, visibilidad_lunar)
            VALUES
            (:fecha_hora, :alba, :salida_sol, :puesta_sol, :anochecer, :duracion_dia,
             :salida_luna, :puesta_luna, :fase_lunar, :ciclo_lunar, :visibilidad_lunar)
            RETURNING id, fecha_hora";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':fecha_hora' => now('America/Argentina/Buenos_Aires')->format('Y-m-d H:i:s'),
            ':alba' => $alba,
            ':salida_sol' => $salidaSol,
            ':puesta_sol' => $puestaSol,
            ':anochecer' => $anochecer,
            ':duracion_dia' => $duracionDia,
            ':salida_luna' => $salidaLuna,
            ':puesta_luna' => $puestaLuna,
            ':fase_lunar' => $faseLunar,
            ':ciclo_lunar' => $cicloLunar,
            ':visibilidad_lunar' => $visibilidadLunar,
        ]);

        $row = $stmt->fetch();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Datos astronómicos actualizados correctamente.',
            'id' => $row['id'] ?? null,
            'fecha_hora' => $row['fecha_hora'] ?? null,
            'datos' => [
                'alba' => $alba,
                'salida_sol' => $salidaSol,
                'puesta_sol' => $puestaSol,
                'anochecer' => $anochecer,
                'duracion_dia' => $duracionDia,
                'salida_luna' => $salidaLuna,
                'puesta_luna' => $puestaLuna,
                'fase_lunar' => $faseLunar,
                'ciclo_lunar' => $cicloLunar,
                'visibilidad_lunar' => $visibilidadLunar,
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}

private function traducirFaseLunar(?string $fase): ?string
{
    if (!$fase) {
        return null;
    }

    return match (strtolower(trim($fase))) {
        'new moon' => 'Luna Nueva',
        'waxing crescent' => 'Luna Creciente',
        'first quarter' => 'Cuarto Creciente',
        'waxing gibbous' => 'Luna Gibosa Creciente',
        'full moon' => 'Luna Llena',
        'waning gibbous' => 'Luna Gibosa Menguante',
        'last quarter', 'third quarter' => 'Cuarto Menguante',
        'waning crescent' => 'Luna Menguante',
        default => $fase,
    };
}

private function calcularCicloLunar(?string $fase): ?string
{
    if (!$fase) {
        return null;
    }

    if (str_contains($fase, 'Creciente')) {
        return 'Creciente';
    }

    if (str_contains($fase, 'Menguante')) {
        return 'Menguante';
    }

    if ($fase === 'Luna Nueva') {
        return 'Creciente';
    }

    if ($fase === 'Luna Llena') {
        return 'Menguante';
    }

    return null;
}

private function normalizarIluminacionLunar($valor): ?float
{
    if ($valor === null || $valor === '') {
        return null;
    }

    $valor = str_replace('%', '', (string) $valor);
    $valor = trim($valor);

    return round((float) $valor, 2);
}

private function floatOrNull($value)
{
    return is_null($value) ? null : (float) $value;
}
}
