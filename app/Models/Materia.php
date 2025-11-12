<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;
    protected $table = 'materias';
    protected $fillable = [
        'nombre',
        'codigo',
        'cargaHoraria',
        'nivel',
    ];

    // Relación muchos a muchos con Docentes a través de carga_academica
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'carga_academica', 'materia_id', 'docente_id')
            ->withTimestamps();
    }

    // Relación muchos a muchos con Grupos a través de carga_academica
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'carga_academica', 'materia_id', 'grupo_id')
            ->withTimestamps();
    }

    // Relación con asignaciones de carga académica
    public function cargasAcademicas()
    {
        return $this->hasMany(CargaAcademica::class);
    }

    // Relación muchos a muchos con Aulas
    public function aulas()
    {
        return $this->belongsToMany(Aula::class, 'materia_aula', 'materia_id', 'aula_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }
}
