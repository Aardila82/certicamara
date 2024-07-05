<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

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

    public function logFacialEnvivoUnoAUno()
    {
        return $this->hasMany(LogFacialEnvivoUnoAUno::class, 'idusuario');
    }
     // Método para verificar el rol
     public function hasRole($role)
     {
         return $this->rol == $role;
     }

     // Mutator para hash de contraseñas
     public function setContrasenaAttribute($value)
     {
         $this->attributes['contrasena'] = Hash::make($value);
     }
}
