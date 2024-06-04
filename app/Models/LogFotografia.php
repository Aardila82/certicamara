<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogFotografia extends Model
{
    use HasFactory;
    protected $table = 'log_fotografia';

    protected $fillable= [
        'id',
        'fnut',
        'nuip',
        'peso_real',
        'hash',
        'fotografia',
    ];
}
