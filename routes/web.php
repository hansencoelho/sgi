<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/', function () {
//     return view('auth.login');
// });

### AUTENTICAÇÃO ###

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => true,
]);

// Route::get('/home', function () {
//     return view('dashboard.home');
// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/logar', function () {
//     return view('login');
// });

################
#   Registro   #
################

route::group(["prefix" => "registro"], function() {

    ## GET CRUD ##
    Route::get('', 'ControllerRegistro@index')->name('registro');
    // Route::get('/create', 'ControllerUsuario@create')->name('registro.create');
    Route::get('/show/{id_registro}', 'ControllerRegistro@show')->name('registro.show');

    // ## POST CRUD ##
    // Route::post('/', 'ControllerUsuario@store')->name('registro.store');
    // Route::post('/update', 'ControllerUsuario@update')->name('registro.update');
    // Route::post('/find', 'ControllerUsuario@find')->name('registro.find');
    
    // ## GET GERAL ##
    // Route::get('/exportar/', 'ControllerUsuario@exportar')->name('registro.exportar');

    ## GET LISTAS ##
    Route::get('/list_tipo_registro/', 'ControllerRegistro@list_tipo_registro');
    Route::get('/list_tipo_local_registro/', 'ControllerRegistro@list_tipo_local_registro');
    Route::get('/list_declarante/', 'ControllerRegistro@list_declarante');
    Route::get('/list_religiao/', 'ControllerRegistro@list_religiao');
    Route::get('/list_estado_civil/', 'ControllerRegistro@list_estado_civil');
    Route::get('/list_uf/', 'ControllerRegistro@list_uf');
    Route::get('/list_cidade/', 'ControllerRegistro@list_cidade');
    Route::get('/list_nacionalidade_sobrenome/', 'ControllerRegistro@list_nacionalidade_sobrenome');
    // ## GET INTEGRACAO ##
    // Route::get('/autocomplete_grupo_usuario/', 'ControllerUsuario@autocomplete_grupo_usuario');

    });

################
#    USUÁRIO   #
################

route::group(["prefix" => "usuario"], function() {

    ## GET CRUD ##
    Route::get('', 'ControllerUsuario@index')->name('usuario');
    Route::get('/create', 'ControllerUsuario@create')->name('usuario.create');
    Route::get('/show/{id_usuario}', 'ControllerUsuario@show')->name('usuario.show');

    ## POST CRUD ##
    Route::post('/', 'ControllerUsuario@store')->name('usuario.store');
    Route::post('/update', 'ControllerUsuario@update')->name('usuario.update');
    Route::post('/find', 'ControllerUsuario@find')->name('usuario.find');
    
    ## GET GERAL ##
    Route::get('/exportar/', 'ControllerUsuario@exportar')->name('usuario.exportar');

    ## GET INTEGRACAO ##
    Route::get('/autocomplete_grupo_usuario/', 'ControllerUsuario@autocomplete_grupo_usuario');

    });