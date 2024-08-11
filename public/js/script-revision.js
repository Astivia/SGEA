document.addEventListener('DOMContentLoaded', function() {
    //////////////////////////////////VALIDACIONES/////////////////////////////////////////
    
    const revisionForm = document.getElementById('revision-form');
    const puntuacionInput = document.querySelector('input[name="puntuacion"]');
    const comentariosTextArea = document.querySelector('textarea[name="comentarios"]');
   
    
    revisionForm.addEventListener('submit', function(event) {
        let allAnswered = true;

        questions.forEach(question => {
            const selectedRadio = question.querySelector('input[type="radio"]:checked');
            if (!selectedRadio) {
                allAnswered = false;
            }
        });

        if (!allAnswered) {
            event.preventDefault();
            alert('Todas las preguntas son obligatorias.');
            return;
        }
        const comentarios = comentariosTextArea.value.trim();
        const wordCount = comentarios.split(/\s+/).length;
        if (wordCount < 250) {
            event.preventDefault();
                alert('Los comentarios deben tener al menos 250 palabras.');
            return;
        }
        puntuacionInput.value  = calcularResultado();
    });
    /////////////////////////////////CALCULAR PUNTUACION ///////////////////////////////
    function calcularResultado() {
        let total = 0;
        questions.forEach(question => {
            const selectedRadio = question.querySelector('input[type="radio"]:checked');
            if (selectedRadio) {
                total += parseInt(selectedRadio.value);
            }
        });
        return total;
    }
});