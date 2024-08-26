<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogLiveness extends Model
{
    use HasFactory;

    protected $table = 'log_liveness';

    protected $fillable= [
        'id',
        'nut',
        'nuip',
        'fecha',

        'clase',
        'resultadoLiveness'
    ];
}