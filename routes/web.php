<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreasController;
use App\Http\Controllers\ArticulosAutoresController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\AutoresController;
//use App\Http\Controllers\Comite_EditorialController;
use App\Http\Controllers\EventosController;
//use App\Http\Controllers\ParticipantesAreasController;
use App\Http\Controllers\ParticipantesController;
//use App\Http\Controllers\RevisoresAreasController;
use App\Http\Controllers\RevisoresArticulosController;
use App\Http\Controllers\LoginController;


Route::resource('areas', AreasController::class)->middleware('auth');
Route::resource('articulos_autores', ArticulosAutoresController::class)->middleware('auth');
Route::resource('articulos', ArticulosController::class)->middleware('auth');
Route::resource('autores', AutoresController::class)->middleware('auth');
Route::resource('comite_editorial', Comite_EditorialController::class)->middleware('auth');
Route::resource('eventos', EventosController::class)->middleware('auth');
Route::resource('participantes_areas', ParticipantesAreasController::class)->middleware('auth');
Route::resource('participantes', ParticipantesController::class)->middleware('auth');
Route::resource('revisores_areas', RevisoresAreasController::class)->middleware('auth');
Route::resource('revisores_articulos', RevisoresArticulosController::class)->middleware('auth');

 Route::view('/login',"login")->name('login');
 Route::view('/registro',"register")->name('registro');
 Route::view('/home',"dashboard")->middleware('auth')->name('home');

Route::post('/validar-registro',[LoginController::class,'register'])->name('validar-registro');
Route::post ('/inicia-sesion',[LoginController::class,'login'])->name('inicia-sesion');
Route::get('/logout',[LoginController::class,'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('login');
});

Route::get('EnviarCorreo', function () {
    
    Mail::to('mastiviac@toluca.tecnm.mx')
        ->send(new App\Mail\TestEmail);

    return "Mensaje enviado";

})->name('EnviarCorreo');

// Route::get('ejecutarajax/{evento_id}','AutoresController@combo_autoresPorEvento');