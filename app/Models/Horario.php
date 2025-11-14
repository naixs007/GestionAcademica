<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $table = 'horarios';
    protected $fillable = [
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    /**
     * Formato legible del bloque horario
     */
    public function getDescripcionAttribute()
    {
        return "{$this->dia_semana} {$this->hora_inicio} - {$this->hora_fin}";
    }

    /**
     * Calcula las horas del bloque (retorna decimal)
     */
    public function getHorasAttribute()
    {
        $inicio = \Carbon\Carbon::parse($this->hora_inicio);
        $fin = \Carbon\Carbon::parse($this->hora_fin);

        return round($fin->diffInMinutes($inicio) / 60, 2);
    }

    /**
     * Retorna duración en formato legible (ej: "1h 30min")
     */
    public function getDuracionFormateadaAttribute()
    {
        // Asegurar que las horas estén en formato correcto
        $horaInicio = $this->hora_inicio;
        $horaFin = $this->hora_fin;

        // Si tiene formato HH:MM:SS, extraer solo HH:MM
        if (strlen($horaInicio) > 5) {
            $horaInicio = substr($horaInicio, 0, 5);
        }
        if (strlen($horaFin) > 5) {
            $horaFin = substr($horaFin, 0, 5);
        }

        try {
            $inicio = \Carbon\Carbon::createFromFormat('H:i', $horaInicio);
            $fin = \Carbon\Carbon::createFromFormat('H:i', $horaFin);

            // Si la hora fin es menor que inicio, asumir que cruza medianoche
            if ($fin->lessThan($inicio)) {
                $fin->addDay();
            }

            $totalMinutos = $inicio->diffInMinutes($fin);
            $horas = floor($totalMinutos / 60);
            $minutos = $totalMinutos % 60;

            $resultado = '';
            if ($horas > 0) {
                $resultado .= $horas . 'h';
            }
            if ($minutos > 0) {
                $resultado .= ($horas > 0 ? ' ' : '') . $minutos . 'min';
            }

            return $resultado ?: '0min';
        } catch (\Exception $e) {
            return '0min';
        }
    }

    // Relación con asignaciones de carga académica
    public function cargasAcademicas()
    {
        return $this->hasMany(CargaAcademica::class);
    }

    // Relaciones indirectas
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'carga_academica', 'horario_id', 'materia_id')
            ->withTimestamps();
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'carga_academica', 'horario_id', 'docente_id')
            ->withTimestamps();
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}
