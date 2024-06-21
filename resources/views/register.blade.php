<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
</head>
<body>
    <div class="register">
        <h1>REGISTRARSE</h1>
        <form method="POST" action="{{ route('validar-registro') }}">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <br><br>
        <div class="mb-3">
            <label for="ap_pat" class="form-label">Apellido Paterno:</label>
            <input type="text" class="form-control" id="noap_pat" name="ap_pat" required>
        </div>
        <br><br>
        <div class="mb-3">
            <label for="ap_mat" class="form-label">Apellido Materno:</label>
            <input type="text" class="form-control" id="noap_mat" name="ap_mat" required>
        </div>
            <br><br>
            <div class="mb-3">
            <label for="email" class="form-label">CURP:</label>
            <input type="text" class="form-control" id="curp" name="curp" required>
        </div>
        <br><br>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <br><br>
        
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <br><br>
        
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
    <br><br>
    <p>¿Ya tienes cuenta? <a href="{{route('login')}}">Iniciar Sesion</a></p>
        
         
    </div>
</body>
</html>