<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseMatcherMasiva extends Model
{
    use HasFactory;

    protected $table = 'response_matcher_masiva';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'codigo_resultado',
        'nut',
        'nuip',
        'id_log',
        'id_oaid',
        'id_cliente',
        'resultado_cotejo',
        'primer_nombre',
        'segundo_nombre',
        'codigo_particula',
        'descripcion_particula',
        'primer_apellido',
        'segundo_apellido',
        'lugar_expedicion',
        'fecha_expedicion',
        'codigo_vigencia',
        'descripcion_vigencia',
        'message_error',
        'idunoauno',
        'idmasiva',
    ];
}
