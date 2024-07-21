    <header>
        <img src="{{asset('SGEA/public/assets/img/TECNM.png')}}" alt="">
        <img src="{{asset('SGEA/public/assets/img/ITTOL-TEXT.png')}}" alt="">
        <img src="{{asset('SGEA/public/assets/img/lgoTec.png')}}" alt="">
    </header>
    <div class="options-bar">
        <div class="menu-toggle">
                <label for="">
                    <span class="las la-bars"></span>
                </label>
            </div>
            <!-- <img src="./assets/img/header.png" alt="logo" class="logo"> -->
            <div class="header-icons">
                <a href="{{ route('user.redirect') }}"><span class="las la-home"></span></a>
                <a href="{{route('logout')}}"><span class="las la-door-closed"></span></a>
            </div>
    </div>