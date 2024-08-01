@extends('layouts.master')
<title>Revision de Articulo</title>

@section('Content')
    <div class="container">
        <h1>{!! $articulo->titulo !!}</h1><br>
        <p><strong>Evento: </strong>{!!$articulo->evento->nombre!!} (<strong>{!!$articulo->evento->acronimo!!} {!!$articulo->evento->edicion!!}</strong>)</p>
        <p>
            <strong>Autores: </strong><br>
                <ul style="margin-left: 4%">
                    @foreach ($autores as $index => $autor)
                        <li>
                            <strong>{!! $autor->orden !!}. </strong> 
                            <a href="{{url('usuarios/'.$autor->usuario->id )}}" style="color:#000;">
                            {!! $autor->usuario->nombre_completo !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
        </p>
        <p><strong>Area: </strong>{!! $articulo->area->nombre !!}</p>
        <p><strong>Correspondencia: </strong><a href="mailto:{!! $articulo->autor_correspondencia->email!!}"> {!! $articulo->autor_correspondencia->email!!} </a>({!! $articulo->autor_correspondencia->usuario->nombre_completo!!})</p>
        <strong>Resumen:</strong>
        <div class="resumen">
            {!! $articulo->resumen !!}
        </div>
        <p><strong> Archivo: </strong>
            @if(!$articulo->archivo)
                No asignado
            @else
                {!!$articulo->archivo!!}
            @endif
        </p>
        

        @if($pdfUrl)
        <a href="{!!$pdfUrl !!}" target="_blank"><button><i class="las la-file-pdf"></i> Ver en nueva Pestaña</button></a>
        <div class="LectorPDF">
            <iframe src="{!!$pdfUrl !!}" frameborder="0" ></iframe>
        </div>
        @endif
        <div class="revision-container">
            {!! Form::open(['method' => 'PUT', 'url' => session('eventoID').'_'.$articulo->id.'/', 'id' => 'revision-form']) !!}

                {{ Form::hidden('id_usuario', auth()->user()->id) }}

                <strong>{{ Form::label('Coments', 'Comentarios:') }}</strong>
                {!! Form::textArea('comentarios') !!}

                <strong>{{ Form::label('turniting', 'Archivo Turnitin:') }}</strong>
                {!! Form::file('similitud', ['class' => 'form-control', 'accept' => '.pdf']) !!}

                <strong>{{ Form::label('puntuation', 'Asignar Puntuacion:') }}</strong>
                {{ Form::number('puntuacion', 'required')}}
                
                <button type="submit" id="EndRev-btn">Finalizar Revision</button>
            {!! Form::close() !!}
            <a href="{{ url()->previous() }}"><button> Cancelar Revision</button></a>
        </div>
    </div>
@endsection