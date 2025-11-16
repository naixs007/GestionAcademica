<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Docente extends Model
{
    use HasFactory;
    protected $table = 'docentes';

    protected $fillable = [
        'user_id',
        'categoria',
        'profesion',
        'carga_maxima_horas',
    ];

    /**
     * Calcula la carga horaria total del docente en horas decimales
     * Suma la cargaHoraria de todas las materias asignadas
     * Si no hay gestión/periodo específico, usa todas las cargas activas
     */
    public function getCargaHorariaAttribute()
    {
        // Intentar obtener la gestión y periodo más reciente
        $cargaMasReciente = $this->cargasAcademicas()
            ->orderBy('gestion', 'desc')
            ->orderBy('periodo', 'desc')
            ->first();

        if (!$cargaMasReciente) {
            return 0;
        }

        $totalCargaHoraria = $this->cargasAcademicas()
            ->where('gestion', $cargaMasReciente->gestion)
            ->where('periodo', $cargaMasReciente->periodo)
            ->with('materia')
            ->get()
            ->sum(function ($carga) {
                return $carga->materia ? $carga->materia->cargaHoraria : 0;
            });

        return round($totalCargaHoraria, 2);
    }

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

    // Relación con habilitaciones de asistencia
    public function habilitacionesAsistencia()
    {
        return $this->hasMany(HabilitacionAsistencia::class);
    }
}
