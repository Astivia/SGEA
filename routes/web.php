<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreasController;
use App\Http\Controllers\ArticulosController;

use App\Http\Controllers\AutoresController;
use App\Http\Controllers\AutoresExternosController;
use App\Http\Controllers\ArticulosAutoresController;
use App\Http\Controllers\Comite_EditorialController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\ParticipantesController;
use App\Http\Controllers\RevisoresArticulosController;
use App\Http\Controllers\UsuariosController;

use App\Http\Controllers\LoginController;


Route::resource('areas', AreasController::class)->middleware('auth')->middleware('can:areas.index');
Route::resource('comite_editorial', Comite_EditorialController::class)->middleware('auth');
Route::resource('eventos', EventosController::class)->names('eventos')->middleware('auth');
Route::resource('usuarios', UsuariosController::class)->middleware('auth');
// PARTICIPANTES
Route::resource('participantes', ParticipantesController::class)->except(['index'])->middleware('auth');
Route::get('participantes/evento/{eventoId}', [ParticipantesController::class, 'index'])->name('participantes.evento.index')->middleware('auth');
Route::delete('participantes/{eventoId}/{usuarioId}', [ParticipantesController::class, 'destroy'])->name('participantes.destroy')->middleware('auth')->middleware('can:participantes.destroy');
//ARTICULOS
Route::resource('articulos', ArticulosController::class)->middleware('auth');
Route::get('articulos/{evento_id}/{id}', [ArticulosController::class, 'show'])->middleware('auth');
Route::get('articulos/{evento_id}/{id}/edit', [ArticulosController::class, 'edit'])->middleware('auth');
Route::put('articulos/{evento_id}/{id}', [ArticulosController::class, 'update']);

Route::resource('autores', ArticulosAutoresController::class)->middleware('auth');
Route::resource('autores_externos', AutoresExternosController::class)->middleware('auth');

// REVISORES DE ARTICULOS:
Route::resource('revisores_articulos', RevisoresArticulosController::class)->middleware('auth');
Route::get('revisores_articulos/evento/{eventoId}', [RevisoresArticulosController::class, 'index'])->name('revisores_articulos.evento.index')->middleware('auth');
Route::delete('revisores_articulos/{eventoId}/{usuarioId}/{articuloId}', [RevisoresArticulosController::class, 'destroy'])->name('revisores_articulos.destroy')->middleware('auth')->middleware('can:revisores_articulos.destroy');

//LOGIN - REGISTER
Route::view('/login',"login")->name('login');

 Route::post('/registrar', [LoginController::class, 'register'])->name('registrar');

 Route::post('/verificar-email', [LoginController::class, 'verificarEmail'])->name('verificar-email');
 Route::post('/reenviar-codigo', [LoginController::class, 'reenviarCodigo'])->name('reenviar-codigo');
 Route::post('/setPassword', [LoginController::class, 'setPassword'])->name('Password');
 Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.reset');
 
 Route::post('/validar-registro',[LoginController::class,'register'])->name('validar-registro');
 Route::post ('/inicia-sesion',[LoginController::class,'login'])->name('inicia-sesion');
 Route::get('/logout',[LoginController::class,'logout'])->name('logout');
 
 // DASHBOARDS

 Route::get('/redirect', [UsuariosController::class, 'redirectToAppropriateView'])->name('user.redirect');
 Route::get('/{acronimo}-index/{edicion}', [LoginController::class, 'index'])->name('evento.index');
 
 
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

Route::get('/forgot-password', function () {
    return view('ResetPass');
});
Route::get('/registro', function () {
    return view('register');
});

Route::get('/dashboard', function () {
    return view('HomeViews.dashboard');
})->name('dashboard');

Route::get('pruebas', function () {
    return view('pruebas');
});
Route::get('pruebas2', function () {
    return view('pruebas2');
});