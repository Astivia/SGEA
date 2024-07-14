<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <div class="container">
        <h1>Ayuda con la Contraseña</h1>
        <p>Escribe la dirección de correo electrónico asociado a tu cuenta. Si existe se enviara un codigo de verificacion</p>
        <form id="login-form" method="POST" action="{{ route('password.reset') }}">
        @csrf
            <label for="email">Correo:</label>
            <input type="email" name="email" id="correo">

            <button type="submit" class="button">Enviar</button>
        </form>

    </div>
    
</body>
</html>