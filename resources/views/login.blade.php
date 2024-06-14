<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <div class="register">
        <h1>INICIAR SESION</h1>
        <form method="POST" action="{{ route('inicia-sesion') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <br> <br>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <br> <br>
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
    </form>
    <br><br>
    <a href="{{route('registro')}}">Registrarse</a><br>
    <br>    
    <a href="">¿Olvidaste tu contraseña?</a>
         
    </div>
</body>
</html>