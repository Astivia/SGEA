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
        <br><hr><br>
        <div class="revision-container">
            <h2>INSTRUCCIONES</h2>
            <span>Seleccione una opcion para cada una de las siguientes preguntas segun su consideracion.</span>
            {!! Form::open(['method' => 'PUT', 'url' => 'Calificar_'.$articulo->id.'/', 'enctype' => 'multipart/form-data','id' => 'revision-form']) !!}
                <div class="evaluate">
                    @foreach ($parameters['Questions'] as $indexq => $question)
                        <div class="question">
                            <label for="pregunta{{$indexq+1}}"><h3>{{ $question }}</h3></label>
                            <div class="answerOptions">
                                @foreach ($parameters['OptionAnswers'] as $index => $answer)
                                <label>{!! Form::radio('op'.$indexq+1, $index ) !!}{{ $answer }} </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <strong>{{ Form::label('Coments', 'Comentarios:') }}</strong>
                {!! Form::textArea('comentarios',null,['required']) !!}

                <strong>{{Form::label('similitud','Similitud (%):')}}</strong>
                {!! Form::number('similitud',null,['required']) !!}
                
                <strong>{{ Form::label('turniting', 'Archivo Turnitin:') }}</strong>
                {!! Form::file('turniting', ['class' => 'form-control', 'accept' => '.pdf' , 'id' => 'turniting']) !!}
                
                {{ Form::hidden('puntuacion', null, ['required']) }}
                {{ Form::hidden('id_usuario', auth()->user()->id) }}
                
                
                {!! Form::button('Finalizar Revision', ['type' => 'submit', 'style' => 'background-color:#1a2d51;color:#fff;', 'id' => 'EndRev-btn']) !!}

            {!! Form::close() !!}
            <a href="{{ url()->previous() }}"><button> Cancelar Revision</button></a>
        </div>
    </div>
@endsection
<script src="{{asset('SGEA/public/js/script-revision.js')}}"></script>
