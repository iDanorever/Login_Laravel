<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('registrar', 'registrar');
    Route::post('solicitar-codigo', 'solicitarCodigo');
    Route::post('validar-codigo', 'validarCodigo');
    Route::post('cambiar-password', 'cambiarPassword');
    Route::post('login-codigo', [AuthController::class, 'loginCodigo']);
    Route::post('clave-emergencia', 'generarClaveEmergencia');
});