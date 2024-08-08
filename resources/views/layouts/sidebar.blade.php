<div class="sidebar" id="sidebar">
    <div class="sidebar-main">
        <div class="sidebar-user">
            <h2>SGEA</h2>
            <a href="{{ url('usuarios/'.Auth::user()->id.'/edit') }}"  data-user-id="{{ Auth::user()->id }}">
                @if(Auth::user()->foto === "DefaultH.jpg" || Auth::user()->foto === "DefaultM.jpg")
                    <img src="{{ asset('SGEA/storage/app/public/Users/profile/'.Auth::user()->foto) }}" alt="">
                @else
                    <img src="{{ asset('SGEA/storage/app/public/Users/profile/'.Auth::user()->curp.'/'.Auth::user()->foto) }}" alt="">

                @endif
                <i class="las la-pen profile-edit-icon"></i>
            </a>
            <div>
                <h3>@auth {{ Auth::user()->nombre }} {{ Auth::user()->ap_paterno }} @endauth</h3>
                @role('Administrador')
                    <span>Administrador</span>
                @endrole
                @if(session('rol') !== null)
                    <span>{{ session('rol') }}</span>
                @endif
            </div>
        </div>  

        <div class="sidebar-menu">
            <div class="menu-head">
                <span>Catálogos</span>
            </div>
            <ul class="menu-list active">
                @if(session('eventoID') !== null)
                    <li>
                        <a href="{{ url(session('eventoID').'/autores') }}" class="{{ Request::is(session('eventoID').'/autores') ? 'active' : '' }}">
                            <span class="las la-pen-nib"></span>Autores
                        </a>
                    </li>
                    @role('Administrador')
                        <li>
                            <a href="{{ url(session('eventoID').'/articulos') }}" class="{{ Request::is(session('eventoID').'/articulos') ? 'active' : '' }}">
                                <span class="lar la-newspaper"></span>Artículos
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(session('eventoID').'/revisoresArticulos') }}" class="{{ Request::is(session('eventoID').'/revisoresArticulos') ? 'active' : '' }}">
                                <span class="las la-glasses"></span>Revisores
                            </a>
                        </li>
                    @endrole 
                @else
                    <li>    
                        <a href="{{ url('eventos')}}" class="{{ Request::is('eventos') ? 'active' : '' }}">
                            <span class="las la-calendar-alt"></span>Eventos
                        </a>
                    </li>
                    @role('Administrador')
                        <li>
                            <a href="{{ url('areas') }}" class="{{ Request::is('areas') ? 'active' : '' }}">
                                <span class="las la-id-card"></span>Areas
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('usuarios') }}" class="{{ Request::is('usuarios') ? 'active' : '' }}">
                                <span class="las la-user"></span>Usuarios
                            </a>
                        </li>
                    @endrole
                @endif
                    
            </ul>
            @role('Administrador')
                @if(session('eventoID') !== null)
                    <div class="menu-head">
                        <span>Administracion</span>
                    </div>
                    <ul class="menu-list active">
                        <li>
                            <a href="{{ url('areas') }}" class="{{ Request::is('areas') ? 'active' : '' }}">
                                <span class="las la-id-card"></span>Areas
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('usuarios') }}" class="{{ Request::is('usuarios') ? 'active' : '' }}">
                                <span class="las la-user"></span>Usuarios
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('eventos')}}" class="{{ Request::is('eventos') ? 'active' : '' }}">
                                <span class="las la-calendar-alt"></span>Eventos
                            </a>
                        </li>
                    </ul>
                @endif
            @endrole
        </div>
    </div>
</div>
