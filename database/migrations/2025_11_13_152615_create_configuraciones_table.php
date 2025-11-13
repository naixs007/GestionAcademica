<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();

            // Información institucional
            $table->string('nombre_institucion')->nullable();
            $table->string('logo_institucional_path')->nullable();

            // Configuración académica
            // TODO: Descomentar cuando se implemente la tabla periodos_academicos
            // $table->foreignId('periodo_academico_default_id')->nullable()->constrained('periodos_academicos')->nullOnDelete();
            $table->unsignedBigInteger('periodo_academico_default_id')->nullable();

            // Configuración de asistencia
            $table->integer('tolerancia_asistencia_minutos')->default(10);
            $table->boolean('requerir_motivo_ausencia')->default(false);

            // Configuración de seguridad
            $table->integer('expiracion_contrasena_dias')->default(90);

            // Configuración de notificaciones
            $table->string('notificaciones_email_remitente')->nullable();

            $table->timestamps();
        });

        // Insertar configuración por defecto (singleton)
        DB::table('configuraciones')->insert([
            'nombre_institucion' => 'Sistema de Gestión Académica',
            'tolerancia_asistencia_minutos' => 10,
            'requerir_motivo_ausencia' => false,
            'expiracion_contrasena_dias' => 90,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
