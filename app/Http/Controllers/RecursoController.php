<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RecursoController extends Controller
{
//Obtener recursos
    public function index()
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('read')) {
            return response()->json(['message' => 'No tienes permiso para ver los recursos'], 401);
        }
    
        $recursos = Recurso::all();
    
        return response()->json([
            'message' => 'Lista de recursos',
            'recursos' => $recursos
        ], 200);
    }

//Crear recurso
    public function store(Request $request)
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('create')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'preu' => 'required|numeric',
            'descripcio' => 'required|string',
        ]);
        if (Recurso::create($validatedData)) {
            return response()->json(["Resposta"=> "Recurs creat correctament", 'recurso' => $validatedData],201);
        } else {
            return response()->json(["Resposta"=> "Error en la creació"],405);
        }
    }

//Obtenir recurso
    public function show($id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('read')) {
            return response()->json(['message' => 'No tienes permiso para ver el recurso'], 401);
        }
    
        $recurso = Recurso::find($id);
    
        if (!$recurso) {
            return response()->json(['message' => 'Recurso no encontrado'], 404);
        }
    
        return response()->json([
            'message' => 'Recurso encontrado',
            'recurso' => $recurso
        ], 200);
    }


//Actualitzar recurso
    public function update(Request $request, $id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('update')) {
            return response()->json(['message' => 'No tienes permiso para actualizar el recurso'], 401);
        }
    
        $recurso = Recurso::find($id);
    
        if (!$recurso) {
            return response()->json(['message' => 'Recurso no encontrado'], 404);
        }
    
        $validatedData = $request->validate([
            'nom' => 'string',
            'preu' => 'numeric',
            'descripcio' => 'string',
        ]);
    
        if ($request->has('nom')) {
            $recurso->nom = $validatedData['nom'];
        }
    
        if ($request->has('preu')) {
            $recurso->preu = $validatedData['preu'];
        }
    
        if ($request->has('descripcio')) {
            $recurso->descripcio = $validatedData['descripcio'];
        }
    
        if ($recurso->save()) {
            return response()->json(['message' => 'Recurso actualizado', 'recurso' => $recurso], 200);
        } else {
            return response()->json(['message' => 'Error al actualizar el recurso'], 500);
        }
    }

    
//Eliminar recurso
    public function destroy(Request $request, $id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('delete')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }
        $recurso = recurso::find($id);
        if (!$recurso) {
            return response()->json(['message' => 'El recurs no existeix'], 404);
        }
        if ($recurso->destroy($id)) {
            return response()->json(['message' => 'Recurs eliminat'], 200);
        } else {
            return response()->json(['message' => 'Error al eliminar el recurs'], 405);
        }
    }
}
