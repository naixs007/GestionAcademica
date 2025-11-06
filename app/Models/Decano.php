<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decano extends Model
{
    use HasFactory;
    protected $table = 'decano';
    protected $fillable = [
        'user_id',
        'fechaIniciogestion',
        'fechaFingestion',
    ];

}
