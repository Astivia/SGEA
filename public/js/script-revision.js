document.addEventListener('DOMContentLoaded', function() {
    const questions = document.querySelectorAll('.question');
    const revisionForm = document.getElementById('revision-form');
    const puntuacionInput = document.querySelector('input[name="puntuacion"]');
    const comentariosTextArea = document.querySelector('textarea[name="comentarios"]');

    questions.forEach(question => {
        const radios = question.querySelectorAll('input[type="radio"]');

        radios.forEach(radio => {
            radio.addEventListener('click', function() {
                if (this.checked) {
                    if (this.previousChecked) {
                        this.checked = false;
                        this.previousChecked = false;
                        radios.forEach(r => r.disabled = false);
                    } else {
                        radios.forEach(r => {
                            if (r !== this) {
                                r.disabled = true;
                            }
                        });
                        this.previousChecked = true;
                    }
                } else {
                    this.previousChecked = false;
                    radios.forEach(r => r.disabled = false);
                }
            });
        });
    });

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