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
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function abogados(): HasMany
    {
        return $this->hasMany(User::class, 'estudio_id');
    }
}
