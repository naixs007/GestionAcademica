<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $table = 'asistencias';

    protected $fillable = [
        'estado',
        'fecha',
        'observaciones',
    ];

     //Relacion muchos a 1 con docente
    function docentes()
    {
        return $this->belongsTo(Docente::class);
    }

    //Relacion muchos a 1 con horario
    function horarios()
    {
        return $this->belongsTo(Horario::class);
    }
}
