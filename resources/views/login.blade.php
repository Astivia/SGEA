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
        <!-- <script>
            alert('{{ session('error') }}');
        </script> -->
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('errorModal')">&times;</span>
                <h1>Error de inicio de sesion </h1>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(session('success'))
        <!-- <script>
            alert('{{ session('success') }}');
            </script> -->
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
                        <label for="remember_me">
                            <input type="checkbox" id="remember_me" name="remember_me"> Mantener la sesión iniciada
                        </label>                     
                    </div>
                    
                    <button type="submit" class="btn">Iniciar Sesion</button>
                </form>
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
