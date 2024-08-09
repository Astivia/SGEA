<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreasController;
use App\Http\Controllers\ArticulosController;
use App\Http\Controllers\ArticulosAutoresController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\ParticipantesController;
use App\Http\Controllers\RevisoresArticulosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LoginController;


Route::resource('areas', AreasController::class)->middleware('auth');
Route::resource('eventos', EventosController::class)->middleware('auth');
Route::get('/eventos/cancel/{evento_id}', [EventosController::class, 'cancelEvent'])->name('evento.cancel');

Route::resource('usuarios', UsuariosController::class)->middleware('auth');

// PARTICIPANTES
Route::resource('participantes', ParticipantesController::class)->except(['index'])->middleware('auth');
Route::get('participantes/evento/{eventoId}', [ParticipantesController::class, 'index'])->name('participantes.evento.index')->middleware('auth');
Route::delete('participantes/{eventoId}/{usuarioId}', [ParticipantesController::class, 'destroy'])->name('participantes.destroy')->middleware('auth')->middleware('can:participantes.destroy');

//ARTICULOS
Route::resource('articulos', ArticulosController::class)->except(['index'])->middleware('auth');
Route::get('{eventoId}/articulos/', [ArticulosController::class, 'index'])->name('articulos.evento.index')->middleware('auth');
Route::get('{evento_id}/articulo/{id}', [ArticulosController::class, 'show'])->middleware('auth');
Route::get('{evento_id}/articulo/{id}/edit', [ArticulosController::class, 'edit'])->middleware('auth');
Route::put('{evento_id}/articulo/{id}', [ArticulosController::class, 'update']);
    //Rutas AJAX
    Route::post('/check-author', [ArticulosController::class, 'checkAuthor'])->name('revisar-existencia');
    Route::post('/verify-curp', [UsuariosController::class, 'verifyCurp'])->name('verify-curp');
    Route::post('/verify-email', [UsuariosController::class, 'verifyEmail'])->name('verify-email');
    Route::post('/insert-user', [UsuariosController::class, 'insertUser'])->name('insertar-usuario');


//AUTORES
Route::resource('autores', ArticulosAutoresController::class)->except(['index'])->middleware('auth');
Route::get('{eventoId}/autores/', [ArticulosAutoresController::class, 'index'])->name('autores.index')->middleware('auth');
Route::get('{evento_id}/autor/{id}', [ArticulosAutoresController::class, 'show'])->middleware('auth');
Route::get('{eventoId}/autores/{id}/edit', [ArticulosAutoresController::class, 'edit'])->middleware('auth');

Route::get('{eventoId}_{id}/MisArticulos/', [ArticulosController::class, 'AuthorArticles'])->middleware('auth');
Route::get('{eventoId}_{id}/Evaluaciones/', [ArticulosController::class, 'Evaluations'])->middleware('auth');
Route::get('{eventoId}_{id}/detalle/', [ArticulosController::class, 'Details'])->middleware('auth');

// REVISORES DE ARTICULOS (ADMIN)
Route::resource('revisores', RevisoresArticulosController::class)->except(['index'])->middleware('auth');
Route::get('{eventoId}/revisoresArticulos/', [RevisoresArticulosController::class, 'index'])->name('revisores.index')->middleware('auth');
Route::get('{evento_id}/revisoresArticulo/{articulo_id}/edit', [RevisoresArticulosController::class, 'edit'])->middleware('auth');
    //Rutas AJAX
    Route::get('/get-articles/{area_id}', [ArticulosController::class, 'getArticles'])->name('get-articles');

// RUTAS DE REVISION (REVISOR)
Route::get('{eventoId}/ArticulosPendientes/{usuId}', [RevisoresArticulosController::class, 'pendientes'])->middleware('auth');
Route::get('{eventoId}_{usuId}/ArticulosRevisados/', [RevisoresArticulosController::class, 'revisados'])->middleware('auth');
Route::get('{eventoId}_{usuId}/revision/', [RevisoresArticulosController::class, 'revision'])->middleware('auth');
Route::put('Calificar_{articuloID}/', [RevisoresArticulosController::class, 'update']);

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
  Route::get('/sidebar', [LoginController::class, 'getSidebar'])->name('get.sidebar');
 
  //MIGRACIONES
  Route::post('/migrate-data', [EventosController::class,'migrarDatos'])->name('migrate.data');
  Route::post('migrar/{evento_id}', [EventosController::class,'migrarEvento'])->name('migrate.event');

 //eliminacion multiple
 Route::post('/areas/delete-multiple', [AreasController::class, 'deleteMultiple'])->name('areas.deleteMultiple');
 Route::post('/usuarios/delete-multiple', [UsuariosController::class, 'deleteMultiple'])->name('usuarios.deleteMultiple');
 //  Route::post('articulos/delete-multiple', [ArticulosController::class, 'deleteMultiple']);
 Route::post('/eventos/delete-multiple', [EventosController::class, 'deleteMultiple'])->name('eventos.deleteMultiple');
 Route::post('/articulos/delete-multiple', [ArticulosController::class, 'deleteMultiple'])->name('articulos.deleteMultiple');
 Route::post('/participantes/delete-multiple', [ParticipantesController::class, 'deleteMultiple'])->name('participantes.deleteMultiple');
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