@include ('layouts.Header')

@include ('layouts.sidebar')
    <body>
        
        <div class="main-content">
            <header>
                <img src="{{asset('SGEA/public/assets/img/TECNM.png')}}" alt="Logo-TecNM">
                <img src="{{asset('SGEA/public/assets/img/ITTOL-TEXT.png')}}" alt="ITTOL-text">
                <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="ITTOL-logo">
            </header>
            <main>
                <div class="options-bar">
                    <div class="menu-toggle">
                        <label for="">
                                <span class="las la-bars"></span>
                        </label>
                    </div>
                    <div class="header-icons">
                        <a href="{{ route('user.redirect') }}"><span class="las la-home"></span></a>
                        <a href="{{route('logout')}}"><span class="las la-door-closed"></span></a>
                    </div>
                </div>
                
                @if(session('error'))
                    <div class="alert alert-error" id="error-alert">
                        <i class="las la-exclamation-circle la-2x"></i>
                        <strong>{!! session('error') !!}</strong>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success" id="success-alert">
                        <i class="las la-check-circle la-2x"></i>
                        <strong> {!! session('success') !!}</strong>
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info" id="info-alert">
                        <i class="las la-info-circle la-2x"></i>
                        <strong> {!! session('info') !!}</strong>
                    </div>
                @endif
                
                @yield('Content')
            </main>
        </div>
    </body>
    

@include ('layouts.Footer-Scripts')


             
