<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeCliente extends Model
{
    protected $table = 'mensajes_cliente';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'mensaje',
        'remitente',
        'leido',
        'leido_at',
    ];
    
    protected $casts = [
    'leido' => 'boolean',
    'leido_at' => 'datetime',
   ];
    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
