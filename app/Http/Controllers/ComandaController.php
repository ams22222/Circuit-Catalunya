<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\Recurso;
use App\Models\Comanda_Recurso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

    class ComandaController extends Controller
    {
    //Crear comanda
        public function store(Request $request)
        {
            
            if (!Auth::guard('sanctum')->user()->tokenCan('create')) {
                return response()->json(['message' => 'No tens permís'], 401);
            }
            
            $validatedData = $request->validate([
                'email' => 'required|string',
                'espai_id' => 'required|exists:espais,id',
                'data_solicitud' => 'required|date',
                'entrades' => 'required|string'
            ]);

            $validatedData['estat_comanda'] = "pendent";
            
            $comanda = Comanda::create($validatedData); // Crear la instancia de Comanda

            if ($comanda) {
                $comandaId = $comanda->id; // Obtener la ID de la comanda creada
                return response()->json(["Resposta" => "Comanda creada correctament", 'comandaId' => $comandaId], 201);
            } else {
                return response()->json(["Resposta" => "Error en la creació"], 405);
            }
        }
         
//Obtenir comandes per email
    public function comandaMail($email)
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('update')){
            return response()->json(['message' => 'No tens permís'], 401);
        }  

        $comandes = Comanda::where('email', $email)->get();

        if ($comandes->isEmpty()) {
        return response()->json(['message' => 'No es troben comandes per aquest correu'], 404);
        } else {
            return response()->json($comandes, 200);
        }
    }

//Obtenir comandes
    public function index()
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('update')){
            return response()->json(['message' => 'No tens permís'], 401);
        }

        $comandes=Comanda::all();
        return response()->json($comandes, 200);
    }

    
    public function indexVista()
    {
        $tokenName = 'Administrador';
    
        $userId = auth('sanctum')->id();
        $token = PersonalAccessToken::where('tokenable_id', $userId)
            ->where('name', $tokenName)
            ->first();
    
        // Verificar si el token existe y el nombre coincide
        if ($token) {
            $peticions = Comanda::paginate(15);
            return view('getComandes', compact('peticions', 'tokenName'));
        }
    
        return response()->json(['message' => 'No tienes permiso'], 401);
    }

//Obtenir comandes pendents
    public function comandaPendents()
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('update')){
            return response()->json(['message' => 'No tens permís'], 401);
        }    
        $comandes = Comanda::where('estat_comanda', 'pendent')->get();
        return response()->json($comandes, 200);
    }

//Obtenir comandes per id
    public function comandaId($id)
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('update')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }

        $comandes = Comanda::where('id', $id)->get();

        if ($comandes->isEmpty()) {
            return response()->json(['message' => 'No es troben comandes per aquest correu id'], 404);
        } else {
            return response()->json($comandes, 200);
        }
    }

//Actualitzar comanda
    public function update(Request $request, $id)
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('update')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }  
        $comanda = Comanda::find($id);
        if (!$comanda) {
            return response()->json(['message' => 'La comanda no existe'], 404);
        }
        $validatedData = $request->validate([
            'email' => 'string',
            'espai_id' => 'exists:espais,id',
            'data_solicitud' => 'date',
            'entrades' => 'string',
            'estat_comanda' => 'in:pendent,acceptada,cancel·lada,tancada'
        ]);

        $comanda->email = $validatedData['email'];
        $comanda->espai_id = $validatedData['espai_id'];
        $comanda->data_solicitud = $validatedData['data_solicitud'];
        $comanda->entrades = $validatedData['entrades'];
        $comanda->estat_comanda = $validatedData['estat_comanda'];
        

        if ($comanda->update($validatedData)) {
            return response()->json(["Resposta"=> "Actualització correcta"],200);
        } else {
            return response()->json(["Resposta"=> "Error a l'actualitzar"],405);
        }
    }

//Eliminar comanda
    public function destroy(Request $request, $id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('delete')) {
            return response()->json(['message' => 'No tens permís'], 401);
        }
        $comanda = Comanda::find($id);
        if (!$comanda) {
            return response()->json(['message' => 'La comanda no existeix'], 404);
        }
        if ($comanda->estat_comanda == 'pendent' || $comanda->estat_comanda == 'cancel·lada') {
            if ($comanda->destroy($id)) {
                return response()->json(['message' => 'Comanda eliminada'], 200);
            } else {
                return response()->json(['message' => 'Error al eliminar la comanda'], 405);
            }
        }
        else{
            return response()->json(['message' => "Error! L'estat no és pendent o cancel·lat"], 405);
        }
    }


    //Crear comanda_recurso
    public function createComandaRecurso(Request $request)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('create')) {
            return response()->json(['message' => 'No tienes permiso para crear relaciones'], 401);
        }

        $validatedData = $request->validate([
            'comanda_id' => 'required|exists:comandas,id',
            'recurso_id' => 'required|exists:recursos,id',
        ]);
        
        $comanda = Comanda::findOrFail($validatedData['comanda_id']);
        $recurso = Recurso::findOrFail($validatedData['recurso_id']);

        $comanda_recurso = Comanda_Recurso::create([
            'comanda_id' => $comanda->id,
            'recurso_id' => $recurso->id,
        ]);

        return response()->json(['message' => 'Relación creada correctamente'], 201);
    }

    //Obtenir els recursos d'un espai
    public function getRecursosVinculadosEspacio($id)
    {
        if (!Auth::guard('sanctum')->user()->tokenCan('read')) {
            return response()->json(['message' => 'No tienes permiso para ver los recursos vinculados'], 401);
        }
    
        $espai = Espai::find($id);
    
        if (!$espai) {
            return response()->json(['message' => 'Espai no encontrado'], 404);
        }
    
        $recursos = $espai->espai_recursos()->with('recurso')->get()->pluck('recurso');
    
        return response()->json([
            'message' => 'Recursos vinculados al espacio',
            'recursos' => $recursos
        ], 200);
    }

    //Comprobar fecha
    public function checkEspacioOcupadoEnFecha(Request $request)
    {
        $fecha = $request->input('fecha');
        $espacioId = $request->input('espacio_id');
    
        // Convierte la fecha en un objeto Carbon para facilitar la comparación
        $fechaCarbon = Carbon::createFromFormat('Y-m-d', $fecha);
    
        // Comprueba si existe una comanda para la fecha y el espacio específicos
        $comanda = Comanda::where('data_solicitud', $fechaCarbon->format('Y-m-d'))
            ->where('espai_id', $espacioId)
            ->first();
    
        // Devuelve true si hay una comanda, lo que significa que está ocupado
        $ocupado = !is_null($comanda);
    
        return response()->json(['ocupado' => $ocupado]);
    }
}
