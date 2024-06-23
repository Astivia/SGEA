@extends('layouts.master')
<title>Eventos</title>
</head>

@section('Content')

<div class="container">
    <h3>Seleccione un Tipo de Evento:</h3>
    <div class="info">
        @foreach ($Eventos  as $e)
            <div class="container">
                <img src="{{ asset('SGEA/public/assets/uploads/'.$e->img) }}" alt="logo" style="width: 150px;">
                <h2>{!!$e->nombre!!} ({!!$e->acronimo!!})</h2>
                <a href="{{ route('general', $e->acronimo) }}"><button>Ver Eventos</button></a>

            </div>
            <br>
        @endforeach
        
    </div>
</div>

@endsection


