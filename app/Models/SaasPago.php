<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasPago extends Model
{
    protected $fillable = [
        'user_id',
        'plan',
        'monto',
        'estado',
        'payment_id',
        'external_reference',
        'metodo_pago',
        'fecha_pago',
        'checkout_url',
    ];
}
