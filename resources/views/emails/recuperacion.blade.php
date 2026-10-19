<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="only dark">
    <meta name="supported-color-schemes" content="only dark">
    <style>
        :root {
            color-scheme: only dark;
        }
        body { 
            background-color: #0d1117 !important; 
            color: #ffffff !important; 
            font-family: 'Segoe UI', Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
        }
        .card { 
            max-width: 520px; 
            margin: 0 auto; 
            background-color: #161b22 !important; 
            border: 1px solid #30363d; 
            border-radius: 8px; 
            padding: 32px; 
            border-top: 3px solid #ff2d20; 
        }
        .badge { 
            display: inline-block; 
            padding: 4px 10px; 
            background-color: rgba(0, 117, 143, 0.25) !important; 
            border: 1px solid #00a4cc; 
            color: #00a4cc !important; 
            border-radius: 4px; 
            font-size: 11px; 
            font-weight: bold; 
            font-family: monospace; 
        }
        .text-body {
            color: #e6edf3 !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            margin: 12px 0 !important;
        }
        .btn { 
            display: block; 
            width: 100%; 
            padding: 14px 0; 
            background-color: #ff2d20 !important; 
            color: #ffffff !important; 
            text-align: center; 
            text-decoration: none; 
            font-weight: bold; 
            border-radius: 6px; 
            font-size: 14px; 
            margin-top: 24px; 
            box-shadow: 0 4px 12px rgba(255,45,32,0.3); 
        }
        .footer-text { 
            text-align: center; 
            font-size: 11px !important; 
            color: #8b949e !important; 
            margin: 4px 0 !important;
        }
        .footer-box {
            margin-top: 24px; 
            border-top: 1px solid #30363d; 
            padding-top: 16px; 
        }
    </style>
</head>
<body style="background-color: #0d1117 !important; color: #ffffff !important;">
    <div class="card" style="background-color: #161b22 !important;">
        <div style="text-align: center; margin-bottom: 20px;">
            <span class="badge">PROTOCOLO DE SEGURIDAD :: DANIEL MARCA</span>
            <h2 style="color: #ffffff !important; margin-top: 10px; font-size: 22px;">Larabel Recuperar Gmail</h2>
        </div>

        <p class="text-body" style="color: #e6edf3 !important;">Hola,</p>
        <p class="text-body" style="color: #e6edf3 !important;">
            Hemos recibido una solicitud para restablecer la contraseña asociada a tu cuenta: 
            <strong style="color: #00a4cc !important;">{{ $correo }}</strong>.
        </p>
        <p class="text-body" style="color: #e6edf3 !important;">Haz clic en el siguiente enlace seguro para crear tu nueva contraseña:</p>

        <a href="{{ $urlRestablecer }}" class="btn" style="color: #ffffff !important; background-color: #ff2d20 !important;">Restablecer Mi Contraseña →</a>

        <div class="footer-box">
            <p class="footer-text" style="color: #8b949e !important;">Si no solicitaste este cambio, puedes ignorar este mensaje de forma segura.</p>
            <p class="footer-text" style="color: #8b949e !important;">© 2026 TechAuth Global. Signed via Laravel &amp; MySQL Engine.</p>
        </div>
    </div>
</body>
</html>