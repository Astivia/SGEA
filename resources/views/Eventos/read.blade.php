@extends('layouts.master')
    <title>Informacion</title>
@section('Content')
    <div class="container">
        <h1>{!!$evento->nombre!!} ({!!$evento->acronimo!!} {!!$evento->edicion!!})</h1>
        <div class="info">
            <img src="{{asset('SGEA/public/assets/uploads/'.$evento->logo)}}" alt="">
            <div class="data">
                <p><strong>Fecha de Inicio: </strong>{!!$evento->fecha_inicio!!}</p>
                <p><strong>Fecha de Fin: </strong>{!!$evento->fecha_fin!!}</p>
                <strong>Status del evento:</strong>{!!$evento->estado!!}
            </div>
        </div>
        <div class="links">
            @role('Administrador')
                <a href="{{ route('autores.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-pen-nib la-3x"></i>Autores
                </a>
                <a href="{{ route('participantes.evento.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-users la-3x"></i>Participantes
                </a>
                <a href="{{ route('revisores.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-glasses la-3x"></i>Revisores
                </a>
                <a href="{{ route('articulos.evento.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="lar la-newspaper la-3x"></i>Articulos
                </a>
            @endrole           
            @if(session('rol')==='Autor')
                <a href="{{url(session('eventoID').'/ArticulosCorresp/'.Auth::user()->id)}}" class="link-card">
                    <i class="las la-bell la-3x"></i> Articulos Propios
                </a>
                <a href="{{url(session('eventoID').'/ArticulosCorresp/'.Auth::user()->id)}}" class="link-card">
                    <i class="las la-bell la-3x"></i> Articulos Rechazados
                </a>
                <a href="{{url(session('eventoID').'/ArticulosCorresp/'.Auth::user()->id)}}" class="link-card">
                    <i class="las la-bell la-3x"></i> Articulos Aceptados
                </a>
            @elseif(session('rol')==='Revisor')
                <a href="{{url(session('eventoID').'/ArticulosPendientes/'.Auth::user()->id)}}" class="link-card">
                    <i class="las la-inbox la-3x"></i> Articulos por Revisar
                </a>
                <a href="{{url(session('eventoID').'_'.Auth::user()->id.'/ArticulosRevisados/')}}" class="link-card">
                    <i class="las la-user-check la-3x"></i> Articulos Revisados
                </a>
            @else
            
            @endif
         </div>
    </div>
    @role('Administrador')
        <button id="migrate-button" data-evento-id="{{ $evento->id }}">Migrar Informacion</button>
    @endrole
@endsection

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', (event) => {
        $(document).ready(function() {
            $('#migrate-button').click(function() {
                var eventoId = $(this).data('evento-id');
                $.ajax({
                    url: '{{ url('migrar') }}/' + eventoId,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.href = '{{ route('eventos.index') }}';
                        
                    },
                    error: function(response) {
                        alert('Migration failed: ' + response.responseJSON.error);
                    }
                });
            });
        });


    });
</script>
