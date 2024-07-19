<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{asset('SGEA/public/assets/img/ITTOL.ico')}}" type="image/x-icon">
    <link rel="stylesheet" href="./css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Registrarse</title>
</head>

<body>
    @if(session('error'))
    <script>
    alert('{{ session('
        error ') }}');
    </script>
    @endif

    @if(session('success'))
    <script>
    alert('{{ session('
        success ') }}');
    </script>
    @endif
    <div class="background"></div>
    <div class="container ">
        <div class="content">
        <div class="header-img">
        <!-- <img src="{{asset('SGEA/public/assets/img/Logo itt-fn.png')}}" alt="">
        <img src="{{asset('SGEA/public/assets/img/Logo ittol-fn.png')}}" alt=""> -->
        <img src="{{asset('SGEA/public/assets/img/Logo ittol.png')}}" alt="">
      </div>
            <h2 class="logo">SGEA<i class='bx bxs-calendar-event'></i></h2>
            @if(isset($usuarios))
            <h1>existe</h1>
            @endif
            <div class="text-content">
                <h2>Bienvenidos! <br> <span>Sistema de Gestion de Eventos Academicos</span> </h2>
                <p>Este es un sistema de gestion de eventos academicos, el cual tiene como objetivo eficientar el
                    desarrollo de eventos institucionales</p>
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
                    <form method="POST" id="registration-form" action="{{ route('registrar') }}">
                        @csrf
                        <h1>REGISTRARSE</h1>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-id-card'></i></span>
                            <input type="text" id="curp" name="curp" required>
                            <label for="email" class="form-label">CURP</label>
                            <span id="curp-message" style="color: red;"></span>
                        </div>
                        <div class="input-box">
                            <span class="icon"><i class='bx bx-male'></i></span>
                            <input type="text" id="register-name" name="nombre" required>
                            <label for="nombre" class="form-label">Nombre</label>
                            
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-user-rectangle'></i></span>
                            <input type="text" id="ap_pat" name="ap_paterno" required>
                            <label for="ap_pat" class="form-label">Apellido Paterno</label>
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-user'></i></span>
                            <input type="text" id="noap_mat" name="ap_materno" required>
                            <label for="ap_mat" class="form-label">Apellido Materno</label>
                        </div>                 

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-envelope' ></i></span>
                            <input type="email" id="email" name="email" required>
                            <label for="email" class="form-label">Correo electrónico:</label>
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-phone' ></i></span>
                            <input type="text" id="telefono" name="telefono" required>
                            <label for="telefono" class="form-label">Telefono:</label>
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-low-vision'></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <label for="password" class="form-label">Contraseña:</label>
                        </div>

                        <button type="submit" class="btn btn-primary">registrarse</button>

                        <!-- <button type="submit" class="btn btn-primary">Validar email</button> -->
                    </form>
                    <p>¿Ya tienes cuenta? <a href="{{route('login')}}">Iniciar Sesion</a></p>
                </div>

            </div>
        </div>
    </div>
    <script>
    document.getElementById('curp').addEventListener('blur', function() {
        let curp = this.value;
        fetch('{{ route("verify-curp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ curp: curp })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'exists') {
                document.getElementById('register-name').value = data.user.nombre;
                document.getElementById('ap_pat').value = data.user.ap_paterno;
                document.getElementById('ap_mat').value = data.user.ap_materno;
                document.getElementById('email').value = data.user.email;
                document.getElementById('telefono').value = data.user.telefono;
                document.getElementById('curp-message').textContent = '';
            } else {
                document.getElementById('curp-message').textContent = 'Usuario no registrado en sistema';
            }
        });
    });
</script>
</body>

</html>