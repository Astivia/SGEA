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
                    <div class="question">
                        <label for="pregunta1"><h3>¿El Título es claro, corto y atractivo?</h3></label>
                        <div class="answerOptions">
                            <label>{!! Form::radio('op1', 0 ) !!}Rechazo fuerte </label>
                            <label>{!! Form::radio('op1', 1) !!}Rechazar </label>
                            <label>{!! Form::radio('op1', 2) !!}Rechazo débil </label>
                            <label>{!! Form::radio('op1', 3) !!}Aceptación débil </label>
                            <label>{!! Form::radio('op1', 4) !!}Aceptar </label>
                            <label>{!! Form::radio('op1', 5) !!}Aceptación fuerte </label>
                        </div>
                    </div>
                    <div class="question">
                        <label for="pregunta2"><h3>¿Es de actualidad e interés el tema estudiado?</h3></label>
                        <div class="answerOptions">
                            <label>{!! Form::radio('op2', 0) !!}Rechazo fuerte </label>
                            <label>{!! Form::radio('op2', 1) !!}Rechazar </label>
                            <label>{!! Form::radio('op2', 2) !!}Rechazo débil </label>
                            <label>{!! Form::radio('op2', 3) !!}Aceptación débil </label>
                            <label>{!! Form::radio('op2', 4) !!}Aceptar </label>
                            <label>{!! Form::radio('op2', 5) !!}Aceptación fuerte </label>
                        </div>
                    </div>
                    <div class="question">
                        <label for="pregunta3"><h3>¿El trabajo es ameno, con rigor divulgativo y transmite la idea esencial?</h3></label>
                        <div class="answerOptions">
                            <label>{!! Form::radio('op3', 0 ) !!}Rechazo fuerte </label>
                            <label>{!! Form::radio('op3', 1) !!}Rechazar </label>
                            <label>{!! Form::radio('op3', 2) !!}Rechazo débil </label>
                            <label>{!! Form::radio('op3', 3) !!}Aceptación débil </label>
                            <label>{!! Form::radio('op3', 4) !!}Aceptar </label>
                            <label>{!! Form::radio('op3', 5) !!}Aceptación fuerte </label>
                        </div>
                    </div>
                    <div class="question">
                        <label for="pregunta4"><h3>¿Usa un lenguaje sencillo e imágenes descriptivas?</h3></label>
                        <div class="answerOptions">
                            <label>{!! Form::radio('op4', 0) !!}Rechazo fuerte </label>
                            <label>{!! Form::radio('op4', 1) !!}Rechazar </label>
                            <label>{!! Form::radio('op4', 2) !!}Rechazo débil </label>
                            <label>{!! Form::radio('op4', 3) !!}Aceptación débil </label>
                            <label>{!! Form::radio('op4', 4) !!}Aceptar </label>
                            <label>{!! Form::radio('op4', 5) !!}Aceptación fuerte </label>
                        </div>
                    </div>
                    <div class="question">
                        <label for="pregunta5"><h3>¿Hace buen uso de la ortografía y la gramática, contiene párrafos cortos y no más de 4 páginas?</h3></label>
                        <div class="answerOptions">
                            <label>{!! Form::radio('op5', 0 ) !!}Rechazo fuerte </label>
                            <label>{!! Form::radio('op5', 1) !!}Rechazar </label>
                            <label>{!! Form::radio('op5', 2) !!}Rechazo débil </label>
                            <label>{!! Form::radio('op5', 3) !!}Aceptación débil </label>
                            <label>{!! Form::radio('op5', 4) !!}Aceptar </label>
                            <label>{!! Form::radio('op5', 5) !!}Aceptación fuerte </label>
                        </div>
                    </div>
                    <div class="question">
                        <label for="pregunta6"><h3>¿El trabajo es accesible a un público no especializado?</h3></label>
                        <div class="answerOptions">
                            <label>{!! Form::radio('op6', 0) !!}Rechazo fuerte </label>
                            <label>{!! Form::radio('op6', 1) !!}Rechazar </label>
                            <label>{!! Form::radio('op6', 2) !!}Rechazo débil </label>
                            <label>{!! Form::radio('op6', 3) !!}Aceptación débil </label>
                            <label>{!! Form::radio('op6', 4) !!}Aceptar </label>
                            <label>{!! Form::radio('op6', 5) !!}Aceptación fuerte </label>
                        </div>
                    </div>
                </div>

                <strong>{{ Form::label('Coments', 'Comentarios:') }}</strong>
                {!! Form::textArea('comentarios',null,['required']) !!}
                
                <strong>{{ Form::label('turniting', 'Archivo Turnitin:') }}</strong>
                {!! Form::file('similitud', ['class' => 'form-control', 'accept' => '.pdf']) !!}
                
                {{ Form::hidden('puntuacion', null, ['required']) }}
                {{ Form::hidden('id_usuario', auth()->user()->id) }}
                
                <button type="submit" id="EndRev-btn">Finalizar Revision</button>
            {!! Form::close() !!}
            <a href="{{ url()->previous() }}"><button> Cancelar Revision</button></a>
        </div>
    </div>
@endsection
<script src="{{asset('SGEA/public/js/script-revision.js')}}"></script>
