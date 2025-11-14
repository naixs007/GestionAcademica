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
        'categoria',
        'profesion',
    ];

    /**
     * Calcula la carga horaria total del docente
     * Suma las horas de todos los horarios asignados en el periodo actual
     */
    public function getCargaHorariaAttribute()
    {
        $gestionActual = date('Y');
        $periodoActual = date('n') <= 6 ? '1' : '2'; // 1 = enero-junio, 2 = julio-diciembre

        return $this->cargasAcademicas()
            ->where('gestion', $gestionActual)
            ->where('periodo', $periodoActual)
            ->with('horario')
            ->get()
            ->sum(function ($carga) {
                if (!$carga->horario) return 0;

                $inicio = \Carbon\Carbon::parse($carga->horario->hora_inicio);
                $fin = \Carbon\Carbon::parse($carga->horario->hora_fin);

                return $fin->diffInHours($inicio);
            });
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
}
