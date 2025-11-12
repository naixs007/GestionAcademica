<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    protected $table = 'docentes';

    protected $fillable = [
        'user_id',
        'cargaHoraria',
        'categoria',
        'profesion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    // Relación muchos a muchos con Materias a través de carga_academica
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'carga_academica', 'docente_id', 'materia_id')
            ->withTimestamps();
    }

    // Relación muchos a muchos con Grupos a través de carga_academica
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'carga_academica', 'docente_id', 'grupo_id')
            ->withTimestamps();
    }

    // Relación con asignaciones de carga académica
    public function cargasAcademicas()
    {
        return $this->hasMany(CargaAcademica::class);
    }
}
