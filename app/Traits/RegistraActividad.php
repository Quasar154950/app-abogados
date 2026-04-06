<?php

namespace App\Traits;

use App\Models\Actividad;
use Illuminate\Support\Facades\Auth;

trait RegistraActividad
{
    protected static function bootRegistraActividad()
    {
        foreach (['created', 'updated', 'deleted'] as $evento) {
            static::$evento(function ($modelo) use ($evento) {
                $datosAntes = null;
                $datosDespues = null;

                if ($evento === 'updated') {
                    $cambios = $modelo->getChanges();
                    
                    foreach ($cambios as $campo => $valor) {
                        $valorViejo = $modelo->getOriginal($campo);
                        
                        // --- TRADUCCIÓN DE NOMBRES DE CAMPOS ---
                        $nombreLindo = match($campo) {
                            'is_pinned' => 'Prioridad',
                            'contenido' => 'Nota',
                            'cliente_id'=> 'Cliente',
                            default     => $campo
                        };

                        if (method_exists($modelo, 'traducirAtributo')) {
                            $datosAntes[$nombreLindo] = $modelo->traducirAtributo($campo, $valorViejo);
                            $datosDespues[$nombreLindo] = $modelo->traducirAtributo($campo, $valor);
                        } else {
                            $datosAntes[$nombreLindo] = $valorViejo;
                            $datosDespues[$nombreLindo] = $valor;
                        }
                    }

                    if (empty($datosDespues)) return;

                } elseif ($evento === 'created') {
                    $datosDespues = $modelo->getAttributes();
                } elseif ($evento === 'deleted') {
                    // --- NUEVO: Guardamos lo que había antes de borrar para no perder la info ---
                    $datosAntes = $modelo->getAttributes();
                }

                Actividad::create([
                    'user_id' => Auth::id(),
                    'accion' => $evento,
                    // Pasamos el $modelo para que busque el nombre humano
                    'descripcion' => self::getDescripcionEvento($evento, $modelo),
                    'logueable_id' => $modelo->id,
                    'logueable_type' => get_class($modelo),
                    'antes' => $datosAntes,
                    'despues' => $datosDespues,
                ]);
            });
        }
    }

    protected static function getDescripcionEvento($evento, $modelo)
    {
        // Buscamos el nombre (ej: Diego) si el modelo tiene la función getLogNombre
        $nombreData = method_exists($modelo, 'getLogNombre') 
            ? " (" . $modelo->getLogNombre() . ")" 
            : "";

        return match ($evento) {
            'created' => 'creó el registro' . $nombreData,
            'updated' => 'actualizó información' . $nombreData,
            'deleted' => 'eliminó el registro' . $nombreData,
            default => $evento . $nombreData,
        };
    }
}