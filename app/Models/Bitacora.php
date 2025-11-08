<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'usuario',
        'descripcion',
        'metodo',
        'ruta',
        'direccion_ip',
        'navegador',
        'browser_info',
        'fecha_hora',
    ];

}
