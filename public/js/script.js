const logregBox = document.querySelector('.logreg-box');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');

registerLink.addEventListener('click', () => {
    logregBox.classList.add('activate');
});

loginLink.addEventListener('click', () => {
    logregBox.classList.remove('activate');
});

document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('login-email').value;
    if (!email.endsWith('@toluca.tecnm.mx')) {
        showModal('Usuario o contraseña incorrecto');
    } else {
        window.location.href = 'participantes.html';
    }
});

document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();
    showModal('Confirma tu cuenta en tu correo electronico.');
});

const modal = document.getElementById('modal');
const modalMessage = document.getElementById('modal-message');
const span = document.getElementsByClassName('close')[0];

function showModal(message) {
    modalMessage.textContent = message;
    modal.style.display = 'block';
}

span.onclick = function() {
    modal.style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
