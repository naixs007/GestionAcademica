<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;
    protected $table = 'aulas';
    protected $fillable = [
        'codigo',
        'capacidad',
        'tipo',
    ];

    // Relación con asignaciones de carga académica
    public function cargasAcademicas()
    {
        return $this->hasMany(CargaAcademica::class);
    }

    // Relación indirecta con materias a través de carga_academica
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'carga_academica', 'aula_id', 'materia_id')
            ->withTimestamps();
    }
}
