<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $table = 'asistencias';

    protected $fillable = [
        'docente_id',
        'horario_id',
        'estado',
        'fecha',
        'observaciones',
    ];

    // Relación muchos a 1 con docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    // Relación muchos a 1 con horario
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }
}
