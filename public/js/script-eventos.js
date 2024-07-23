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