<div class="sidebar">
    <div class="sidebar-main">
        <div class="sidebar-user">
            <h2>SGEA</h2>
                <img src="{{ asset('SGEA/public/assets/img/'.Auth::user()->foto) }}" alt="">
            <div>
                <h3>@auth {{Auth::user()->nombre}} {{Auth::user()->ap_paterno}}@endauth</h3>
                @role('Administrador')
                    <span>Administrador</span>
                @endrole
                @role('Organizador')
                    <span>Organizador</span>
                @endrole
            </div>
        </div>    
        <div class="sidebar-menu">
            <div class="menu-head">
                <span>Catálogos</span>
            </div>
            <ul class="menu-list active">
                @role(['Administrador','Organizador'])
                
                @endrole
                <li><a href="{{ url(session('eventoID').'/articulos') }}"><span class="lar la-newspaper"></span>Artículos</a></li>
                <li><a href="{{ url(session('eventoID').'/autores') }}"><span class="las la-pen-nib"></span>Autores</a></li>    
                <li><a href="{{ url('comite_editorial') }}"><span class="las la-user"></span>Comide Editorial</a></li>  
            </ul>
            @role('Administrador')
            <div class="menu-head">
                <span>Administracion</span>
            </div>
            <ul class="menu-list">
                <li><a href="{{ url('eventos') }}"><span class="las la-calendar-alt"></span>Eventos</a></li>
                <li><a href="{{ url('areas') }}"><span class="las la-id-card"></span>Areas</a></li>
                <li><a href="{{ url('usuarios') }}"><span class="las la-user"></span>Usuarios</a></li>
            </ul>
            @endrole
        </div>
    </div>
</div>
