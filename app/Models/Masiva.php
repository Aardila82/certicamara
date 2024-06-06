<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masiva extends Model
{
    use HasFactory;

    protected $fillable = [
        'fechainicio',
        'fechafin',
        'usuariocarga_id',
        'totalregistros',
        'errortotalregistros',
    ];
}
