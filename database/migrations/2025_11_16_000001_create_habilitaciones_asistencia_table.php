<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habilitaciones_asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('carga_academica_id')->constrained('carga_academica')->onDelete('cascade');
            $table->date('fecha');
            $table->enum('estado', ['Habilitada', 'Utilizada', 'Expirada', 'Cancelada'])->default('Habilitada');
            $table->text('observaciones')->nullable();
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_utilizacion')->nullable();
            $table->timestamps();

            // Índices para mejorar búsquedas
            $table->index(['docente_id', 'fecha', 'estado']);
            $table->index(['carga_academica_id', 'fecha']);

            // Prevenir duplicados
            $table->unique(['docente_id', 'carga_academica_id', 'fecha'], 'unique_habilitacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habilitaciones_asistencia');
    }
};
