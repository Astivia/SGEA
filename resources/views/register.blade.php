<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/register.css">
   

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
</head>
<body>
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <h2 class="logo">SGEA<i class='bx bxs-calendar-event'></i></h2>
            <div class="text-content">
                <h2>Bienvenidos! <br> <span>Sistema de Gestion de Eventos Academicos</span> </h2>
                <p>Este es un sistema de gestion de eventos academicos, el cual tiene como objetivo eficientar el desarrollo de eventos institucionales</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-facebook-circle'></i></a>
                    <a href="#"><i class='bx bxl-instagram-alt'></i></a>
                    <a href="#"><i class='bx bxl-youtube'></i></a>
                </div>
            </div>
        </div>

        <div class="logreg-box">
            <div class="form-box login">


    <div class="register">
        <form method="POST" action="{{ route('validar-registro') }}">
            @csrf
            <h1>REGISTRARSE</h1>

            <div class="input-box">
                <span class="icon"><i class='bx bxs-user'></i></span>
                <input type="text"  id="register-name" name="nombre" required>
                <label for="nombre" class="form-label">Nombre</label>
            </div>

            <div class="input-box">
                <span class="icon"><i class='bx bxs-user'></i></span>
                <input type="text" id="ap_pat" name="ap_pat" required>
                <label for="ap_pat" class="form-label">Apellido Paterno</label>
            </div>

            <div class="input-box">
                <span class="icon"><i class='bx bxs-user'></i></span>
                <input type="text" id="noap_mat" name="ap_mat" required>
                <label for="ap_mat" class="form-label">Apellido Materno</label>
            </div>

            <div class="input-box">
            <span class="icon"><i class='bx bxs-user'></i></span>
            <input type="text" id="curp" name="curp" required>
            <label for="email" class="form-label">CURP</label>
        </div>

        <div class="input-box">
            <span class="icon"><i class='bx bxs-user'></i></span>
            <input type="email" id="email" name="email" required>
            <label for="email" class="form-label">Correo electrónico:</label>
        </div>
        
        
        <div class="input-box">
            <span class="icon"><i class='bx bxs-user'></i></span>
            <input type="password" class="form-control" id="password" name="password" required>
            <label for="password" class="form-label">Contraseña:</label>
        </div>
        
        
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="{{route('login')}}">Iniciar Sesion</a></p>
        
         
    </div>
   
</body>
</html>