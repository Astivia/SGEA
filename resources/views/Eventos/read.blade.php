@extends('layouts.master')
    <title>Informacion</title>
@section('Content')
    <div class="container">
        <h1>{!!$evento->nombre!!} ({!!$evento->acronimo!!} {!!$evento->edicion!!})</h1>
        <div class="info">
            <div class="event-img">
                <img src="{{asset($evento->logo)}}" alt="">
            </div>
            <div class="data">
                <p><strong>Inicia en:</strong>{!!$evento->fecha_inicio!!}</p>
                <p><strong>Culmina en:</strong>{!!$evento->fecha_fin!!}</p>
                @role(['Administrador','Comite'])
                <strong>Status del evento:</strong>{!!$evento->estado!!}
                <div class="evenControls">
                    <a href="" ><i class="las la-cog la-2x"></i></a>
                    <a href="{{url('eventos/'.$evento->id.'/edit')}}"><i class="las la-pen la-2x"></i></a>
                    <a href="" id="migrate-button" data-evento-id="{{ $evento->id }}"><i class="las la-rocket la-2x"></i></a>
                    <a href=""><i class="las la-times la-2x"></i></a>
                </div>
                @endrole
            </div>
        </div>
        <div class="links">
            @role('Administrador')
                <a href="{{ route('articulos.evento.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="lar la-newspaper la-3x"></i>Articulos
                </a>
                <a href="{{ route('autores.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-pen-nib la-3x"></i>Autores
                </a>
                <a href="{{ route('participantes.evento.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-users la-3x"></i>Participantes
                </a>
                <a href="{{ route('revisores.index', ['eventoId' => $evento->id]) }}" class="link-card">
                    <i class="las la-glasses la-3x"></i>Revisores
                </a>
            @endrole           
            @if(session('rol')==='Autor')
                <a href="{{url(session('eventoID').'_'.Auth::user()->id.'/MisArticulos/')}}" class="link-card">
                    <i class="las la-newspaper la-3x"></i> Mis Articulos
                </a>
                <a href="{{url(session('eventoID').'_'.Auth::user()->id.'/Evaluaciones/')}}" class="link-card">
                    <i class="las la-newspaper la-3x"></i>Historial de Evaluaciones
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

    <div id="create-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Registro de Evento</h2>
        {!! Form::open(['url'=>'/eventos', 'enctype' => 'multipart/form-data', 'id' => 'evento-form']) !!}
            {!! Form::label('logo', 'Imagenes en sistema:') !!}
                @if (isset($sysImgs) && !empty($sysImgs))
                    <div class="carousell">
                        @foreach ($sysImgs as $image)
                            <img src="{{ asset($image)}}" alt="Imagen" class="img-thumbnail img-selectable" data-img-name="{{ $image }}" style="width: 4vw;">
                        @endforeach
                    </div>
                @else
                    <strong>Aun no hay imagenes en el sistema</strong>
                @endif
            {!! Form::file('logo', ['id' => 'logo', 'class' => 'form-control', 'accept' => 'image/jpeg, image/png, image/webp']) !!}
            {!! Form::hidden('logo', null, ['id' => 'selected_img']) !!}
            <br><hr><br>
            <div class="loaded-img" style="display:flex;justify-content:center;align-items:center;">
                <img id="preview-image" alt="imagen" style="display:none; width:8vw; margin-top: 10px;background-color:#1a2d51;padding:2%;">
            </div>
            {!! Form::label('nombre', 'Nombre:') !!}
            {!! Form::text('nombre', null, ['id'=>'nombre','required']) !!}

            {!! Form::label('acronimo', 'Acronimo:') !!}
            {!! Form::text('acronimo', null, ['id'=>'acronimo','required']) !!}

            {!! Form::label('fecha de Inicio', 'Inicia:') !!}
            {!! Form::date('fecha_inicio', null, ['id'=>'fecha_inicio','required']) !!}

            {!! Form::label('fecha de Fin', 'Culmina:') !!}
            {!! Form::date('fecha_fin', null, ['id'=>'fecha_fin','required']) !!}

            {!! Form::label('edition', 'Edición:') !!}
            {!! Form::number('edicion', null, ['id'=>'edicion','required']) !!}
            <br>
            {!! Form::button('Crear Evento', ['type' => 'submit', 'id' => 'save-event-btn']) !!}
        {!!Form::close()!!}
</div>
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
