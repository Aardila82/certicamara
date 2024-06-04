<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlfaController;
use App\Http\Controllers\LogFacialEnvivoUnoAUnoController;
use App\Http\Controllers\LogFotografiaController;

Route::get('',[LoginController::class,'index']);

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login',[LoginController::class,'login']);
Route::get('logout',[LoginController::class,'logout']);


//Route::middleware('auth')->group(function () {

   Route::get('dash', function () {
      return view('dash');
   });

   Route::get('guardadoFormulario', function () {
      return view('guardadoFormulario');
   });

   Route::get('editadoFormulario', function () {
    return view('usuario/editadoformulario');
 })->name('usuario.editadoformulario');

   Route::get('usuario/listado',[UsuarioController::class,'lista']);
   Route::post('/usuario/desactivar/{id}', [UsuarioController::class, 'desactivar'])->name('usuario.desactivar');
   Route::post('/usuario/cambiarEstado/{id}', [UsuarioController::class, 'cambiarEstado'])->name('usuario.cambiarEstado');
   Route::get('usuario/listado',[UsuarioController::class,'lista']);
   Route::get('usuario/creacion',[UsuarioController::class,'index']);
   Route::post('usuario/creacion',[UsuarioController::class,'store']);
   Route::get('departamentos/{departamento_id}/municipios', [UsuarioController::class, 'getMunicipios']);

   Route::get('usuario/edicion/{id}', [UsuarioController::class, 'edit'])->name('usuario.edit');

   Route::post('usuario/edicion/{id}', [UsuarioController::class, 'update'])->name('usuario.update');

   Route::get('usuario/menu',[UsuarioController::class,'menu'])->name('usuario.menu');
   Route::get('usuario/reporte',[UsuarioController::class,'reporte']);

   Route::get('log/facial',[LogFacialEnvivoUnoAUnoController::class,'lista']);
   Route::get('log/fotografia',[LogFotografiaController::class,'lista']);

   Route::get('/importaralfa',[AlfaController::class,'importaralfa']);

   Route::post('/loginApi',[LoginController::class,'loginApi']);

//});


//require __DIR__.'/auth.php';











