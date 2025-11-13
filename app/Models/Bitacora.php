<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'fecha_hora',
    ];

    /**
     * Relación al usuario que generó la entrada de la bitácora.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
