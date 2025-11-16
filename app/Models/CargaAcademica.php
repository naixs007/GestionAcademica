<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaAcademica extends Model
{
    use HasFactory;

    protected $table = 'carga_academica';

    protected $fillable = [
        'docente_id',
        'materia_id',
        'grupo_id',
        'horario_id',
        'aula_id',
        'gestion',
        'periodo',
    ];

    // Relaciones
    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    /**
     * Relación con asistencias basada en múltiples columnas
     * Como Asistencia no tiene carga_academica_id, usamos una query personalizada
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'docente_id', 'docente_id')
            ->where('materia_id', $this->materia_id)
            ->where('grupo_id', $this->grupo_id);
    }

    /**
     * Scope para filtrar por gestión y periodo
     */
    public function scopeGestionPeriodo($query, $gestion, $periodo)
    {
        return $query->where('gestion', $gestion)->where('periodo', $periodo);
    }

    /**
     * Scope para filtrar por la gestión y periodo actual
     */
    public function scopeActual($query)
    {
        $gestionActual = date('Y');
        $periodoActual = date('n') <= 6 ? '1' : '2';

        return $query->gestionPeriodo($gestionActual, $periodoActual);
    }
}
