<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlfaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LogMasivaController;
use App\Http\Controllers\LogFotografiaController;
use App\Http\Controllers\LogCotejoIndividualController;
use App\Http\Controllers\LogFacialEnvivoUnoAUnoController;
use App\Http\Controllers\LogLivnesController;
use App\Http\Controllers\LogController;

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login',[LoginController::class,'login']);
Route::get('logout',[LoginController::class,'logout']);


Route::middleware('auth')->middleware('rol:all,all')->group(function () {

   Route::get('/', [LoginController::class, 'index']);

   /*Route::get('dash', function () {
      return view('dash');
   });*/

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
   Route::get('dash',[UsuarioController::class,'dash']);

   Route::get('departamentos/{departamento_id}/municipios', [UsuarioController::class, 'getMunicipios']);

   Route::get('usuario/edicion/{id}', [UsuarioController::class, 'edit'])->name('usuario.edit');

   Route::post('usuario/edicion/{id}', [UsuarioController::class, 'update'])->name('usuario.update');

   Route::get('usuario/menu',[UsuarioController::class,'menu'])->name('usuario.menu');
   Route::get('log/menu',[UsuarioController::class,'reporte']);

   Route::get('log/facial/{id}',[LogFacialEnvivoUnoAUnoController::class,'lista']);
   Route::get('log/facial-export/{id}', [LogFacialEnvivoUnoAUnoController::class, 'exportCsv']);
   Route::get('log/unoauno',[LogFacialEnvivoUnoAUnoController::class,'listaunoauno']);
   Route::get('log/fotografia',[LogFotografiaController::class,'lista']);
   Route::get('log/masiva',[LogMasivaController::class,'lista']);
   Route::get('log/liveness',[LogLivnesController::class,'lista']);
   Route::get('log/liveness-export', [LogLivnesController::class, 'exportTxt']);
   Route::get('log/zip-export-all', [LogController::class, 'zipExportAll']);

   


   Route::get('/loaderAjax/{idmasiva}', [AlfaController::class, 'loaderAjax']);

   Route::get('/importaralfa',[AlfaController::class,'importaralfa']);
   Route::get('/masiva', [AlfaController::class, 'masivaTest']);
   Route::get('resultadoCargaMasiva', [AlfaController::class, 'index']);

   Route::post('/loginApi',[LoginController::class,'loginApi']);

   Route::get('log/unoauno-export', [LogFacialEnvivoUnoAUnoController::class, 'exportCsv2'])->name('log/unoauno-export');

   Route::post('log/logs', [LogCotejoIndividualController::class, 'store']);
   Route::get('log/logs', [LogCotejoIndividualController::class, 'index']);
   Route::get('log/logs/view', [LogCotejoIndividualController::class, 'showLogs']);
   Route::get('/log-fotografia/csv', [LogFotografiaController::class, 'exportCsv']);
   Route::get('cotejounoauno/consultar', [LogCotejoIndividualController::class, 'cotejounoauno']);
   Route::post('cotejounoauno/mostrarcedula', [LogCotejoIndividualController::class, 'capturarCedula'])->name('capturar.cedula');
   Route::post('generar-pdf', [LogCotejoIndividualController::class, 'generarPDF'])->name('generar.pdf');
   Route::get('/execute-jar', [LogFacialEnvivoUnoAUnoController::class, 'executeJar']);
   Route::get('connectliveness/{cedula}', [LogCotejoIndividualController::class, 'connectliveness'])->name('connectliveness');
   Route::get('/download-pdf/{filename}', [LogCotejoIndividualController::class, 'downloadpdf'])->name('download.pdf');
   Route::get('rechazarcotejo/{cedula}', [LogCotejoIndividualController::class, 'rechazarcotejo'])->name('rechazarcotejo');
   Route::get('connectliveness/qr/{cedula}', [LogCotejoIndividualController::class, 'qr'])->name('qr');


   Route::get('log/upload', function () {
    return view('log.upload');
   });
   Route::post('log/upload-image', [LogFacialEnvivoUnoAUnoController::class, 'upload']);

   /*Route::get('loader/{idLogMasiva}', function () {
      return view('loader');
   });*/

   Route::get('/download-zip', [LogMasivaController::class, 'createZip'])->name('download.zip');
   Route::get('/loader/{idmasiva}', [AlfaController::class, 'loader']);

});

Route::get('/cambiar-contrasena', [UsuarioController::class, 'showChangePasswordForm'])->name('cambiar.contrasena.form');
Route::post('/cambiar-contrasena', [UsuarioController::class, 'changePassword'])->name('cambiar.contrasena');
Route::get('/actualizar-contrasena', [UsuarioController::class, 'showUpdatePasswordForm'])->name('actualizar.contrasena.form');
Route::post('/actualizar-contrasena', [UsuarioController::class, 'updatePassword'])->name('actualizar.contrasena');

//require _DIR_.'/auth.php';
