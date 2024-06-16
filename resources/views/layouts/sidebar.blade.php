<div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                
                <div class="brand-icons">
                    <span class="las la-bell"></span>
                    <span class="las la-user-circle"></span>
                </div>
            </div>
        </div>
        <div class="sidebar-main">
            <div class="sidebar-user">
                <img src="./imgs/perfil.png" alt="">
                 <!-- <img src="{{ asset('/imgs/perfil.png') }}" alt=""> -->
                <div>
                    <h3>@auth {{Auth::user()->name}} @endauth</h3>
                    <span>Administrador</span>
                </div>                
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li>
                    <a href="{{route('home')}}"><span class="las la-calendar-alt"></span>inicio</a>
                    </li>
                </ul>
                <br>
                <div class="menu-head">
                    <span>Catálogos</span>
                </div>
                <ul>
                    <li><a href=eventos><span class="las la-calendar-alt"></span>Eventos</a></li>
                    <li><a href=participantes><span class="las la-user"></span>Participantes</a></li>
                    <li><a href=comite_editorial><span class="las la-user"></span>Comide Editorial</a></li>
                    <li><a href=areas><span class="las la-id-card"></span>Áreas</a></li>    
                    <li><a href=autores><span class="las la-comment"></span>Autores</a></li>
                    <li><a href=articulos><span class="las la-comment"></span>Artículos</a></li>
                    <li><a href=revisores_articulos><span class="las la-people-carry"></span>Revisores</a></li>                           
                </ul>
                <div class="menu-head">
                    <span>Aplicaciones</span>
                </div>
                <ul>
                    <li><a href=""><span class="las la-calendar"></span>Calendario</a></li>
                    <li><a href=""><span class="las la-user"></span>Contactos</a></li>
                    <br><br><br>
                    
                    <li><a href="{{route('logout')}}">Cerrar Sesión</a></li>
                    
                </ul>
            </div>
        </div>
    </div>