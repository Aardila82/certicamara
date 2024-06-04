<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alfa extends Model
{
    use HasFactory;

    protected $table = 'alfas';

    protected $primaryKey = 'pin';

    public $incrementing = false;

    protected $keyType = 'string';


    protected $fillable= [
        'pin',
        'nombre1',
        'nombre2',
        'partícula',
        'apellido1',
        'apellido2',
        'explugar',
        'expfecha',
        'vigencia'
    ];
}
