<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosConexion extends Model
{
    protected $table = 'datos_conexion';

    protected $fillable = [
        'so',
        'navegador',
        'ubicacion_geografica',
        'ipv4',
        'identificador_unico_dispositivo',
        'fecha',
    ];

    public $timestamps = true;
}
