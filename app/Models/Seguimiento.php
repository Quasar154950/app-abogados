<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\RegistraActividad;

// Spatie
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Seguimiento extends Model implements HasMedia
{
    use RegistraActividad, InteractsWithMedia;

    protected $fillable = [
        'cliente_id',
        'expediente_id',
        'etiqueta_id',
        'descripcion',
        'estado',
        'fecha_recordatorio',
        'prioridad',
    ];

    /**
     * CONFIGURACIÓN DE MEDIOS (Miniaturas)
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->queued();
    }

    /**
     * Identificador humano para el historial
     */
    public function getLogNombre()
    {
        return $this->cliente->nombre ?? 'Sin Cliente';
    }

    /**
     * Traductor de valores para el historial
     */
    public function traducirAtributo($campo, $valor)
    {
        if ($campo === 'estado') {
            return $valor === 'resuelto' ? '✅ Resuelto' : '⏳ Pendiente';
        }

        if ($campo === 'fecha_recordatorio' && $valor) {
            return \Carbon\Carbon::parse($valor)->format('d/m/Y');
        }

        return $valor;
    }

    protected $casts = [
        'fecha_recordatorio' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }

    public function etiqueta()
    {
        return $this->belongsTo(Etiqueta::class);
    }

    public function getEtiquetaVencimientoAttribute()
    {
        if (!$this->fecha_recordatorio || $this->estado === 'resuelto') {
            return null;
        }

        $f = $this->fecha_recordatorio->startOfDay();
        $hoy = Carbon::today();

        if ($f->lt($hoy)) return 'Vencido';
        if ($f->isSameDay($hoy)) return 'Hoy';

        return 'Próximo';
    }

    public function getColorVencimientoAttribute()
    {
        $tipo = $this->etiqueta_vencimiento;

        return match($tipo) {
            'Vencido' => 'bg-red-600',
            'Hoy'     => 'bg-orange-500',
            'Próximo' => 'bg-blue-500',
            default   => '',
        };
    }

    public function scopeFiltrar($query, $filtros)
    {
        return $query

            // 🔥 Cliente (FIX)
            ->when(!empty($filtros['cliente_id']), function ($q) use ($filtros) {
                $q->where('cliente_id', $filtros['cliente_id']);
            })

            // 🔥 Expediente (FIX)
            ->when(!empty($filtros['expediente_id']), function ($q) use ($filtros) {
                $q->where('expediente_id', $filtros['expediente_id']);
            })

            ->when(!empty($filtros['estado']), function ($q) use ($filtros) {
                $q->where('estado', $filtros['estado']);
            })

            ->when(!empty($filtros['etiqueta_id']), function ($q) use ($filtros) {
                $q->where('etiqueta_id', $filtros['etiqueta_id']);
            })

            ->when(!empty($filtros['prioridad']), function ($q) use ($filtros) {
                $q->where('prioridad', $filtros['prioridad']);
            })

            ->when(!empty($filtros['vencimiento']), function ($q) use ($filtros) {
                if ($filtros['vencimiento'] === 'vencido') {
                    $q->where('fecha_recordatorio', '<', now()->startOfDay())
                      ->where('estado', '!=', 'resuelto');
                } elseif ($filtros['vencimiento'] === 'hoy') {
                    $q->whereDate('fecha_recordatorio', now()->today());
                }
            });
    }
}