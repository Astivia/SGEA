function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onload = function() {
    if (document.getElementById('errorModal')) {
        document.getElementById('errorModal').style.display = 'block';
    }
    if (document.getElementById('successModal')) {
        document.getElementById('successModal').style.display = 'block';
    }
}
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
    var modals = document.getElementsByClassName('modal');
    for (var i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
            modals[i].style.display = 'none';
        }
    }
}
function togglePassword(inputId) {
    var input = document.getElementById(inputId);
    var icon = input.nextElementSibling.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('bxs-show');
        icon.classList.add('bxs-hide');
    } else {
        input.type = "password";
        icon.classList.remove('bxs-hide');
        icon.classList.add('bxs-show');
    }
}
//verificacion de curp y autocompletado
document.getElementById('curp').addEventListener('blur', function() {
    let curp = this.value;
    fetch('{{ route("verify-curp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ curp: curp })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'exists') {
            document.getElementById('register-name').value = data.user.nombre;
            document.getElementById('ap_pat').value = data.user.ap_paterno;
            document.getElementById('ap_mat').value = data.user.ap_materno;
            document.getElementById('email').value = data.user.email;
            document.getElementById('telefono').value = data.user.telefono;
            document.getElementById('curp-message').textContent = '';
        } else {
            document.getElementById('curp-message').textContent = 'Usuario no registrado en sistema';
        }
    });
});
document.getElementById('registration-form').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Expresión regular para validar la contraseña
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    // Validar la contraseña
    if (!passwordRegex.test(password)) {
        event.preventDefault(); // Evitar el envío del formulario
        showErrorModal('La contraseña debe tener al menos 8 caracteres, incluyendo letras, números y al menos 1 caracter especial.');
        return;
    }

    // Validar que las contraseñas coincidan
    if (password !== confirmPassword) {
        event.preventDefault(); // Evitar el envío del formulario
        showErrorModal('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
        return;
    }
});

function showErrorModal(message) {
    document.getElementById('password-error-message').innerText = message;
    openModal('password-error-modal');
}