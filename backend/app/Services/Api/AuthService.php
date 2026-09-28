<?php
namespace App\Services\Api;

use App\Models\User;
use App\Mail\ResetPasswordCodeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use Exception;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => '03', // Asignar el rol de usuario por defecto si no se proporciona
        ]);
        return ['user' => $user ];
    }

    public function login(array $data): array|false
    {
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {// Si las credenciales proporcionadas no son válidas, devuelve false indicando que el inicio de sesión ha fallado
            return false;
        }

        /** @var User $user */
        $user = Auth::user();// Obtiene el usuario autenticado actualmente utilizando el facade Auth
         if ($user->trashed()) { // Comprueba si el usuario autenticado ha sido eliminado (soft deleted)
            Log::warning('Login - Usuario eliminado', ['email' => $data['email']]); // Registra una advertencia en los registros indicando que el usuario ha sido eliminado
            Auth::logout(); // Cierra la sesión del usuario autenticado
            return false; // Devuelve false indicando que el inicio de sesión ha fallado debido a que el usuario ha sido eliminado
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function forgotPassword(array $data): array|false // Método para manejar la solicitud de restablecimiento de contraseña 
    {
        $user = User::where('email', $data['email'])->first(); // Busca al usuario en la base de datos utilizando el correo electrónico proporcionado
        if (!$user) { // Si no se encuentra ningún usuario con el correo electrónico proporcionado, devuelve false indicando que no se puede enviar un código de restablecimiento de contraseña
            return false;
        }

        try {
            $code = random_int(100000, 999999);

            DB::table('password_reset_tokens')->where('email', $data['email'])->delete();// Elimina cualquier código de restablecimiento de contraseña existente para el correo electrónico proporcionado
            DB::table('password_reset_tokens')->insert([// Inserta un nuevo código de restablecimiento de contraseña en la tabla 'password_reset_tokens'
                'email'      => $data['email'], // El correo electrónico del usuario que solicitó el restablecimiento de contraseña
                'token'       => (string)$code, // El código de restablecimiento de contraseña generado aleatoriamente
                'created_at' => Carbon::now(), // La fecha y hora actual para registrar cuándo se creó el código
            ]);

            Mail::to($data['email'])->send(new ResetPasswordCodeMail($code)); // Envía un correo electrónico al usuario con el código de restablecimiento de contraseña utilizando la clase 'ResetPasswordCodeMail'
            return ['email' => $data['email']]; // Devuelve un array con el correo electrónico del usuario y el código de restablecimiento de contraseña generado
            
        } catch (Exception $e) {
            //logger('Error en forgotPassword: ' . $e->getMessage());
            //logger('Trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function verificationCode(array $data): array | false // Método para verificar si el código de restablecimiento de contraseña proporcionado es válido
    {
        $record = DB::table('password_reset_tokens') // Consulta la tabla 'password_reset_tokens' para encontrar un registro que coincida con el correo electrónico y el código proporcionados
            ->where('email', $data['email']) // Filtra los registros por el correo electrónico proporcionado
            ->where('token', $data['code']) // Filtra los registros por el código proporcionado
            ->first(); // Obtiene el primer registro que coincida con los criterios de búsqueda

        if (!$record) {// Si no se encuentra ningún registro que coincida, devuelve false indicando que el código no es válido
            return false;
        }

        // Validez de 15 minutos
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) { // Comprueba si el código ha expirado (más de 15 minutos desde su creación)
            DB::table('password_reset_tokens')->where('email', $data['email'])->delete(); // Si el código ha expirado, elimina el registro de la tabla 'password_reset_tokens' para que no pueda ser reutilizado
            return false;
        }

        return ['email' => $data['email'], 'code' => $data['code']]; // Devuelve un array con el correo electrónico del usuario indicando que el código es válido
    }

    public function resetPassword(array $data): bool // Método para restablecer la contraseña del usuario si el código de verificación es válido
    {
        $code = $data['code'] ?? $data['token'] ?? null; // Obtiene el código de verificación del array de datos, ya sea desde la clave 'code' o 'token', o establece null si no se encuentra ninguno

        $record = DB::table('password_reset_tokens') // Consulta la tabla 'password_reset_tokens' para encontrar un registro que coincida con el correo electrónico y el código proporcionados
            ->where('email', $data['email']) // Filtra los registros por el correo electrónico proporcionado
            ->where('token', $code) // Filtra los registros por el código proporcionado
            ->first(); // Obtiene el primer registro que coincida con los criterios de búsqueda

        if (!$record) {
            return false;
        }

        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) { // Comprueba si el código ha expirado (más de 15 minutos desde su creación)
            DB::table('password_reset_tokens')->where('email', $data['email'])->delete(); // Si el código ha expirado, elimina el registro de la tabla 'password_reset_tokens' para que no pueda ser reutilizado
            return false;
        }

        $user = User::where('email', $data['email'])->first(); // Busca al usuario en la base de datos utilizando el correo electrónico proporcionado
        if (!$user) {
            DB::table('password_reset_tokens')->where('email', $data['email'])->delete(); // Si no se encuentra ningún usuario con el correo electrónico proporcionado, elimina el registro de la tabla 'password_reset_tokens' para que no pueda ser reutilizado
            return false;
        }

        $user->forceFill([ // Actualiza la contraseña del usuario con la nueva contraseña proporcionada en el array de datos, asegurándose de que esté cifrada utilizando Hash::make
            'password' => Hash::make($data['password']), // Cifra la nueva contraseña antes de guardarla en la base de datos
        ])->save();

        // Borrar el código para que no sea reutilizado
        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return true;
    }
}