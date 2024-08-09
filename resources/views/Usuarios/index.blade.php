@extends('layouts.master')
<title>Usuarios</title>
@section('Content')
<div class="container">
    <div class="search-create">
        <h1 id="titulo-h1">Usuarios</h1>
        <button class="tooltip" id="create-btn"><i class="las la-plus-circle la-2x"></i><span class="tooltip-box">Crear Usuario</span></button>
    </div>
    <div class="ajuste" >
    <button class="tooltip" id="deleteSelected"><i class="las la-trash-alt la-2x"></i><span class="tooltip-box">Eliminar Seleccionados</span></button>
        <table id="example" class="display  responsive nowrap" style="width:100%">
            <thead>            
                <tr>
                <th><input type="checkbox" id="selectAll"></th>
                    <th>NOMBRE</th>
                    <th>EMAIL</th>
                    <th>CURP</th>
                    @role(['Administrador','Organizador'])
                    <th>Estado</th>
                    <th>Controles</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach ($Usuarios as $usu)
                <tr>
                <td><input type="checkbox" class="selectRow" data-id="{{ $usu->id }}"></td>
                    <td>
                        <a href="{{url('/usuarios/'.$usu->id)}}" style="color:#000;">{!!$usu->nombre!!} {!!$usu->ap_paterno!!} {!!$usu->ap_materno!!}</a>
                    </td>
                    <td>
                        <a href="mailto:{!!$usu->email!!}">{!!$usu->email!!}</a>
                    </td>
                    <td>{!!$usu->curp!!}</td>
                    @role(['Administrador','Organizador'])
                    <td>{!!$usu->estado!!}</td>
                    <td>
                        <a href="{!! 'usuarios/'.$usu->id !!}"><i class="las la-info-circle la-2x"></i></a>
                        <a href="{!!'usuarios/'.$usu->id.'/edit'!!}">
                            <i class="las la-user-edit la-2x"></i>
                        </a>
                        <a href="{{url('usuarios/'.$usu->id)}}" 
                            onclick="
                                event.preventDefault(); 
                                Swal.fire({
                                    title: '¿Estás seguro?',
                                    text: '¿Realmente desea Eliminar este Usuario?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, eliminar',
                                    cancelButtonText: 'No, cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('delete-form-{{ $usu->id }}').submit();
                                    }
                                });
                            ">
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
        <h2>Registrar Usuario</h2>
        {!! Form::open(['url'=>'/usuarios' , 'id' => 'usuario-register-form']) !!}
            
            {{ Form::label('usuario-nombre', 'Nombre:') }}
            <input type="text" id="usuario-name" name="nombre" required>

            {{ Form::label('usuario-lastName', 'Apellido Paterno:') }}
            <input type="text" id="usuario-lastName" name="ap_paterno" required>

            {{ Form::label('usuario-2lastName', 'Apellido Materno:') }}
            <input type="text" id="usuario-2lastName" name="ap_materno" required>

            {{ Form::label('usuario-CURP', 'CURP:') }}
            <input type="text" id="usuario-curp" name="curp" required>
            <span id="curp-error" style="color:red; display:none;">Esta CURP ya se ha registrado en otro usuario</span>

            {{ Form::label('usuario-mail', 'Email:') }}
            <input type="text" id="usuario-email" name="email" required>
            <span id="email-error" style="color:red; display:none;">Este Correo ya se encuentra registrado en otro usuario</span>

            {{ Form::label('usuario-pass', 'Contraseña:') }}
            <input type="password" id="usuario-password" name="password" required>

            {{ Form::label('usuario-phone', 'Telefono:') }}
            <input type="tel" id="telefono" name="telefono" required>

            <button type="submit">Guardar</button>
        {!!Form::close()!!}
    </div>
</div>

@endsection
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const deleteSelected = document.getElementById('deleteSelected');
    const selectAll = document.getElementById('selectAll');
    const curpInput = document.getElementById('usuario-curp');
    const emailInput = document.getElementById('usuario-email');
    const curpError = document.getElementById('curp-error');
    const emailError = document.getElementById('email-error');
    const usuarioRegisterForm = document.getElementById('usuario-register-form');

    if (deleteSelected) {
        deleteSelected.addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.selectRow:checked');
            let ids = [];
            selectedCheckboxes.forEach(function(checkbox) {
                ids.push(checkbox.getAttribute('data-id'));
            });

            if (ids.length > 0) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: '¡No podrá revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('usuarios.deleteMultiple') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ ids: ids })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Usuarios eliminados correctamente.'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error: ' + data.error
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ha ocurrido un error al eliminar a los usuarios.'
                            });
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: 'No se seleccionaron usuarios.'
                });
            }
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.selectRow');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    curpInput.addEventListener('input', () => {
        fetch('{{ route('verify-curp') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ curp: curpInput.value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'exists') {
                curpError.style.display = 'block';
            } else {
                curpError.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    
    emailInput.addEventListener('input', () => {
        fetch('{{ route('verify-email') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: emailInput.value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'exists') {
                emailError.style.display = 'block';
            } else{
                emailError.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    usuarioRegisterForm.addEventListener('submit', function(event) {
        if (curpError.style.display === 'block' || emailError.style.display === 'block') {
            event.preventDefault();
            Swal.fire({
                title: 'Informacion',
                text: 'No es posible registrar, favor de verificar la informacion',
                icon: 'warning'
            })
            return;
        }
    });

});
</script>