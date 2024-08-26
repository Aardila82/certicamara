<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atdp extends Model
{
    use HasFactory;

    protected $table = 'atdp';

    protected $fillable = [
        'nut',
        'estado_aprobacion',
        'cedula_aprobacion',
        'enlace_atdp',
    ];
}
