<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expediente extends Model
{
    protected $fillable = [
        'cliente_id',
        'numero_expediente',
        'juzgado',
        'caratula',
        'tipo',
        'estado',
        'fecha_inicio',
        'observaciones',
    ];

    // 🔗 Relación con cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    // 🔗 Relación con tareas (seguimientos)
    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }
}
