@extends('layouts.master')
    <title>Editar</title>
</head>

@section('Content')
<div class="container">

    <h1>Modificar Evento</h1>
    {!! Form::open(['method' => 'PATCH', 'url' => '/eventos/'.$evento->id, 'files' => true]) !!}
        <div>
            <img src="{{ asset('SGEA/public/assets/uploads/' . $evento->logo) }}" alt="logo" style="width: 400px;">
            <p>Nombre de la imagen: {{ ( $evento->logo) }}</p>
        </div>

        <div class="container">
            <label for="logo">Cambiar logo por una imagen en sistema:</label>

            @if (isset($sysImgs) && !empty($sysImgs))
                <div class="carousell">
                    @foreach ($sysImgs as $image)
                    <img src="{{  asset('SGEA/public/assets/uploads/'.$image) }}" alt="Imagen" class="img-thumbnail img-selectable" data-img-name="{{ $image }}" style="width: 70px;">
                    @endforeach
                </div>
            @else
                <strong>Aun no hay imagenes en el sistema</strong>
            @endif
            <br><hr><br>
            <label for="img">Cambiar Logo:</label>
            {!! Form::file('logo', ['id' => 'logo',  'accept' => 'image/jpeg, image/png, image/webp']) !!}

            <!-- Campo oculto para almacenar el nombre de la imagen seleccionada -->
            <input type="hidden" id="selected_img" name="logo">
        </div>
        <label for="nombre">Nombre:</label>
        {!! Form::text ('nombre',$evento->nombre)!!}

  

        <label for="acronimo">Acrónimo:</label>
        {!! Form::text ('acronimo',$evento->acronimo)!!}

    

        <label for="fecha_inicio">Fecha de inicio:</label>
        {!! Form::date ('fecha_inicio',$evento->fecha_inicio)!!}

    

        <label for="fecha_fin">Fecha de fin:</label>
        {!! Form::date ('fecha_fin',$evento->fecha_fin)!!}

     

        <label for="edicion">Edición:</label>
        {!! Form::number ('edicion',$evento->edicion)!!}

       

        <button type="submit">Guardar evento</button>
        <a href="{{!!asset('/eventos')!!}"><button>Cancelar</button></a>
        {!!Form::close()!!}   
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imgSelectables = document.querySelectorAll('.img-selectable');
        const imgInput = document.getElementById('logo');
        const selectedImgInput = document.getElementById('selected_img');

        imgSelectables.forEach(function(img) {
            img.addEventListener('click', function() {
                // Asignar el nombre de la imagen al campo oculto
                selectedImgInput.value = this.dataset.imgName;

                // Opcional: Desactivar el campo de subir imagen para evitar confusiones
                imgInput.disabled = true;

                // Opcional: Añadir alguna clase CSS para resaltar la imagen seleccionada
                imgSelectables.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Reactivar el campo de subir imagen si se selecciona un archivo
        imgInput.addEventListener('change', function() {
            selectedImgInput.value = '';
            imgInput.disabled = false;
            imgSelectables.forEach(i => i.classList.remove('selected'));
        });
    });
</script>

<style>
    .selected {
        border: 2px solid blue;
    }
</style>