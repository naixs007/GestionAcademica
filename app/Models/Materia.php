<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;
    protected $table = 'materias';
    protected $fillable = [
        'nombre',
        'codigo',
        'cargaHoraria',
        'docente_id',
        'nivel',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}
