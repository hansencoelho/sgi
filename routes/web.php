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

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('', ['middleware' => 'auth', 'uses' => 'ControllerHome@index']);
Route::get('/', 'ControllerHome@index');

// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('/register', function () {
//     return view('auth.register');
// });

### AUTENTICAÇÃO ###

// Auth::routes();

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
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
    Route::get('/create', 'ControllerRegistro@create')->name('registro.create');
    Route::get('/show/{id_registro}', 'ControllerRegistro@show')->name('registro.show');
    Route::get('/arquivo/{id_arquivo}', 'ControllerRegistro@arquivo');
    
    ## POST CRUD ##
    Route::post('', 'ControllerRegistro@store')->name('registro.store');
    Route::post('/update', 'ControllerRegistro@update')->name('registro.update');
    Route::post('/find', 'ControllerRegistro@find')->name('registro.find');
    Route::post('/delete', 'ControllerRegistro@delete');
    Route::post('/delete_arquivo', 'ControllerRegistro@delete_arquivo');
    Route::post('/exportar', 'ControllerRegistro@exportar');

    ## GET AUTOCOMPLETE ##
    Route::get('/autocomplete_uf/', 'ControllerRegistro@autocomplete_uf');
    Route::get('/autocomplete_cidade/', 'ControllerRegistro@autocomplete_cidade');
    Route::get('/autocomplete_religiao/', 'ControllerRegistro@autocomplete_religiao');

    });

################
#    USUÁRIO   #
################

route::group(["prefix" => "usuario"], function() {

    ## GET CRUD ##
    Route::get('', 'ControllerUsuario@index')->name('usuario');
    Route::get('/create', 'ControllerUsuario@create')->name('usuario.create');
    Route::get('/show/{id_usuario}', 'ControllerUsuario@show');
    Route::get('/alterar_senha', 'ControllerUsuario@alterar_senha');

    ## POST CRUD ##
    Route::post('', 'ControllerUsuario@store')->name('usuario.store');
    Route::post('/update', 'ControllerUsuario@update')->name('usuario.update');
    Route::post('/find', 'ControllerUsuario@find')->name('usuario.find');
    
    ## GET GERAL ##
    // Route::get('/exportar/', 'ControllerUsuario@exportar')->name('usuario.exportar');
    
    ## GET AUTOCOMPLETE ##
    Route::get('/autocomplete_funcao/', 'ControllerUsuario@autocomplete_funcao');

    });

######################
#    ALTERAR SENHA   #
######################

route::group(["prefix" => "alterar_senha"], function() {

    ## GET CRUD ##
    Route::get('', 'ControllerPrimeiroAcesso@index')->name('alterar_senha');
    // Route::get('/create', 'ControllerUsuario@create')->name('usuario.create');
    // Route::get('/show/{id_usuario}', 'ControllerUsuario@show');
    // Route::get('/alterar_senha', 'ControllerUsuario@alterar_senha');

    ## POST CRUD ##
    // Route::post('', 'ControllerUsuario@store')->name('usuario.store');
    // Route::post('/update', 'ControllerUsuario@update')->name('usuario.update');
    // Route::post('/find', 'ControllerUsuario@find')->name('usuario.find');
    
    ## GET GERAL ##
    // Route::get('/exportar/', 'ControllerUsuario@exportar')->name('usuario.exportar');
    
    ## GET AUTOCOMPLETE ##
    // Route::get('/autocomplete_funcao/', 'ControllerUsuario@autocomplete_funcao');

    });