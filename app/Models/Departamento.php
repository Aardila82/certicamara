<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable=[

        'codigodivipola',
        'nombre',
        'estado'


    ];

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'codigodivipola', 'codigodivipola');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'departamento');
    }
}
