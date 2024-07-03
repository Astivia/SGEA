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
                <img src="{{ asset('SGEA/public/assets/img/'.Auth::user()->foto) }}" alt="">
            <div>
                <h3>@auth {{Auth::user()->nombre}} {{Auth::user()->ap_pat}} {{Auth::user()->ap_mat}}@endauth</h3>
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
                <li><a href="{{ url('eventos') }}"><span class="las la-calendar-alt"></span>Eventos</a></li>
                @role(['Administrador','Organizador'])
                <li><a href="{{ url('usuarios') }}"><span class="las la-user"></span>Usuarios</a></li>
                <li><a href="{{ url('autores') }}"><span class="las la-pen-nib"></span>Autores</a></li>
                <li><a href="{{ url('autores_externos') }}"><span class="las la-external-link-alt"></span>Autores externos</a></li>
                @endrole
                <li><a href="{{ url('articulos') }}"><span class="lar la-newspaper"></span>Artículos</a></li>
                @role(['Administrador','Organizador'])
                <li><a href="{{ url('areas') }}"><span class="las la-id-card"></span>Áreas</a></li>
                @endrole
                                        
            </ul>
            @role('Administrador')
            <div class="menu-head">
                <span>Administracion</span>
            </div>
            <ul>
                <li><a href="{{ url('comite_editorial') }}"><span class="las la-user"></span>Comide Editorial</a></li>
                <li><a href="{{ url('revisores_articulos') }}"><span class="las la-people-carry"></span>Revisores</a></li>     
            </ul>
            @endrole
        </div>
    </div>
</div>
