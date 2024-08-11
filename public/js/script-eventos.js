document.addEventListener('DOMContentLoaded', () => {
    /////////////////////////////////////////IMGS EN MODAL///////////////////////////////////
    const imgSelectables = document.querySelectorAll('.img-selectable');
    const imgInput = document.getElementById('logo');
    const selectedImgInput = document.getElementById('selected_img');

    imgSelectables.forEach(function(img) {
        img.addEventListener('click', function() {
            // Asignar el nombre de la imagen al campo oculto
            selectedImgInput.value = this.dataset.imgName;

            // Desactivar el campo de subir imagen para evitar confusiones
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


document.addEventListener('DOMContentLoaded', function() {
    /////////////////////////////////////////IMGS PREVIEW///////////////////////////////////
    document.getElementById('logo').addEventListener('change', function(event) {
        var preview = document.getElementById('preview-image');
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.style.display = 'none';
        }
    });

    /////////////////////////////////////////VALIDACIONES///////////////////////////////////
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
        if (!Number.isInteger(valor) || valor <= 0) {
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
