@extends('layouts.master')
    <title>Informacion</title>
</head>
@section('Content')
    <div class="container">
        <h1>{!!$evento->nombre!!} ({!!$evento->acronimo!!} {!!$evento->edicion!!})</h1>
        <div class="info">
            
            <img src="{{asset('SGEA/public/assets/uploads/'.$evento->logo)}}" alt="">
            <dv class="data">
                <p><strong>Fecha de Inicio: </strong>{!!$evento->fecha_inicio!!}</p>
                <p><strong>Fecha de Fin: </strong>{!!$evento->fecha_fin!!}</p>
                <strong>Status del evento:</strong>{!!$evento->estado!!}
            </dv>
            <div class="links">
                @role(['Administrador','Organizador'])
                    <a href="{{ route('participantes.evento.index', ['eventoId' => $evento->id]) }}" class="link-card">
                        <i class="las la-users la-3x"></i>Participantes
                    </a>
                    <a href="{{ route('comite.evento.index', ['eventoId' => $evento->id]) }}" class="link-card">
                        <i class="las la-user-shield la-3x"></i>Comite Edit.
                    </a>
                @endrole
                <a href="{{ route('articulos.evento.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="lar la-newspaper la-3x"></i>Articulos
                </a>
                <a href="{{ route('autores.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-pen-nib la-3x"></i>Autores
                </a>
                @role(['Administrador','Organizador'])
                <a href="{{ route('revisores.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-glasses la-3x"></i>Revisores
                </a>
                @endrole
                <a href="" class="link-card">
                    <i class="las la-id-card la-3x"></i>Talleres
                </a>
                <a href="" class="link-card">
                    <i class="las la-id-card la-3x"></i>Conferencias
                </a>
            </div>

            <button id="migrate-button">Migrar Informacion</button>
            @role(['Administrador','Organizador'])
                @if($evento->estado===1)
                    <a href=""><button>Iniciar Evento</button></a>
                @elseif($evento->estado===3)
                    <button id="migrate-button">Migrar Informacion</button>
                @endif
            @endrole
        </div>
    </div>
@endsection


<script type="text/javascript">
    $(document).ready(function() {
        $('#migrate-button').click(function() {
            $.ajax({
                url: '{{ route('migrate.data') }}',
                type: 'POST',
                data: {
                _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(response) {
                    alert('Migration failed: ' + response.responseJSON.error);
                }
            });
        });
    });
</script>