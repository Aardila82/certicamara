<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAlfa extends Model
{
    use HasFactory;
    protected $table = 'log_alfas';

    protected $fillable = [
        'id',
        'nombre_archivo',
        'usuario_inicio',
        'fecha_inicio_transaccion',
        'fecha_final',
    ];
}
