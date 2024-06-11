<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogMasiva extends Model
{
    use HasFactory;

    protected $table = 'log_masivas';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'fechainicio',
        'fechafin',
        'usuariocarga_id',
        'totalregistros',
        'errortotalregistros',
    ];
}
