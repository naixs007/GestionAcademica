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
        // Solo si la columna existe intentamos tocarla
        if (Schema::hasColumn('materias', 'docente_id')) {

            // Por si acaso existe una FK con ese nombre en Postgres
            DB::statement('ALTER TABLE materias DROP CONSTRAINT IF EXISTS materias_docente_id_foreign');

            Schema::table('materias', function (Blueprint $table) {
                $table->dropColumn('docente_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Solo la volvemos a crear si no existe
        if (!Schema::hasColumn('materias', 'docente_id')) {
            Schema::table('materias', function (Blueprint $table) {
                $table->foreignId('docente_id')
                    ->nullable()
                    ->constrained('docentes')
                    ->onDelete('set null');
            });
        }
    }
};
