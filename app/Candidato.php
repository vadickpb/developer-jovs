<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    protected $fillable = [
        'nombre', 'cv', 'email', 'vacante_id'
    ];
}
