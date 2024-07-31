@extends('layouts.master')
<title>Usuarios</title>

@section('Content')
<div class="container">
   
    <div class="search-create">
        <h1 id="titulo-h1">Usuarios</h1>
        <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
    </div>

    
    <!-- <div style="overflow-x:auto; overflow-y:auto; "> -->
    <div class="ajuste" >
    <button id="deleteSelected">Eliminar seleccionados</button>
        <table id="example" class="display  responsive nowrap" style="width:100%">
            <thead>            
                <tr>
                <th><input type="checkbox" id="selectAll"></th>
                    <th>NOMBRE</th>
                    <th>APELLIDOS</th>
                    <th>EMAIL</th>
                    <th>CURP</th>
                    @role(['Administrador','Organizador'])
                    <th>Controles</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach ($Usuarios as $usu)
                <tr>
                <td><input type="checkbox" class="selectRow" data-id="{{ $usu->id }}"></td>
                    <td>{!!$usu->nombre!!}</td>
                    <td>{!!$usu->ap_paterno!!} {!!$usu->ap_materno!!}</td>
                    <td>{!!$usu->email!!}</td>
                    <td>{!!$usu->curp!!}</td>
                    @role(['Administrador','Organizador'])
                    <td>
                        <a href="{!! 'usuarios/'.$usu->id !!}"><i class="las la-info-circle la-2x"></i></a>
                        <a href="{!!'usuarios/'.$usu->id.'/edit'!!}">
                            <i class="las la-user-edit la-2x"></i>
                        </a>
                        <a href="{{url('usuarios/'.$usu->id)}}" onclick="
                                                event.preventDefault(); 
                                                if (confirm('¿Estás seguro de que deseas eliminar este registro?')) 
                                                { document.getElementById('delete-form-{{ $usu->id }}').submit(); }">
                            <i class="las la-user-minus la-2x"></i>
                        </a>
                        <form id="delete-form-{{ $usu->id }}" action="{{ url('usuarios/'.$usu->id) }}" method="POST"
                            style="display: none;">
                            @method('DELETE')
                            @csrf
                        </form>
                    </td>
                    @endrole
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div id="create-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Nuevo Usuario</h2>
        {!! Form::open(['url'=>'/usuarios']) !!}


        <label for="participante-name">Nombre:</label>
        <input type="text" id="participante-name" name="nombre" required>

        <label for="participante-lastName">Apellido Paterno:</label>
        <input type="text" id="participante-lastName" name="ap_paterno" required>

        <label for="participante-2lastName">Apellido Materno:</label>
        <input type="text" id="participante-2lastName" name="ap_materno" required>

        <label for="participante-curp">CURP:</label>
        <input type="text" id="participante-curp" name="curp" required>

        <label for="participante-email">Email:</label>
        <input type="text" id="participante-email" name="email" required>

        <label for="participante-pass">Contraseña:</label>
        <input type="password" id="participante-password" name="password" required>
        
        <label for="participante-phone">Telefono:</label>
        <input type="tel" id="telefono" name="telefono" placeholder=" 722 1234 567" required>

        <button type="submit">Guardar</button>
        {!!Form::close()!!}
    </div>
</div>

@endsection