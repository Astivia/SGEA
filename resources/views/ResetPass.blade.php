<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('SGEA/public/assets/img/ITTOL.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
    <title>Restablecer Contraseña</title>
</head>
<body>
<div class="background"></div>
    <div class="container">
        <div class="header-img">
        <!-- <img src="{{asset('SGEA/public/assets/img/Logo itt-fn.png')}}" alt="">
        <img src="{{asset('SGEA/public/assets/img/Logo ittol-fn.png')}}" alt=""> -->
        <img src="{{asset('SGEA/public/assets/img/Logo ittol.png')}}" alt="">
        </div>
        <div class="card">
            <h1>Ayuda con la Contraseña</h1>
            <p>Escribe la dirección de correo electrónico asociado a tu cuenta. Si existe se enviara un codigo de verificacion</p>
            <form id="login-form" method="POST" action="{{ route('password.reset') }}">
            @csrf
                <label for="email">Correo:</label>
                <input type="email" name="email" id="correo">

                <button type="submit" class="button">Enviar</button>
            </form>
        </div>
    </div>
    
</body>
</html>