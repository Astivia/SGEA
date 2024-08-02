@extends('layouts.master')
<title>Areas</title>


@section('Content')
<div class="container">
    <div class="search-create">
        <h1 id="titulo-h1">Areas</h1>
        <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
    </div>

    @if($Areas->isEmpty())
        <strong>No hay datos</strong>
      @else
      <!-- <div style="overflow-x:auto; overflow-y:auto; max-height:500px;"> -->
      <div class="ajuste" >
            <button id="deleteSelected">Eliminar seleccionados</button>
            <table id="example" class="display  responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Controles</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($Areas as $area)
                        <tr >
                            <td><input type="checkbox" class="selectRow" data-id="{{ $area->id }}"></td>
                            <td>{!!$area->nombre!!}</td>
                            <td>{!!$area->descripcion!!}</td>
                            <td>
                                <a href="{!! 'areas/'.$area->id !!}"><i class="las la-info-circle la-2x"></i></a>
                                <a href="{!!'areas/'.$area->id.'/edit'!!}"><i class="las la-pen la-2x"></i></a>
                                <!-- <a href="{{url('areas/'.$area->id)}}"
                                    onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $area->id }}').submit(); }">
                                    <i class="las la-trash-alt la-2x"></i>
                                </a> -->
                                <a href="{{url('areas/'.$area->id)}}"
                                    onclick="event.preventDefault(); 
                                        Swal.fire({
                                            title: '¿Estás seguro?',
                                            text: '¿Estás seguro de que deseas eliminar este registro?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'No, cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('delete-form-{{ $area->id }}').submit();
                                            }
                                        });
                                    ">
                                    <i class="las la-trash-alt la-2x"></i>
                                </a>
                                <form id="delete-form-{{ $area->id }}" action="{{ url('areas/'.$area->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
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
        <h2>Registrar Area</h2>
        {!! Form::open(['url'=>'/areas']) !!}
        <label for="area-name">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="area-description">Descripción:</label>
        <textarea id="description" name="descripcion" required></textarea>

        <button type="submit">Guardar</button>

        {!!Form::close()!!}
    </div>
</div>


@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteSelected = document.getElementById('deleteSelected');
    const selectAll = document.getElementById('selectAll');

    if (deleteSelected) {
        deleteSelected.addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.selectRow:checked');
            let areaIds = [];
            selectedCheckboxes.forEach(function(checkbox) {
                let areaId = checkbox.getAttribute('data-id');
                areaIds.push(areaId);
            });

            if (areaIds.length > 0) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: '¡No podrá revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('areas.deleteMultiple') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ areaIds: areaIds })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Áreas eliminadas correctamente.'
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
                                text: 'Ha ocurrido un error al eliminar las áreas.'
                            });
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: 'No se seleccionaron áreas.'
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
});

</script>