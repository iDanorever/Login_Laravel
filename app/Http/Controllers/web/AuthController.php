<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegistrar()
    {
        return view('auth.registrar');
    }

    public function showBienvenido()
    {
        if (!session()->has('usuario')) {
            return redirect()->route('login');
        }
        return view('auth.bienvenido');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'passwordd' => 'required'
        ]);

        $usuario = DB::selectOne('call sp_Usuario_Login(?)', [$request->correo]);

        // Acepta contraseña en texto plano (como el insert manual) o con Hash
        if ($usuario && ($request->passwordd === $usuario->Passwordd || Hash::check($request->passwordd, $usuario->Passwordd))) {
            session(['usuario' => $usuario]);
            return redirect()->route('bienvenido');
        }

        return back()->withErrors(['correo' => 'Correo o contraseña incorrectos.']);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombres' => 'required',
            'correo' => 'required|email',
            'passwordd' => 'required|min:4'
        ]);

        $passHash = Hash::make($request->passwordd);
        DB::select('call sp_Usuario_Guardar(?, ?, ?)', [$request->nombres, $request->correo, $passHash]);

        return redirect()->route('login')->with('success', 'Cuenta creada con éxito. Inicia sesión.');
    }

    public function logout()
    {
        session()->forget('usuario');
        return redirect()->route('login');
    }
}