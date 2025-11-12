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
        Schema::create('carga_academica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')
                ->constrained('docentes')
                ->onDelete('cascade');
            $table->foreignId('materia_id')
                ->constrained('materias')
                ->onDelete('cascade');
            $table->foreignId('grupo_id')
                ->nullable()
                ->constrained('grupos')
                ->onDelete('cascade');

            // Índice único para evitar duplicados
            $table->unique(['docente_id', 'materia_id', 'grupo_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carga_academica');
    }
};
