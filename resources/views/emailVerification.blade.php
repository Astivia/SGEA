<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Email</title>
</head>
<body>
@if(session('error'))
    <script>
        alert('{{ session('error') }}');
    </script>
    @endif

    @if(session('success'))
    <script>
    alert('{{ session('
        success ') }}');
    </script>
    @endif
    <form method="POST" id="verification-form" action="{{ route('verificar-email') }}">
        @csrf
        <h1>VERIFICAR EMAIL</h1>
        <p>{!!$user->nombre!!}, Introduce el codigo de verificacion<br> enviado al correo "{!!$user->email!!}"</p>

        <input type="hidden" id="user-identifier" name="user-id" value="{!!$user->id!!}">
        <input type="hidden" id="codee" name="codigo" value="{!!$codigo!!}">

        <label for="">Introducir Codigo:</label>
        <input type="number" name="input-usuario" id="" placeHolder="ej: 1234">
        <br><br>
        <button type="submit" class="btn">Confirmar</button>
    </form>
    
</body>
</html>