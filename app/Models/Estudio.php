<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudio extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'logo',
        'activo',
        'fecha_vencimiento',
        'plan',
        'precio_suscripcion',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'fecha_vencimiento' => 'datetime',
            'precio_suscripcion' => 'decimal:2',
        ];
    }

    public function abogados(): HasMany
    {
        return $this->hasMany(User::class, 'estudio_id');
    }
}
