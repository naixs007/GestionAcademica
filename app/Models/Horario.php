<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $table = 'horarios';
    protected $fillable = [
        'diaSemana',
        'horaInicio',
        'horaFin',
        'materia_id',
        'modalidad',
    ];

    public function materias()
    {
        return $this->belongsTo(Materia::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
