<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\EspaiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Crear comanda
Route::post('comandes/create',[ComandaController::class,'store']);

//Obtenir comandes
Route::get('comandes',[ComandaController::class,'index']);

//Obtenir comandes pendents
Route::get('comandes/pendents',[ComandaController::class,'comandaPendents']);

//Obtenir comandes per id
Route::get('comandes/{id}',[ComandaController::class,'comandaId'])->where('id', '[0-9]+');

//Obtenir comandes per mail
Route::get('comandes/{mail}',[ComandaController::class,'comandaMail'])->where('mail', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}');

//Actualitzar comanda
Route::put('comandes/{id}', [ComandaController::class, 'update']);

//Eliminar comanda
Route::delete('/comandes/{id}', [ComandaController::class, 'destroy']);

//Comprobar fecha
Route::get('comandes/verificar-disponibilidad', [ComandaController::class, 'checkEspacioOcupadoEnFecha']);

//------------------------------//

//Crear recurso
Route::post('recurs/create',[RecursoController::class,'store']);

//Eliminar recursos
Route::delete('/recurs/{id}', [RecursoController::class, 'destroy']);

//Obtenir recursos
Route::get('recurs',[RecursoController::class,'index']);

//Obtenir recurso per id
Route::get('recurs/{id}',[RecursoController::class,'show'])->where('id', '[0-9]+');

//Actualitzar recurso
Route::put('recurs/{id}', [RecursoController::class, 'update']);


//------------------------------//

//Crear espai
Route::post('espai/create',[EspaiController::class,'store']);

//Eliminar espai
Route::delete('/espai/{id}', [EspaiController::class, 'destroy']);

//Obtenir espai
Route::get('espai',[EspaiController::class,'index']);

//Obtenir espai per id
Route::get('espai/{id}',[EspaiController::class,'show'])->where('id', '[0-9]+');

//Actualitzar espai
Route::put('espai/{id}', [EspaiController::class, 'update']);

//Obtenir capacitat
Route::get('espai/{espaiId}/capacidad', [EspaiController::class, 'obtenerCapacidadEspacio']);

//------------------------------//

//Crear espai_recurs
Route::post('/espais-recursos', [EspaiController::class, 'createEspaiRecurso']);

//Obtenir espai_recurs
Route::get('/espai/{id}/recursos', [EspaiController::class, 'getRecursosVinculadosEspacio']);

//------------------------------//

//Crear comanda_recurs
Route::post('/comandas-recursos', [ComandaController::class, 'createComandaRecurso']);




//chEhLOOQmrUyJk7pYZbbSPTRQ1HRI7GGeJ2CRHpT -> admin