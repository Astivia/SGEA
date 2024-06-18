const logregBox = document.querySelector('.logreg-box');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const forgotPasswordLink = document.getElementById('forgot-password-link');
const forgotPasswordModal = document.getElementById('forgot-password-modal');
const closeButtons = document.querySelectorAll('.modal .close');
const sendForgotPasswordEmailButton = document.getElementById('send-forgot-password-email');

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
    } 
});

document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();
    showModal('Confirma tu cuenta en tu correo electronico.');
    startCountdown();
});

forgotPasswordLink.addEventListener('click', () => {
    forgotPasswordModal.style.display = 'flex';
});

closeButtons.forEach(button => {
    button.onclick = function() {
        button.parentElement.parentElement.style.display = 'none';
    }
});

window.onclick = function(event) {
    if (event.target == forgotPasswordModal) {
        forgotPasswordModal.style.display = 'none';
    } else if (event.target == document.getElementById('modal')) {
        document.getElementById('modal').style.display = 'none';
    }
}

function showModal(message) {
    const modal = document.getElementById('modal');
    const modalMessage = document.getElementById('modal-message');
    modalMessage.textContent = message;
    modal.style.display = 'flex';
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bxs-show', 'bxs-hide');
    } else {
        input.type = 'password';
        icon.classList.replace('bxs-hide', 'bxs-show');
    }
}

let countdown;
function startCountdown() {
    let timeLeft = 60;
    const countdownElement = document.getElementById('countdown');
    countdownElement.textContent = `Tiempo restante: ${timeLeft}s`;

    countdown = setInterval(() => {
        timeLeft -= 1;
        countdownElement.textContent = `Tiempo restante: ${timeLeft}s`;
        if (timeLeft <= 0) {
            clearInterval(countdown);
            countdownElement.textContent = 'Intenta otra vez.';
        }
    }, 1000);
}

document.getElementById('submit-verification-code').addEventListener('click', () => {
    const code = document.getElementById('verification-code').value;
    if (code === '1234') { // Replace with actual verification logic
        alert('Código verificado');
        document.getElementById('modal').style.display = 'none';
    } else {
        alert('Código incorrecto, intenta otra vez');
    }
});

sendForgotPasswordEmailButton.addEventListener('click', () => {
    const email = document.getElementById('forgot-password-email').value;
    if (email) {
        alert(`Correo de recuperación enviado a ${email}`);
        forgotPasswordModal.style.display = 'none';
    } else {
        alert('Por favor, ingresa tu correo electrónico');
    }
});
//contraseña validacion si es fuerte o debil y los requerimientos 
const registerPasswordInput = document.getElementById('register-password');
const confirmPasswordInput = document.getElementById('register-confirm-password');
const passwordRequirements = document.getElementById('password-requirements');
const passwordStrengthText = document.getElementById('password-strength-text');
const passwordStrengthBar = document.getElementById('password-strength-bar');
const passwordError = document.createElement('div');
passwordError.id = 'password-error';
passwordError.textContent = 'Las contraseñas no coinciden';
confirmPasswordInput.parentElement.appendChild(passwordError);

const reqLength = document.getElementById('req-length');
const reqUppercase = document.getElementById('req-uppercase');
const reqNumber = document.getElementById('req-number');
const reqSpecial = document.getElementById('req-special');

registerPasswordInput.addEventListener('input', () => {
    const password = registerPasswordInput.value;
    const strength = getPasswordStrength(password);

    passwordStrengthText.textContent = strength.text;
    passwordStrengthBar.innerHTML = `<div class="${strength.class}" style="width: ${strength.width}"></div>`;

    validatePasswordRequirements(password);
    checkPasswordsMatch();
});

confirmPasswordInput.addEventListener('input', checkPasswordsMatch);

registerPasswordInput.addEventListener('mouseover', () => {
    passwordRequirements.style.display = 'block';
});

registerPasswordInput.addEventListener('mouseout', () => {
    passwordRequirements.style.display = 'none';
});

function getPasswordStrength(password) {
    let strength = { text: 'Débil', class: 'weak', width: '33%' };

    const hasUpperCase = /[A-Z]/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const hasNumbers = /[0-9]/.test(password);
    const isValidLength = password.length <= 8;

    if (password.length === 0) {
        strength = { text: '', class: '', width: '0%' };
    } else if (hasUpperCase && hasSpecialChar && hasNumbers && isValidLength) {
        strength = { text: 'Fuerte', class: 'strong', width: '100%' };
    } else if (password.length >= 6 && (hasUpperCase || hasSpecialChar || hasNumbers)) {
        strength = { text: 'Media', class: 'medium', width: '66%' };
    }

    return strength;
}

function validatePasswordRequirements(password) {
    const hasUpperCase = /[A-Z]/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const hasNumbers = /[0-9]/.test(password);
    const isValidLength = password.length <= 8;

    reqLength.classList.toggle('valid', isValidLength);
    reqLength.classList.toggle('invalid', !isValidLength);

    reqUppercase.classList.toggle('valid', hasUpperCase);
    reqUppercase.classList.toggle('invalid', !hasUpperCase);

    reqNumber.classList.toggle('valid', hasNumbers);
    reqNumber.classList.toggle('invalid', !hasNumbers);

    reqSpecial.classList.toggle('valid', hasSpecialChar);
    reqSpecial.classList.toggle('invalid', !hasSpecialChar);
}

function checkPasswordsMatch() {
    const password = registerPasswordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const passwordsMatch = password === confirmPassword;

    passwordError.style.display = passwordsMatch ? 'none' : 'block';
    return passwordsMatch;
}

document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const password = registerPasswordInput.value;

    if (!/[A-Z]/.test(password) || !/[!@#$%^&*(),.?":{}|<>]/.test(password) || 
        !/[0-9]/.test(password) || password.length > 8) {
        showModal('La contraseña debe tener al menos una letra mayúscula, un carácter especial, números y no más de 8 caracteres.');
    } else if (!checkPasswordsMatch()) {
        showModal('Las contraseñas no coinciden. Por favor, verifica.');
    } else {
        showModal('Contraseña aceptada. Confirma tu cuenta en tu correo electrónico.');
        startCountdown();
    }
});
