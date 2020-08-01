<?php

namespace App\Http\Controllers;

use App\User;
use App\Salario;
use App\Vacante;
use App\Categoria;
use App\Ubicacion;
use App\Experiencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VacanteController extends Controller
{


    public function __construct()
    {
        //Verificar que el usuario este autenticado y verificado

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* $vacantes = auth()->user()->vacantes; */
        $vacantes = Vacante::where('user_id', auth()->user()->id)->simplePaginate(6);

        return view('vacantes.index', compact('vacantes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Consultas
        $categorias = Categoria::all();
        $experiencias = Experiencia::all();
        $ubicacions = Ubicacion::all();
        $salarios = Salario::all();

        return view('vacantes.create')
            ->with([
                'categorias' => $categorias,
                'experiencias' => $experiencias,
                'ubicacions' => $ubicacions,
                'salarios' => $salarios

            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validar los datos
        $data = $request->validate([
            'titulo' => 'required|min:8',
            'categoria' => 'required',
            'experiencia' => 'required',
            'ubicacion' => 'required',
            'salario' => 'required',
            'descripcion' => 'required|min:20',
            'imagen' => 'required',
            'skills' => 'required'
        ]);

        //Almacebar en la bd
        auth()->user()->vacantes()->create([
            'titulo' => $data['titulo'],
            'imagen' => $data['imagen'],
            'descripcion' => $data['descripcion'],
            'skills' => $data['skills'],
            'categoria_id' => $data['categoria'],
            'experiencia_id' => $data['experiencia'],
            'ubicacion_id' => $data['ubicacion'],
            'salario_id' => $data['salario']

        ]);

        return redirect()->action('VacanteController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vacante  $vacante
     * @return \Illuminate\Http\Response
     */
    public function show(Vacante $vacante)
    {
        //
        return view('vacantes.show')->with('vacante', $vacante);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vacante  $vacante
     * @return \Illuminate\Http\Response
     */
    public function edit(Vacante $vacante)
    {
        $this->authorize('view', $vacante);

        //Consultas
        $categorias = Categoria::all();
        $experiencias = Experiencia::all();
        $ubicacions = Ubicacion::all();
        $salarios = Salario::all();
        return view('vacantes.edit', compact('vacante', 'categorias', 'experiencias', 'ubicacions', 'salarios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vacante  $vacante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacante $vacante)
    {
        $this->authorize('update', $vacante);

        //validar los datos
        $data = $request->validate([
            'titulo' => 'required|min:8',
            'categoria' => 'required',
            'experiencia' => 'required',
            'ubicacion' => 'required',
            'salario' => 'required',
            'descripcion' => 'required|min:20',
            'imagen' => 'required',
            'skills' => 'required'
        ]);

        $vacante->titulo = $data['titulo'];
        $vacante->skills = $data['skills'];
        $vacante->imagen = $data['imagen'];
        $vacante->descripcion = $data['descripcion'];
        $vacante->categoria_id = $data['categoria'];
        $vacante->experiencia_id = $data['experiencia'];
        $vacante->ubicacion_id = $data['ubicacion'];
        $vacante->salario_id = $data['salario'];

        $vacante->save();

        return redirect()->action('VacanteController@index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vacante  $vacante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacante $vacante)
    {
        //
        $this->authorize('delete', $vacante);

        /* return response()->json($vacante); */
        $vacante->delete();

        return response()->json(['mensaje' => 'Se elimino la vacante'.$vacante->titulo]);
    }

    public function imagen(Request $request)
    {
        $imagen = $request->file('file');
        $nombreImagen = time() . '.' . $imagen->extension();
        $imagen->move(public_path('storage/vacantes'), $nombreImagen);

        return response()->json(['correcto' => $nombreImagen]);
    }

    public function borrarimagen(Request $request)
    {
        if($request->ajax()){

            $imagen = $request->get('imagen');

            if( File::exists('storage/vacantes/'. $imagen)){
                File::delete('storage/vacantes/'. $imagen);
            }
        }

        return response('Imagen Eliminada', 200);

    }

    //cambiar estado de la vacante
    public function estado(Request $request, Vacante $vacante)
    {
        //leer el nuevo estado y asignarlo
        $vacante->activa = $request->estado;

        //guardarlo a la base de datos
        $vacante->save();

        return response()->json(['respuesta' => 'Correcto']);
    }
}
