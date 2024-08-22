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
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <span class="alerta-modal"><i class='bx bxs-error-alt'></i>Alerta</span>
                <span class="close" onclick="closeModal('errorModal')">&times;</span>
                <h1>Error de inicio de sesion </h1>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div id="successModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('successModal')">&times;</span>
                <p>{{ session('success') }}</p>
            </div>
        </div>
     @endif
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <div class="header-img">
                <img src="{{asset('SGEA/public/assets/img/TECNM.png')}}" alt="">
            </div>
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
        <!-- Formulario  INICIO DE SESION -->
        <div class="logreg-box">
            <div class="form-box login">
                {!! Form::open(['id' => 'login-form', 'method' => 'POST','url' => 'inicia-sesion']) !!}
                
                    <h2>Iniciar Sesion</h2>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                        {!! Form::email('email', null, ['id'=>'login-email','required']) !!}
                        {{ Form::label('usuario-mail', 'Email') }}
                    </div>
                    <div class="input-box">
                        {!! Form::password('password', null, ['id'=>'login-password','required']) !!}
                        {{ Form::label('login-Contraseña', 'Contraseña') }}
                        <span class="toggle-password" onclick="togglePassword('login-password')"><i class='bx bxs-show'></i></span>
                    </div>
                    <div class="remember-forgot"> 
                        <label for="remember_me">
                            {{ Form::checkbox('remember_me', 1, false, ['id' => 'remember_me']) }} Mantener la sesión iniciada
                        </label>                     
                    </div>
                    {!! Form::button('Iniciar Sesion', ['type' => 'submit','class'=>'btn']) !!}
                {!!Form::close()!!}
                
                <div class="login-register">
                    <p>No tienes una cuenta? <a href="registro" class="register-link">Registrarme</a></p>
                    <a href="forgot-password" id="forgot-password-link">Olvidaste tu contraseña?</a>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="./js/scriptLogin.js"> </script>
</body>
</html>
