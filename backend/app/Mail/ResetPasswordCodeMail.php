<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordCodeMail extends Mailable
{
    use Queueable, SerializesModels;// Permite que el correo se pueda poner en cola y serializar los datos del correo

    public string $code;// Código de restablecimiento de contraseña que se enviará en el correo

    public function __construct(string $code)// Constructor que recibe el código de restablecimiento de contraseña
    {
        $this->code = $code; // Asigna el código recibido a la propiedad $code de la clase
    }

    public function build()// Método que construye el correo electrónico
    {
        return $this->subject('Código de restablecimiento de contraseña') // Establece el asunto del correo
                    ->view('emails.reset-password-code');// Establece la vista que se utilizará para el contenido del correo
    }
}