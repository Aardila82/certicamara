<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class municipio extends Model
{
    use HasFactory;
    protected $fillable = ['nombre','departamento_id', 'codigodivipola', 'numeromunicipio', 'estado'];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'codigodivipola', 'codigodivipola');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'municipio');
    }
}
