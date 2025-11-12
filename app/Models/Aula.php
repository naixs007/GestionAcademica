<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;
    protected $table = 'aulas';
    protected $fillable = [
        'nombre',
        'capacidad',
        'tipo',
    ];

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'materia_aula', 'aula_id', 'materia_id');
    }
}
