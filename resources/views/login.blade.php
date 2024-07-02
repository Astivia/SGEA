<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="{{asset('SGEA/public/assets/img/ITTOL.ico')}}" type="image/x-icon">
    <title>Iniciar Sesion</title>
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
        <!-- Formulario de INICIO DE SESION -->
        <div class="logreg-box">
            <div class="form-box login">
                <form id="login-form" method="POST" action="{{route('inicia-sesion')}}">
                @csrf
                    <h2>Iniciar Sesion</h2>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                        <input type="email" id="login-email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <input type="password" id="login-password" name="password" required>
                        <label>Password</label>
                        <span class="toggle-password" onclick="togglePassword('login-password')"><i class='bx bxs-show'></i></span>
                    </div>
                    <div class="remember-forgot">                       
                        <a href="#" id="forgot-password-link">Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn">Iniciar Sesion</button>
                </form>
                
                <div class="login-register">
                    <p>No tienes una cuenta? <a href="registro" class="register-link">Registrarme</a></p>
                </div>
            </div>
            <!-- Formulario de registro -->
            

        </div>
    </div>

    <!-- Modals -->
    <!-- <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message"></p>
            <div id="verification-code-section">
                <input type="text" id="verification-code" placeholder="Código de verificación">
                <button id="submit-verification-code" class="btn">Enviar</button>
                <p id="countdown"></p>
            </div>
        </div>
    </div>
    <div id="forgot-password-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Ingresa tu correo electrónico</p>
            <input type="email" id="forgot-password-email" placeholder="Correo electrónico">
            <button id="send-forgot-password-email" class="btn">Enviar</button>
        </div>
    </div> -->

    <!-- <script src="./js/script-login.js"></script> -->

    <!-- <div class="register">
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
    </div> -->
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

    @if(session('login_error'))
<div class="modal" id="loginErrorModal" tabindex="-1" role="dialog" aria-labelledby="loginErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginErrorModalLabel">Error de inicio de sesión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ session('login_error') }}</p>
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