<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Iniciar Sesion</title>
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
        <!-- Formulario de INICIO DE SESION -->
        <div class="logreg-box">
            <div class="form-box login">
                <form id="login-form" method="POST" action="{{ route('inicia-sesion') }}">
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
                    <div class="login-register">
                        <p>No tienes una cuenta? <a href="registro" class="register-link">Registrarme</a></p>
                    </div>
                </form>
            </div>
            <!-- Formulario de registro -->
            <div class="form-box register">
                <h2>Registro</h2>
                <form id="register-form" method="POST" action="{{ route('validar-registro') }}">
                    @csrf
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input type="text" id="register-name" name="name"  required>
                        <label>Nombre</label>
                    </div>
                
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input type="text" id="register-email" name = "email" required>
                        <label>Correo electrónico</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class=''></i></span>
                        <input type="password" id="register-password" name="password" required>
                        <label>Contraseña</label>
                        <span class="toggle-password" onclick="togglePassword('register-password')">
                            <i class='bx bxs-show'></i>
                        </span>
                        <div id="password-requirements" class="tooltip">
                            <p>La contraseña debe cumplir con los siguientes requisitos:</p>
                            <ul>
                                <li id="req-length">No más de 8 caracteres</li>
                                <li id="req-uppercase">Al menos una letra mayúscula</li>
                                <li id="req-number">Al menos un número</li>
                                <li id="req-special">Al menos un carácter especial</li>
                            </ul>
                        </div>
                    </div>
                    <div id="password-strength">
                        <div id="password-strength-text"></div>
                        <div id="password-strength-bar"></div>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class=''></i></span>
                        <input type="password" id="register-confirm-password" required>
                        <label>Confirmar contraseña</label>
                        <span class="toggle-password" onclick="togglePassword('register-confirm-password')">
                            <i class='bx bxs-show'></i>
                        </span>
                    </div>
                    <button type="submit" class="btn">Registrar</button>

                    <div class="login-register">
                        <p>¿Ya tienes una cuenta? <a href="login" class="login-link">Inicia sesión</a></p>
                    </div>
                </form>
            </div>

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
</body>
</html>