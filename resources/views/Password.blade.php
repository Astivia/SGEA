<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('SGEA/public/css/style-home.css') }}">
    <link rel="shortcut icon" href="{{asset('SGEA/public/assets/img/ITTOL.ico')}}" type="image/x-icon">
    <title>Establecer Contraseña</title>
</head>
<body>
    <h1>ESTABLECER CONTRASEÑA</h1>
    <p>Hola <strong>{!!$user->nombre!!}</strong> porfavor introduce una contraseña nueva</p>
    <form method="POST" id="password-form" action="{{ route('Password') }}">
        @csrf

        <input type="hidden" id="user-identifier" name="user-id" value="{!!$user->id!!}">

        <label for=""><strong>Definir Contraseña: </strong></label><br>
        <input type="password" name="password" id="pass" placeHolder="">
        <br><br>
        <label for=""><strong>Confirmar Contraseña: </strong></label><br>
        <input type="password" name="password-confirm" id="pass-confirm" placeHolder="">

        <br><br>
        <button type="submit" class="btn">Confirmar</button>
    </form>
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
