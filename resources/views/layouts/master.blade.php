@include ('layouts.Header')

@include ('layouts.sidebar')
    <body>
        @if(session('error'))
            <script>
                alert('{{ session('error') }}');
            </script>
        @endif

        @if(session('success'))
            <script>
            alert('{{ session('success') }}');
            </script>
        @endif
        <div class="main-content">
        @include ('layouts.head')
            <main>
                @yield('Content')
            </main>
        </div>
        <script src="{{asset('SGEA/public/js/script.js')}}"></script>

    </body>
@include ('layouts.Footer')
    
             
