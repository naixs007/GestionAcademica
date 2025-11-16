<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HabilitacionAsistencia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'habilitaciones_asistencia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'docente_id',
        'carga_academica_id',
        'fecha',
        'estado',
        'observaciones',
        'creado_por',
        'fecha_utilizacion',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
        'fecha_utilizacion' => 'datetime',
    ];

    /**
     * Relación con Docente
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    /**
     * Relación con CargaAcademica
     */
    public function cargaAcademica(): BelongsTo
    {
        return $this->belongsTo(CargaAcademica::class);
    }

    /**
     * Relación con el usuario que creó la habilitación
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /**
     * Verificar si la habilitación está activa y disponible
     */
    public function estaDisponible(): bool
    {
        return $this->estado === 'Habilitada'
            && $this->fecha->isToday();
    }

    /**
     * Marcar como utilizada
     */
    public function marcarComoUtilizada(): void
    {
        $this->update([
            'estado' => 'Utilizada',
            'fecha_utilizacion' => now(),
        ]);
    }

    /**
     * Scope para obtener habilitaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'Habilitada');
    }

    /**
     * Scope para obtener habilitaciones de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    /**
     * Scope para un docente específico
     */
    public function scopeParaDocente($query, $docenteId)
    {
        return $query->where('docente_id', $docenteId);
    }
}
