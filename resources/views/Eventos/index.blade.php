@extends('layouts.master')
    <title>Eventos</title>
@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Eventos</h1>
            @role(['Administrador','Organizador'])
                <button class="tooltip" id="create-btn"><i class="las la-plus-circle la-2x"></i><span class="tooltip-box">Crear Evento</span></button>
            @endrole
        </div>
        @if($Eventos->isEmpty())
            <strong>No hay datos</strong>
        @else
            @role('Administrador')
                <button id="migrate-button">Migrar Toda la Informacion</button>
            @endrole
        <div class="ajuste" >
            @role('Administrador')
            <button class="tooltip" id="deleteSelected"><i class="las la-trash-alt la-2x"></i><span class="tooltip-box">Eliminar Seleccionados</span></button>
            @endrole
            <table id="example" class="display  responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        @role('Administrador')
                        <th><input type="checkbox" id="selectAll"></th>
                        @endrole
                        <th>LOGO</th>
                        <th>NOMBRE</th>
                        <th>ACRONIMO</th>
                        <th>EDicion</th>
                        <th>ESTADO</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Eventos as $e)
                    <tr>
                        @role('Administrador')
                        <td><input type="checkbox" class="selectRow" data-id="{{ $e->id }}"></td>
                        @endrole
                        <td>
                            <div class="evento-nombre-logo">
                                <a href="{!! 'eventos/'.$e->id !!}" style="color:#000;">
                                    <img id="img-list" src="{{ asset($e->logo) }}" alt="logo">
                                </a>
                            </div>
                        </td>
                        <td><a href="{!! 'eventos/'.$e->id !!}" style="color:#000;"><strong>{!!$e->nombre!!}</strong></a></td>
                        <td>{!!$e->acronimo!!}</td>
                        <td>{!!$e->edicion!!}</td>
                        <td>{!!$e->estado!!}</td>
                        <td>
                            @if($e->acronimo === 'CIDICI')
                                <a href=""><i class="las la-cog la-2x"></i></a>
                            @endif
                            <a href="{!! 'eventos/'.$e->id !!}"><i class="las la-info-circle la-2x"></i></a>
                            
                            @role(['Administrador', 'Organizador'])
                            <a href="{!!'eventos/'.$e->id.'/edit'!!}">
                                <i class="las la-pen la-2x"></i>
                            </a>
                            <a href="{{url('eventos/'.$e->id)}}"
                                onclick="event.preventDefault(); 
                                    Swal.fire({
                                        title: '¿Estás seguro?',
                                        text: '¿Estás seguro de que deseas eliminar este evento?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Sí, eliminar',
                                        cancelButtonText: 'No, cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('delete-form-{{ $e->id }}').submit();
                                        }
                                    });
                                ">
                                <i class="las la-trash-alt la-2x"></i>
                            </a>
                            <form id="delete-form-{{ $e->id }}" action="{{ url('eventos/'.$e->id) }}" method="POST"
                                style="display: none;">
                                @method('DELETE')
                                @csrf
                            </form>
                            @endrole
                            @if ($e->id !== session('eventoID'))
                                {!! Form::open(['route' => 'participantes.store', 'id' => 'participante-form']) !!}{!! Form::hidden('evento_id', $e->id) !!}{!! Form::hidden('usuario_id', Auth::user()->id) !!}
                                    <button id ="unirme" type="submit"><i class="las la-user-plus la-2x"></i> Unirme</button>
                                {!!Form::close()!!}
                            @else
                                {!! Form::open(['route' => ['participantes.destroy', $e->id,Auth::user()->id], 'method' => 'delete', 'style' => 'display:inline-block;']) !!}
                                    <button id="salir" type="button" 
                                        onclick="
                                            Swal.fire({
                                                title: '¿Estás seguro?',
                                                text: '¿Estás seguro de que desea salir del evento?',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sí, salir',
                                                cancelButtonText: 'No, cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('salir').form.submit();
                                                }
                                            });
                                        "style="border:none;">
                                        <i class="las la-sign-out-alt la-2x"></i>Salir
                                    </button>
    
                                {!! Form::close() !!}
                            @endif
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

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
<script src="{{asset('SGEA/public/js/script-eventos.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteSelected = document.getElementById('deleteSelected');
        const selectAll = document.getElementById('selectAll');

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
                            fetch('{{ route('eventos.deleteMultiple') }}', {
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
                                        text: 'Eventos eliminados correctamente.'
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
                                    text: 'Ha ocurrido un error al eliminar los eventos.'
                                });
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        text: 'No se seleccionaron eventos.'
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

        //////////////////////////////VALIDACIONES///////////////////////
        // Obtener elementos del DOM
   
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');
        const fechaHoy = new Date();
        const edicionInput = document.getElementById('edicion');

        fechaHoy.setUTCHours(0,0,0,0);
        fechaInicio.addEventListener('input', validarFechaInicio);
        fechaFin.addEventListener('input', validarFechaFin);

        function validarFechaInicio() {
            // Obtener el valor de la fecha de inicio y convertirla a la medianoche
            const startDate = new Date(fechaInicio.value);
            startDate.setUTCHours(0, 0, 0, 0); // Convertir la fecha a medianoche en UTC
            // Limpiar mensajes de error anteriores
            limpiarErrores(fechaInicio);
            // Validar que la fecha de inicio no sea anterior a la fecha actual
            if (startDate < fechaHoy  ) {
                showError(fechaInicio, 'La fecha de inicio no puede ser anterior a la actual.');
            }
            // Validar la fecha de fin solo si ya tiene un valor
            if (fechaFin.value) {
                validarFechaFin();
            }
        }

        function validarFechaFin() {
            // Obtener los valores de las fechas
            const startDate = new Date(fechaInicio.value).setHours(0, 0, 0, 0);
            const endDate = new Date(fechaFin.value).setHours(0, 0, 0, 0);
            
            // Limpiar mensajes de error anteriores
            limpiarErrores(fechaFin);

            // Validar que la fecha de fin no sea anterior a la fecha de inicio
            if (endDate < startDate) {
                showError(fechaFin, 'La fecha de fin no puede ser anterior a la de inicio.');
            }
        }

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



            // Verificar si nextElement no es null antes de acceder a sus propiedades
            while (nextElement && nextElement.nodeType !== 1) {
                nextElement = nextElement.nextSibling;
            }

            if (nextElement && nextElement.classList.contains('error-message')) {
                nextElement.remove();
            }
        }

        edicionInput.addEventListener('input', () => {
            limpiarErrores(edicionInput);
            const valor = parseFloat(edicionInput.value);
            if (!Number.isInteger(valor) || valor < 0) {
                showError(edicionInput, 'El número debe ser un valor entero y positivo');
            }
        });

        const formulario = document.getElementById('evento-form');
        formulario.addEventListener('submit', (event) => {
            event.preventDefault();
            const errores = document.querySelectorAll('small');

            if (errores.length > 0) {
                Swal.fire({
                    title:'Advertencia',
                    text:'Hay errores, favor de verificar los datos.',
                    icon:'warning',
                });
                return
            } else {
                formulario.submit();
            }
        });
    });
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', (event)=>{
        $(document).ready(function() {
            $('#migrate-button').click(function() {
                $.ajax({
                    url: '{{ route('migrate.data') }}',
                    type: 'POST',
                    data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // alert(response.message);
                        Swal.fire({
                            icon: 'info',
                            title: 'Información',
                            text: response.message
                        });
                        window.location.href = '{{ route('eventos.index') }}';
                    },
                    error: function(response) {
                        // alert('Migration failed: ' + response.responseJSON.error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Migration failed: ' + response.responseJSON.error
                        });
                    }
                });
            });
        });

        fetchSidebar();
    });

    function fetchSidebar() {
            fetch('{{ route('get.sidebar') }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('sidebar').innerHTML = html;
                });
        }
    
</script>



<style>
    .selected {
        border: 2px solid blue;
    }
</style>
