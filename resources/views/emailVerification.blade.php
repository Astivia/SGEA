<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('SGEA/public/assets/img/ITTOL.ico')}}" type="image/x-icon">
    <title>Confirmar Identidad</title>
</head>
<script>
    console.log({!!$codigo!!});
</script>

<body>
@if(session('error'))
    <script>
        alert('{{ session('error') }}');
    </script>
@endif

@if(session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
<div class="container">

    <h1>Verifica la dirección de correo electrónico</h1>
    <p>{!!$user->nombre!!}, Para verificar tu correo electrónico, hemos enviado un código a <strong> {!!$user->email!!} </strong></p>
    <form method="POST" id="verification-form" action="{{ route('verificar-email') }}">
        @csrf

        <input type="hidden" id="user-identifier" name="user-id" value="{!!$user->id!!}">
        <input type="hidden" id="codee" name="codigo" value="{!!$codigo!!}">

        <label for=""><strong>Introducir Codigo</strong></label><br>
        <input type="number" name="input-usuario" id="" placeHolder="ej: 1234">
        <br><br>
        <button type="submit" class="btn">Confirmar</button>
    </form>
    <a href="javascript:void(0);" id="resend-code-link">Reenviar Codigo</a>
</div>

<script>
    const resendCodeLink = document.getElementById('resend-code-link');

    resendCodeLink.addEventListener('click', function() {
        // Enviar solicitud AJAX para reenviar el código
        fetch('{{ route('reenviar-codigo') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                'user-id': {{ $user->id }}
            })
        })
        .then(response => {
            if (response.ok) {
                return response.text().then(text => {
                    alert(text);  // Mostrar mensaje de éxito
                });
            } else {
                return response.text().then(text => {
                    alert(text);  // Mostrar mensaje de error
                });
            }
        })
        .catch(error => {
            console.error('Error al reenviar el código:', error);
            alert('Ocurrió un error al reenviar el código. Inténtalo de nuevo más tarde.');
        });
    });
</script>
    
</body>
</html>