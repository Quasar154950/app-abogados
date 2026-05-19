<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaTurno extends Model
{
    protected $table = 'reserva_turnos';

    protected $fillable = [
        'cliente_id',
        'turno_id',
        'estado',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
}
