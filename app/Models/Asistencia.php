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
        'materia_id',
        'grupo_id',
        'horario_id',
        'fecha',
        'estado',
        'hora_llegada',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación muchos a 1 con docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    // Relación muchos a 1 con materia
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    // Relación muchos a 1 con grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    // Relación muchos a 1 con horario
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    /**
     * Scope para filtrar por fecha
     */
    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    /**
     * Scope para filtrar por docente
     */
    public function scopeDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Obtener el color del badge según el estado
     */
    public function getEstadoBadgeColorAttribute()
    {
        return match($this->estado) {
            'Presente' => 'success',
            'Ausente' => 'danger',
            'Justificado' => 'warning',
            'Tardanza' => 'info',
            default => 'secondary',
        };
    }
}
