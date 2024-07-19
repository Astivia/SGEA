<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" href="{{asset('SGEA/public/assets/img/ITTOL.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
    <title>Establecer Contraseña</title>
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
        <h1>RESTABLECER CONTRASEÑA</h1>
        <p>Hola <strong>{!!$user->nombre!!}</strong> porfavor introduce una contraseña nueva</p>
        <form method="POST" id="password-form" action="{{ route('Password') }}">
            @csrf

            <input type="hidden" id="user-identifier" name="user-id" value="{!!$user->id!!}">

            <label for=""><strong>Definir Contraseña: </strong></label>
            <input type="password" name="password" id="pass" placeHolder="">
            
            <label for=""><strong>Confirmar Contraseña: </strong></label>
            <input type="password" name="password-confirm" id="pass-confirm" placeHolder="">

            
            <button type="submit" class="btn">Confirmar</button>
        </form>
      </div>
 </div>
</body>
</html>

<script>
  function validatePassword() {
    var password = document.getElementById("pass").value;
    var confirmPassword = document.getElementById("pass-confirm").value;

    if (password !== confirmPassword) {
      alert("Las contraseñas no coinciden. ¡Por favor verifica!");
      return false; // Prevent form submission
    }
    return true; // Allow form submission
  }

  document.getElementById("password-form").addEventListener("submit", validatePassword);
</script>
