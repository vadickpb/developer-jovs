<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    protected $fillable = [
        
        'titulo', 'imagen', 'descripcion', 'skills', 'activa', 'categoria_id', 'experiencia_id', 'ubicacion_id', 'salario_id'
    ];


    //Relacion de 1 a 1 categoria y vacante
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    //Relacion de 1 a 1 de salario y vacante
    public function salario()
    {
        return $this->belongsTo(Salario::class);
    }

    //Relacion de 1 a 1 de salario y vacante
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    //Relacion de 1 a 1 de salario y vacante
    public function experiencia()
    {
        return $this->belongsTo(Experiencia::class);
    }

    //Relacion de 1 a 1 de reclutador y vacante
    public function reclutador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
