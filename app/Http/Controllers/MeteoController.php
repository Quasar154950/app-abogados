<?php

namespace App\Http\Controllers;

use PDO;
use PDOException;

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

private function floatOrNull($value)
{
    return is_null($value) ? null : (float) $value;
}
}
