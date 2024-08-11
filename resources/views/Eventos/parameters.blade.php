@extends('layouts.master')
    <title>Parametros del Evento</title>
@section('Content')
    <div class="container">
        <h1>Configuración del {{ $parameters['Evento'] }}</h1>
        <div class="parameter-form">
            {!! Form::open(['method' => 'POST', 'url' => session('eventoID').'/update-parameters', 'id' => 'parameter-form']) !!}
                <div class="puntuation">
                    <div class="max-punt">
                        <h3>{!! Form::label('max_to_approve', 'Puntuación Maxima para aprobar un artículo:') !!}</h3>
                        {!! Form::number('max_to_approve', $parameters['MaxToApprove'], ['id'=>'max_to_approve','required']) !!}
                    </div>
                    <div class="min-punt">
                        <h3>{!! Form::label('min_to_approve', 'Puntuación Minima para aprobar un artículo:') !!}</h3>
                        {!! Form::number('min_to_approve', $parameters['MinToApprove'], ['id'=>'min_to_approve','required']) !!}
                    </div>
                </div>
    
                <h3>Preguntas</h3>
                <div id="questions-container" class ="questions">
                    @foreach ($parameters['Questions'] as $index => $question)
                        <div class="input-group">
                            {!! Form::text('questions[]', $question, ['class'=>'input-control','required']) !!}
                            {!! Form::button('<i class="las la-trash-alt la-2x remove-question-btn"></i>', ['type' => 'button', 'class' => 'remove-question-btn']) !!}
                        </div>
                    @endforeach
                </div>
                <div class="addQuestion">
                    {!! Form::button('Agregar Pregunta', ['type' => 'button', 'id' => 'add-question-btn']) !!}
                </div>
                
                <h3>Opciones de Respuesta</h3>
                <div id="answers-container" class="questions">
                    @foreach ($parameters['OptionAnswers'] as $index => $answer)
                        <div class="input-group">
                            {!! Form::text('answers[]', $answer, ['class'=>'input-control','required']) !!}
                            {!! Form::button('<i class="las la-trash-alt la-2x"></i>', ['type' => 'button', 'class' => 'remove-answer-btn']) !!}
                        </div>
                    @endforeach
                </div>
                <div class="addQuestion">
                    {!! Form::button('Agregar Opcion', ['type' => 'button', 'id' => 'add-answer-btn']) !!}
                </div>  
                {!! Form::button('Guardar Configuración', ['type' => 'submit', 'id' => 'save-btn']) !!}
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
            removeButton.innerHTML = '<i class="las la-trash-alt la-2x remove-question-btn"></i>';
           
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
        // Agregar nueva respuesta
        document.getElementById('add-answer-btn').addEventListener('click', function() {
            const container = document.getElementById('answers-container');
            const div = document.createElement('div');
            div.classList.add('input-group');

            const input = document.createElement('input');
            input.type = 'text';
            input.classList.add('input-control');
            input.name = 'answers[]';
            input.required = true;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('remove-answer-btn');
            removeButton.innerHTML = '<i class="las la-trash-alt la-2x remove-answer-btn"></i>';
           
            div.appendChild(input);
            div.appendChild(removeButton);
            container.appendChild(div);
        });
        // Eliminar respuesta existente
        document.getElementById('answers-container').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-answer-btn')) {
                e.target.closest('.input-group').remove();
            }
        });

        ////////////////////////////////////////VALIDACIONES/////////////////////////////////

        const MaxInput = document.getElementById('max_to_approve');
        const MinInput = document.getElementById('min_to_approve');

        MaxInput.addEventListener('input', () => {
            limpiarErrores(MaxInput);
            const valor = parseFloat(MaxInput.value);
            if (!Number.isInteger(valor) || valor <= 0) {
                showError(MaxInput, 'El número debe ser un valor entero y positivo');
            }
        });
        MinInput.addEventListener('input', () => {
            limpiarErrores(MinInput);
            const valor = parseFloat(MinInput.value);
            if (!Number.isInteger(valor) || valor <= 0) {
                showError(MinInput, 'El número debe ser un valor entero y positivo');
            }
        });

        function showError(element, message) {
            element.style.borderColor = 'red';
            let errorMessage = document.createElement('small');
            errorMessage.className = 'error-message';
            errorMessage.style.color = 'red';
            errorMessage.innerText = message;
            element.parentNode.insertBefore(errorMessage,element.nextSibling);
        }

        function limpiarErrores(element) {
            element.style.borderColor = '';
            let nextElement = element.nextSibling;
            while (nextElement && nextElement.nodeType !== 1) {
                nextElement = nextElement.nextSibling;
            }
            if (nextElement && nextElement.classList.contains('error-message')) {
                nextElement.remove();
            }
        }

        const formulario = document.getElementById('parameter-form');
        formulario.addEventListener('submit', (event) => {
            event.preventDefault();
            const errores = document.querySelectorAll('small');

            if (errores.length > 0) {
                Swal.fire({
                    title:'Advertencia',
                    text:'Hay errores en la información, favor de verificar.',
                    icon:'warning',
                });
                return
            } else {
                formulario.submit();
            }
        });

    });
</script>
