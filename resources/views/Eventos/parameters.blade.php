@extends('layouts.master')
    <title>Parametros del Evento</title>
@section('Content')
    <div class="container">
        <h1>Configuración del {{ $parameters['Evento'] }}</h1>
        <div class="parameter-form">
            {!! Form::open(['method' => 'POST', 'url' => session('eventoID').'/update-parameters', 'id' => 'parameter-form']) !!}
                <div class="puntuation">
                    <div class="max-punt">
                        
                        <label for="min_to_approve"><h3>Puntuación Maxima para aprobar un artículo</h3></label> 
                        <input type="number" class="form-control" id="max_to_approve" name="max_to_approve" required value="{{ $parameters['MaxToApprove'] }}">
                    </div>
                    <div class="min-punt">
                        <label for="min_to_approve"><h3>Puntuación Mínima para aprobar un artículo</h3></label>
                        <input type="number" class="form-control" id="min_to_approve" name="min_to_approve" required value="{{ $parameters['MinToApprove'] }}">
                    </div>
                </div>
    
                <h3>Preguntas</h3>
                <div id="questions-container" class = "questions">
                    @foreach ($parameters['Questions'] as $index => $question)
                        <div class="input-group">
                            <input type="text" class="input-control" name="questions[]" value="{{ $question }}" required>
                            <button type="button" class="remove-question-btn"><i class="las la-trash-alt la-2x"></i></button>
                        </div>
                    @endforeach
                </div>
                <div class="addQuestion">
                    <button type="button" id="add-question-btn">Agregar Pregunta</button>
                </div>
                
                <h3>Opciones de Respuesta</h3>
                <div id="answers-container" class="questions">
                    @foreach ($parameters['OptionAnswers'] as $index => $answer)
                        <div class="input-group">
                            <input type="text" class="input-control" name="answers[]" value="{{ $answer }}" required>
                            <button type="button" class="remove-answer-btn"><i class="las la-trash-alt la-2x"></i></button>
                        </div>
                    @endforeach
                </div>
                <div class="addQuestion">
                    <button type="button" id="add-question-btn">Agregar Opcion</button>
                </div>
                <button type="submit">Guardar Configuración</button>
            {!! Form::close() !!}
            <a href="{{ url()->previous() }}"><button>Cancelar</button></a> 
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar nueva pregunta
        document.getElementById('add-question-btn').addEventListener('click', function() {
            const container = document.getElementById('questions-container');
            const div = document.createElement('div');
            div.classList.add('input-group');

            const input = document.createElement('input');
            input.type = 'text';
            input.classList.add('input-control');
            input.name = 'questions[]';
            input.classList.add('form-control');
            input.required = true;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('remove-question-btn');
            removeButton.innerHTML = '<i class="las la-trash-alt la-2x"></i>';
           
            div.appendChild(input);
            div.appendChild(removeButton);
            container.appendChild(div);
        });

        // Eliminar pregunta existente
        document.getElementById('questions-container').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-question-btn')) {
                e.target.closest('.input-group').remove();
            }
        });
    });
</script>
