<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $codigo;
    public $correo;
    public $urlRestablecer;

    public function __construct($codigo, $correo)
    {
        $this->codigo = $codigo;
        $this->correo = $correo;

        // Lee obligatoriamente la APP_URL del .env (Ngrok en tu PC o localhost en lo del profe)
        $baseUrl = rtrim(config('app.url'), '/');
        $this->urlRestablecer = $baseUrl . "/recuperar.html?correo=" . urlencode($correo) . "&codigo=" . $codigo;
    }

    public function build()
    {
        return $this->subject('🔒 Restablece tu contraseña - TechAuth Security')
                    ->view('emails.recuperacion');
    }
}