<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configuraciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_institucion',
        'logo_institucional_path',
        'periodo_academico_default_id',
        'tolerancia_asistencia_minutos',
        'requerir_motivo_ausencia',
        'expiracion_contrasena_dias',
        'notificaciones_email_remitente',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requerir_motivo_ausencia' => 'boolean',
        'tolerancia_asistencia_minutos' => 'integer',
        'expiracion_contrasena_dias' => 'integer',
    ];

    /**
     * Obtener la configuración actual del sistema (singleton).
     * Siempre retorna la primera fila o crea una nueva si no existe.
     *
     * @return self
     */
    public static function current(): self
    {
        $config = self::first();

        if (!$config) {
            $config = self::create([
                'nombre_institucion' => 'Sistema de Gestión Académica',
                'tolerancia_asistencia_minutos' => 10,
                'requerir_motivo_ausencia' => false,
                'expiracion_contrasena_dias' => 90,
            ]);
        }

        return $config;
    }

    /**
     * Relación con PeriodoAcademico (cuando se implemente).
     */
    // public function periodoAcademicoDefault()
    // {
    //     return $this->belongsTo(PeriodoAcademico::class, 'periodo_academico_default_id');
    // }
}
