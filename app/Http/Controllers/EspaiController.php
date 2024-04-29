<?php

namespace App\Http\Controllers;

use App\Models\Espai;
use App\Models\Recurso;
use App\Models\Espai_Recurso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EspaiController extends Controller
{
//Obtener espai
    public function index()
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('read')) {
            return response()->json(['message' => 'No tienes permiso para ver los espais'], 401);
        }

        $espai = Espai::all();

        return response()->json([
            'message' => 'Lista de espacios',
            'espais' => $espai
        ], 200);
    }

//Crear espai
    public function store(Request $request)
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('create')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'preu' => 'required|numeric',
            'descripcio' => 'required|string',
            'capacitat' => 'required|numeric',
        ]);
        
        if (Espai::create($validatedData)) {
            return response()->json(["Resposta"=> "Recurs creat correctament", 'espai' => $validatedData],201);
        } else {
            return response()->json(["Resposta"=> "Error en la creació"],405);
        }
    }

//Obtenir espai
    public function show($id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('read')) {
            return response()->json(['message' => 'No tienes permiso para ver el espai'], 401);
        }

        $espai = Espai::find($id);

        if (!$espai) {
            return response()->json(['message' => 'Espai no encontrado'], 404);
        }

        return response()->json([
            'message' => 'Espai encontrado',
            'espai' => $espai
        ], 200);
    }


//Crear espai_recurso
    public function createEspaiRecurso(Request $request)
    {

        if (!Auth::guard('sanctum')->user()->tokenCan('create')) {
            return response()->json(['message' => 'No tienes permiso para crear relaciones'], 401);
        }

        $validatedData = $request->validate([
            'espai_id' => 'required|exists:espais,id',
            'recurso_id' => 'required|exists:recursos,id',
        ]);
        
        $espai = Espai::findOrFail($validatedData['espai_id']);
        $recurso = Recurso::findOrFail($validatedData['recurso_id']);

        $espai_recurso = Espai_Recurso::create([
            'espai_id' => $espai->id,
            'recurso_id' => $recurso->id,
        ]);

        return response()->json(['message' => 'Relación creada correctamente'], 201);
    }


//Actualitzar espai
    public function update(Request $request, $id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('update')) {
            return response()->json(['message' => 'No tienes permiso para actualizar el espai'], 401);
        }

        $espai = Espai::find($id);

        if (!$espai) {
            return response()->json(['message' => 'Espai no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nom' => 'string',
            'preu' => 'numeric',
            'descripcio' => 'string',
            'capacitat' => 'numeric',
        ]);

        if ($request->has('nom')) {
            $espai->nom = $validatedData['nom'];
        }

        if ($request->has('preu')) {
            $espai->preu = $validatedData['preu'];
        }

        if ($request->has('descripcio')) {
            $espai->descripcio = $validatedData['descripcio'];
        }

        if ($request->has('capacitat')) {
            $espai->capacitat = $validatedData['capacitat'];
        }

        if ($espai->save()) {
            return response()->json(['message' => 'Espai actualizado', 'espai' => $espai], 200);
        } else {
            return response()->json(['message' => 'Error al actualizar el espai'], 500);
        }
    }


//Eliminar espai
    public function destroy(Request $request, $id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('delete')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }
        $espai = espai::find($id);
        if (!$espai) {
            return response()->json(['message' => 'El espai no existeix'], 404);
        }
        if ($espai->destroy($id)) {
            return response()->json(['message' => 'Espai eliminat'], 200);
        } else {
            return response()->json(['message' => 'Error al eliminar el espai'], 405);
        }
    }

    //Obtener capacidad de un espacio por ID
    public function obtenerCapacidadEspacio($espaiId)
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('update')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }
        
        $espai = Espai::find($espaiId);
        
        if (!$espai) {
            return response()->json(['message' => 'L\'espai no existeix'], 404);
        }
        
        $capacitat = $espai->capacitat;
        
        return response()->json(['capacidad' => $capacitat], 200);
    }


      
    public function getRecursosVinculadosEspacio($id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('read')) {
            return response()->json(['message' => 'No tienes permiso para ver los recursos vinculados'], 401);
        }

        $espaiRecursos = Espai_Recurso::where('espai_id', $id)->get();
        $recursos = [];

        foreach ($espaiRecursos as $espaiRecurso) {
            $recursos[] = $espaiRecurso->recurso;
        }

        return response()->json([
            'message' => 'Recursos vinculados al espacio',
            'recursos' => $recursos
        ], 200);
    }
}
