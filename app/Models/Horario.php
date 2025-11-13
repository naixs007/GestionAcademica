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

    protected $casts = [
        'diaSemana' => 'array',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
