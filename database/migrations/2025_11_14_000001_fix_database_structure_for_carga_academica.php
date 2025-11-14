<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * IMPORTANTE: Ejecutar con datos limpios o hacer backup antes
     */
    public function up(): void
    {
        // 1. Eliminar cargaHoraria de docentes (será calculado dinámicamente)
        Schema::table('docentes', function (Blueprint $table) {
            if (Schema::hasColumn('docentes', 'cargaHoraria')) {
                $table->dropColumn('cargaHoraria');
            }
        });

        // 2. Añadir sigla a materias (separado de codigo)
        if (!Schema::hasColumn('materias', 'sigla')) {
            Schema::table('materias', function (Blueprint $table) {
                $table->string('sigla', 20)->after('codigo')->nullable();
            });

            // Copiar códigos a siglas (fuera del Schema closure)
            DB::statement('UPDATE materias SET sigla = codigo');

            // Hacer sigla obligatoria
            Schema::table('materias', function (Blueprint $table) {
                $table->string('sigla', 20)->nullable(false)->change();
            });
        }

        // 3. Modificar grupos: separar de materia y añadir cupo_maximo
        Schema::table('grupos', function (Blueprint $table) {
            // Eliminar relación con materia (grupos son genéricos)
            if (Schema::hasColumn('grupos', 'materia_id')) {
                $table->dropForeign(['materia_id']);
                $table->dropColumn('materia_id');
            }

            // Renombrar capacidad a cupo_maximo
            if (Schema::hasColumn('grupos', 'capacidad')) {
                $table->renameColumn('capacidad', 'cupo_maximo');
            }
        });

        // 4. Modificar aulas: renombrar nombre a codigo
        Schema::table('aulas', function (Blueprint $table) {
            if (Schema::hasColumn('aulas', 'nombre')) {
                $table->renameColumn('nombre', 'codigo');
            }
        });

        // 5. Recrear tabla horarios con estructura correcta
        // Primero eliminar la restricción de asistencias si existe
        Schema::table('asistencias', function (Blueprint $table) {
            if (Schema::hasColumn('asistencias', 'horario_id')) {
                $table->dropForeign(['horario_id']);
            }
        });

        Schema::dropIfExists('horarios');

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->enum('dia_semana', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            // Índice único para evitar bloques duplicados
            $table->unique(['dia_semana', 'hora_inicio', 'hora_fin']);
        });

        // Restaurar la relación de asistencias con horarios
        if (Schema::hasColumn('asistencias', 'horario_id')) {
            Schema::table('asistencias', function (Blueprint $table) {
                $table->foreign('horario_id')->references('id')->on('horarios')->onDelete('cascade');
            });
        }

        // 6. Recrear carga_academica correctamente
        Schema::dropIfExists('carga_academica');

        Schema::create('carga_academica', function (Blueprint $table) {
            $table->id();

            // Relaciones principales
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->foreignId('aula_id')->constrained('aulas')->onDelete('cascade');

            // Gestión académica
            $table->integer('gestion'); // Año (ej: 2025)
            $table->enum('periodo', ['1', '2']); // Semestre

            // Validación: evitar que un docente tenga dos materias al mismo tiempo
            // evitar que un aula tenga dos materias al mismo tiempo
            // evitar duplicados exactos
            $table->unique(['docente_id', 'horario_id', 'gestion', 'periodo'], 'docente_horario_unique');
            $table->unique(['aula_id', 'horario_id', 'gestion', 'periodo'], 'aula_horario_unique');
            $table->unique(['grupo_id', 'horario_id', 'gestion', 'periodo'], 'grupo_horario_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir es complicado, mejor hacer backup antes de ejecutar
        Schema::dropIfExists('carga_academica');
        Schema::dropIfExists('horarios');

        // Recrear horarios antiguo
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->json('diaSemana');
            $table->time('horaInicio');
            $table->time('horaFin');
            $table->string('modalidad');
            $table->timestamps();
        });

        // Recrear carga_academica antiguo
        Schema::create('carga_academica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('grupo_id')->nullable()->constrained('grupos')->onDelete('cascade');
            $table->unique(['docente_id', 'materia_id', 'grupo_id']);
            $table->timestamps();
        });
    }
};
