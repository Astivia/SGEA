<div class="sidebar" id="sidebar">
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
                @if(session('rol')!== null)
                    <span>{{session('rol')}}</span>

                @endif
            </div>
        </div>    
        <div class="sidebar-menu">
            <div class="menu-head">
                <span>Catálogos</span>
            </div>
            <ul class="menu-list active">
                 @if(session('eventoID')!==null)
                    <li><a href="{{ url(session('eventoID').'/articulos') }}"><span class="lar la-newspaper"></span>Artículos</a></li>
                    <li><a href="{{ url(session('eventoID').'/autores') }}"><span class="las la-pen-nib"></span>Autores</a></li>
                    @role('Administrador')
                    <li><a href="{{ url(session('eventoID').'/revisoresArticulos') }}"><span class="las la-glasses"></span>Revisores</a></li>  
                    @endrole 
                @else
                    @role('Administrador')
                    <li><a href="{{ url('areas') }}"><span class="las la-id-card"></span>Areas</a></li>
                    <li><a href="{{ url('usuarios') }}"><span class="las la-user"></span>Usuarios</a></li>
                    @endrole
                @endif
                <li><a href="{{ url('eventos') }}"><span class="las la-calendar-alt"></span>Eventos</a></li>
            </ul>
            @role('Administrador')
            @if(session('eventoID')!==null)
            
                <div class="menu-head">
                    <span>Administracion</span>
                </div>
                <ul class="menu-list active">
                    <li><a href="{{ url('areas') }}"><span class="las la-id-card"></span>Areas</a></li>
                    <li><a href="{{ url('usuarios') }}"><span class="las la-user"></span>Usuarios</a></li>
                    
                </ul>
            @endif
            @endrole
        </div>
    </div>
</div>
