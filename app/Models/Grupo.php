<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $table = 'grupos';

    protected $fillable = [
        'nombre',
        'cupo_maximo',
    ];

    // Relación muchos a muchos con Materias a través de carga_academica
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'carga_academica', 'grupo_id', 'materia_id')
            ->withTimestamps();
    }

    // Relación muchos a muchos con Docentes a través de carga_academica
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'carga_academica', 'grupo_id', 'docente_id')
            ->withTimestamps();
    }

    // Relación con asignaciones de carga académica
    public function cargasAcademicas()
    {
        return $this->hasMany(CargaAcademica::class);
    }
}
