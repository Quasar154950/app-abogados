<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'cliente_id',
        'fecha',
        'hora_ingreso',
        'hora_salida',
        'presente',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_ingreso' => 'datetime',
        'hora_salida' => 'datetime',
        'presente' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
