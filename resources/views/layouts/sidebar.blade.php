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
            <h2>SGEA</h2>
                <!-- <img src="./imgs/perfil.png" alt=""> -->
                <img src="{{ asset('SGEA/public/assets/img/perfil.png') }}" alt="">
            <div>
                <h3>@auth {{Auth::user()->nombre}} {{Auth::user()->ap_pat}} {{Auth::user()->ap_mat}}@endauth</h3>
                <span>Administrador</span>
            </div>                
        </div>

        <div class="sidebar-menu">
            <div class="menu-head">
                <span>Catálogos</span>
            </div>
            <ul class="menu-list active">
                <li><a href="{{ url('eventos') }}"><span class="las la-calendar-alt"></span>Eventos</a></li>
                <li><a href="{{ url('participantes') }}"><span class="las la-user"></span>Participantes</a></li>
                <li><a href="{{ url('comite_editorial') }}"><span class="las la-user"></span>Comide Editorial</a></li>
                <li><a href="{{ url('areas') }}"><span class="las la-id-card"></span>Áreas</a></li>    
                <li><a href="{{ url('autores') }}"><span class="las la-comment"></span>Autores</a></li>
                <li><a href="{{ url('articulos') }}"><span class="las la-comment"></span>Artículos</a></li>
                <li><a href="{{ url('revisores_articulos') }}"><span class="las la-people-carry"></span>Revisores</a></li>                           
            </ul>
            <div class="menu-head">
                <span>Administracion</span>
            </div>
            <ul>
                <li><a href="{{ url('roles') }}"><span class="las la-calendar"></span>Roles</a></li>
                <li><a href="{{ url('permisos') }}"><span class="las la-user"></span>Permisos</a></li>
            </ul>
        </div>
    </div>
</div>
