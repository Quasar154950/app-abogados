<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    protected $fillable = ['nombre', 'color'];

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class);
    }
}
