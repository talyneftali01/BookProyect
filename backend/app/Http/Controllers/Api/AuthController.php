<?php
namespace App\Http\Controllers\Api;

use App\Services\Api\AuthService;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function __construct(private AuthService $authService){}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $result = $this->authService->register($validated);
        return $this->success($result, 'Usuario registrado exitosamente',201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login($validated);
        if (!$result) {
            return $this->error('Credenciales inválidas o usuario eliminado', 401);
        }

        return $this->success($result, 'Inicio de sesión exitoso');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $this->authService->logout($user);
        return $this->success(null, 'Sesión cerrada exitosamente');
    }
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([ // Valida que el campo 'email' sea obligatorio, tenga formato de correo electrónico, sea una cadena y exista en la tabla 'users' en la columna 'email'
            'email' => 'required|email|string|exists:users,email',
        ],[
            'email.exists' => 'No existe una cuenta activa con este correo electrónico.',
        ]);

        $result = $this->authService->forgotPassword($validated);
        if (!$result) {
            return $this->error('No se pudo enviar el correo de recuperación', 500);
        }

        return $this->success($result, 'Correo de recuperación enviado exitosamente');
    }

    public function verificationCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'code'  => 'required|string|size:6',
        ]);

        $result = $this->authService->verificationCode($validated);
        if (!$result) {
            return $this->error([],'Código de verificación inválido o expirado', 400);
        }

        return $this->success($result, 'Código de verificación válido');
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email|string|exists:users,email',
            'code'     => 'required|string|size:6',
            'password' => 'required|string|min:8', 
        ]);

        $result = $this->authService->resetPassword($validated);
        if (!$result) {
            return $this->error('No se pudo restablecer la contraseña', 500);
        }

        return $this->success(null, 'Contraseña reseteada exitosamente');
    }
}