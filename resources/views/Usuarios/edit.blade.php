@extends('layouts.master')
    <title>Modificar Datos</title>
@section('Content')
<div class="container">
        <h1>MODIFICAR DATOS</h1>
        {!! Form::open(['method'=>'PATCH','url'=>'/usuarios/'.$usu->id, 'files' => true]) !!}  
            <div class="profile-photo">
                <img src="{{ asset($usu->foto)}}" alt="Foto de Perfil" id="profile-img">
                {!! Form::label('logo', 'Cambiar Imagen:') !!}
                <div class="custom-file">
                    {!! Form::file('newPhoto', ['id' => 'upload-img', 'class' => 'custom-file-input', 'accept' => 'image/jpeg, image/png, image/webp', 'style' => 'display:none;']) !!}
                    {!! Form::label('load-img', 'Elegir archivo...', ['id'=>'load-img', 'class' => 'custom-file-label']) !!}
                </div>
                <small class="form-text text-muted">El archivo debe ser una imagen en formato JPEG, PNG o WEBP.</small>
            </div>
            
            {!! Form::label('nombre', 'Nombre:') !!}
            {!! Form::text ('nombre',$usu->nombre)!!}              
                                
            {!! Form::label('ap_paterno', 'Apellido Paterno:') !!}
            {!! Form::text ('ap_paterno',$usu->ap_paterno)!!}
                                
            {!! Form::label('ap_materno', 'Apellido Materno:') !!}
            {!! Form::text ('ap_materno',$usu->ap_materno)!!}
                                
            {!! Form::label('curp', 'CURP:') !!}
            {!! Form::text ('curp',$usu->curp)!!}

            {!! Form::label('email', 'Email:') !!}
            {!! Form::text ('email',$usu->email)!!}

            {!! Form::label('password', 'Nueva Contraseña:') !!}
            {!! Form::text ('password',null)!!}

        @role('Administrador')
            <h3>Seleccionar Rol:</h3>
            @foreach($roles as $role)
            <div class="">
                <label>
                    {!! Form::checkbox('roles[]', $role->id, $usu->roles->contains('id', $role->id), ['class' => 'checkRole']) !!}
                    {{$role->name}}
                </label>
            </div>
            @endforeach
        @endrole
        <button type="submit">Guardar</button>
        {!!Form::close()!!}   
        <a href="{{ url()->previous() }}"><button>Cancelar</button></a> 
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('load-img').addEventListener('click', function () {
            document.getElementById('upload-img').click();
        });
        document.getElementById('profile-img').addEventListener('click', function () {
            document.getElementById('upload-img').click();
        });

        document.getElementById('upload-img').addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-img').src = e.target.result;
                }
                reader.readAsDataURL(file);

                var fileName = file.name;
                var nextSibling = e.target.nextElementSibling;
                if (nextSibling && nextSibling.classList.contains('custom-file-label')) {
                    nextSibling.innerText = fileName;
                }
            }
        });

        
    });
</script>

