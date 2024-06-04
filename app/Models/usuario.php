<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    use HasFactory;
    protected $fillable=[

        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'numerodedocumento',
        'email',
        'telefono',
        'departamento',
        'municipio',
        'usuario',
        'contrasena',
        'estado',
        'rol'


    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio');
    }
}
