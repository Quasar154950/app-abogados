<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Actividad extends Model
{
    // Nombre de la tabla
    protected $table = 'actividades';

    // Campos que permitimos llenar
    protected $fillable = [
        'user_id', 
        'descripcion', 
        'accion', 
        'logueable_type', 
        'logueable_id', 
        'antes', 
        'despues'
    ];

    // Esto convierte automáticamente el JSON de la DB en un array de PHP
    protected $casts = [
        'antes' => 'array',
        'despues' => 'array',
    ];

    /**
     * Relación: Quién hizo el cambio (Usuario)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación Polimórfica: A qué modelo pertenece este log (Cliente, Seguimiento, etc.)
     */
    public function logueable(): MorphTo
    {
        return $this->morphTo();
    }
}
