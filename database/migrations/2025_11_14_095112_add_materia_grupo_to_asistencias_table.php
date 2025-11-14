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
        Schema::table('asistencias', function (Blueprint $table) {
            // Agregar columnas materia_id y grupo_id después de docente_id
            $table->foreignId('materia_id')->nullable()->after('docente_id')
                ->constrained('materias')->onDelete('cascade');

            $table->foreignId('grupo_id')->nullable()->after('materia_id')
                ->constrained('grupos')->onDelete('cascade');

            // Agregar columna hora_llegada para registrar tardanzas
            $table->time('hora_llegada')->nullable()->after('estado');

            // Modificar columna estado para usar valores descriptivos
            $table->string('estado', 20)->change();

            // Modificar observaciones para que sea nullable
            $table->text('observaciones')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropForeign(['materia_id']);
            $table->dropColumn('materia_id');

            $table->dropForeign(['grupo_id']);
            $table->dropColumn('grupo_id');

            $table->dropColumn('hora_llegada');
        });
    }
};
