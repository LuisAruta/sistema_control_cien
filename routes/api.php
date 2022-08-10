<?php

use App\Constant\PermissionConstant;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::patch('settings/profile', [ProfileController::class, 'update']);
    Route::patch('settings/password', [PasswordController::class, 'update']);
    Route::apiResource('dependencia', 'Api\DependenciaController');
    Route::post('dependencia/search', 'Api\DependenciaController@index');
    Route::post('dependencia/crear', 'Api\DependenciaController@store');
    Route::put('dependencia/{id}', 'Api\DependenciaController@update');
    Route::get('dependencias', 'Api\DependenciaController@all');
    Route::get('delegaciones', 'Api\DependenciaController@allDelegaciones');
    Route::get('intervinientes', 'Api\DependenciaController@allIntervinientes');
    Route::get('estadoActual/{delegacion_id}', 'Api\DashboardController@calcularEstadoActual');
    Route::get('estadisticas/{anio}/{delegacion_id}', 'Api\DashboardController@calcularEstadisticas');
    Route::get('filtros/{delegacion_id}', 'Api\DashboardController@setearOpcionesFiltros');
    Route::get('filtrosITCC', 'Api\DashboardController@setearOpcionesFiltrosITCC');
    Route::apiResource('delito', 'Api\DelitoController');
    Route::apiResource('tipo-colision', 'Api\TipoColisionController');
    Route::apiResource('tipo-indicio', 'Api\TipoIndicioController');
    Route::apiResource('tipo-intervencion-tecnica', 'Api\TipoIntervencionTecnicaController');
    Route::apiResource('estado', 'Api\EstadoController');
    Route::apiResource('estados-oficio', 'Api\EstadoOficioController');

    Route::get('user', 'Auth\UserController@current');
    Route::post('user/crear', 'Auth\UserController@store');
    Route::get('user-perito', 'Auth\UserController@allPerito');
    Route::post('user/search', 'Auth\UserController@index');
    Route::put('user/{id}', 'Auth\UserController@update');
    Route::put('user/blanquear-clave/{id}','Auth\UserController@blanquear');
    Route::delete('user/eliminar/', 'Auth\UserController@destroy');
    Route::get('roles', 'Auth\UserController@allRoles');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');

});

Route::apiResource('intervencion-tecnica', 'Api\IntervencionTecnicaController');
Route::post('intervencion-tecnica/search', 'Api\IntervencionTecnicaController@index');
Route::post('intervencion-tecnica/agregar-oficio', 'Api\IntervencionTecnicaController@agregarOficio');

Route::apiResource('necro', 'Api\NecroController');
Route::post('necro/search', 'Api\NecroController@index');
Route::post('necro/agregar-oficio', 'Api\NecroController@agregarOficio');

Route::apiResource('traslado', 'Api\TrasladoController');
Route::post('traslado/search', 'Api\TrasladoController@index');
Route::post('traslado/agregar-oficio', 'Api\TrasladoController@agregarOficio');

Route::apiResource('oficio', 'Api\OficioController');
Route::get('oficio/searchBy/{type}/{id}','Api\OficioController@findAllByIDOficiable');

Route::apiResource('sin-efecto', 'Api\SinEfectoController');
Route::post('sin-efecto/search', 'Api\SinEfectoController@index');
Route::post('sin-efecto/agregar-oficio', 'Api\SinEfectoController@agregarOficio');


Route::prefix('external')->group(function () {
    Route::get('expediente/{numeroExpediente}/', 'Api\ExternalController@ExpedienteByNumero');
    Route::get('personal/{documento}/', 'Api\ExternalController@personal');
    Route::get('renaper/{documento}/', 'Api\ExternalController@renaper');
    // ARGIS
    Route::get('departamentos/', 'Api\ExternalController@departamentos');
    Route::get('localidades/{departamento}', 'Api\ExternalController@localidades');
    Route::get('barrios/{departamento}/{barrio_incompleto}', 'Api\ExternalController@barrios');
    Route::get('calles/{departamento}/{calle_incompleta}', 'Api\ExternalController@calles');
});





