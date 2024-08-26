<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogFacialEnvivoUnoAUno extends Model
{
    use HasFactory;

    protected $table = 'log_facial_envivo_uno_a_uno';
    protected $primaryKey = 'id';

    protected $fillable= [
        'id',
        'nut',
        'nuip',
        'resultado',
        'fechafin',
        'idusuario',
        'hash256',
        'idmasiva',
        'response',
        'atdpruta',
        'aprobacion_atdp',
        'fechainicio',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario');
    }
}
