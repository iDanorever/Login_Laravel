<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Mail\RecuperacionMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // 1. Registro Técnico con Validación de Duplicados
    public function registrar(Request $request)
    {
        try {
            // Validar si el DNI ya existe
            $existeDni = DB::table('Usuario')->where('Dni', $request->dni)->exists();
            if ($existeDni) {
                return response()->json([
                    'success' => false,
                    'message' => 'El DNI ingresado ya se encuentra registrado en el sistema.'
                ], 422);
            }

            // Validar si el Correo ya existe
            $existeCorreo = DB::table('Usuario')->where('Correo', $request->correo)->exists();
            if ($existeCorreo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El correo electrónico ya está asociado a otra cuenta.'
                ], 422);
            }

            

            // Encriptar contraseña y generar Passkey de respaldo
            $passHash = Hash::make($request->passwordd);
            $codigoRespaldo = $request->codigo_respaldo ?? strtoupper(substr(md5(uniqid()), 0, 8));

            // Ejecutar Stored Procedure
            DB::select('call sp_Usuario_Guardar(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->nombres,
                $request->apellidos,
                $request->dni,
                $request->fechaNacimiento,
                $request->edad ?? 18,
                $request->telefono,
                $request->correo,
                $passHash,
                $codigoRespaldo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente.',
                'codigo_respaldo' => $codigoRespaldo
            ], 200);

        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => 'El DNI o Correo ya está registrado en el sistema.'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud en la base de datos.'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. Iniciar Sesión Estándar (Correo + Contraseña)
    public function login(Request $request)
    {
        try {
            $usuario = DB::selectOne('call sp_Usuario_Login(?)', [$request->correo]);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'El correo ingresado no existe.'
                ], 404);
            }

            if (!Hash::check($request->passwordd, $usuario->Passwordd)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contraseña incorrecta.'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => $usuario,
                'message' => '¡Inicio de sesión exitoso!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    // 3. Iniciar Sesión con Passkey / Código de Respaldo
    public function loginCodigo(Request $request)
    {
        try {
            $usuario = DB::selectOne('call sp_Usuario_LoginCodigo(?)', [$request->codigo]);

            if ($usuario) {
                return response()->json([
                    'success' => true,
                    'data' => $usuario,
                    'message' => 'Acceso concedido mediante Passkey de respaldo.'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Código de acceso no válido o no encontrado.'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al validar Passkey: ' . $e->getMessage()
            ], 500);
        }
    }

    // 4. Solicitar Código de Recuperación (Guardado en MySQL)
    public function solicitarCodigo(Request $request)
{
    try {
        $usuario = DB::selectOne('call sp_Usuario_Login(?)', [$request->correo]);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'El correo electrónico no se encuentra registrado.'
            ], 404);
        }

        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        DB::select('call sp_Usuario_GuardarCodigo(?, ?)', [$request->correo, $codigo]);

        // Enviar el correo electrónico real a través de Gmail
        Mail::to($request->correo)->send(new RecuperacionMail($codigo, $request->correo));

        return response()->json([
            'success' => true,
            'message' => 'Correo de recuperación enviado con éxito a tu bandeja de entrada.'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al enviar el correo: ' . $e->getMessage()
        ], 500);
    }
}

   // 5. Cambiar Contraseña y Opcional Nueva Passkey
    public function cambiarPassword(Request $request)
    {
        try {
            // Nota: Validamos si el campo de contraseña viene como 'password' o 'passwordd'
            $passwordInput = $request->password ?? $request->passwordd;
            $passHash = Hash::make($passwordInput);
            
            DB::select('call sp_Usuario_UpdatePassword(?, ?)', [$request->correo, $passHash]);

            $nuevaPasskey = null;
            // Acepta tanto 'generar_passkey' como 'generar_nueva_passkey'
            if ($request->generar_nueva_passkey || $request->generar_passkey) {
                $nuevaPasskey = strtoupper(substr(md5(uniqid()), 0, 8));
                DB::table('Usuario')->where('Correo', $request->correo)->update(['codigo_respaldo' => $nuevaPasskey]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada con éxito en la base de datos.',
                'passkey'        => $nuevaPasskey, // Clave unificada para que el frontend la lea sin fallo
                'nuevo_passkey'  => $nuevaPasskey,
                'nueva_passkey'  => $nuevaPasskey
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la contraseña: ' . $e->getMessage()
            ], 500);
        }
    }
    
}