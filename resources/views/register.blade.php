<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/style.css">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{asset('SGEA/public/assets/img/ITTOL.ico')}}" type="image/x-icon">
    
    <title>Registrarse</title>
</head>
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
    <style>
    /* Centrar verticalmente el modal */
    #loginErrorModal .modal-dialog {
        display: flex;
        align-items: center;
        justify-content:center;
        min-height: 100vh; /* Asegura que ocupe toda la altura de la ventana */
        
    }

    /* Estilos adicionales para el modal */
    #loginErrorModal .modal-content {
        /* Puedes añadir estilos adicionales según tus necesidades */
    }
</style>

    @if(session('register_error'))
<div class="modal" id="loginErrorModal" tabindex="-1" role="dialog" aria-labelledby="loginErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginErrorModalLabel">Error de registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ session('register_error') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Mostrar automáticamente el modal al cargar la página si hay un error de login
    $(document).ready(function() {
        $('#loginErrorModal').modal('show');
        $('#loginErrorModal').on('hidden.bs.modal', function () {
            // Limpiar cualquier mensaje de error después de cerrar el modal
            // Esto es opcional y depende de cómo quieras manejar los mensajes de error
        });
    });
</script>
@endif
</body>
</html>