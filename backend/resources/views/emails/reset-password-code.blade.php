<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .code-container {
            text-align: center;
            margin: 30px 0;
        }
        .code {
            display: inline-block;
            background-color: #2196F3;
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 20px 40px;
            border-radius: 12px;
        }
        .message {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Restablecer Contraseña</h1>
            <p>Hemos recibido una solicitud para restablecer tu contraseña</p>
        </div>

        <div class="code-container">
            <div class="code">{{ $code }}</div>
        </div>

        <div class="message">
            <p>Tu código de verificación es el mostrado arriba. Ingrésalo en la aplicación para continuar con el proceso.</p>
            <p><strong>Este código expira en 15 minutos.</strong></p>
            <p>Si no solicitaste este cambio, puedes ignorar este correo de forma segura.</p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>