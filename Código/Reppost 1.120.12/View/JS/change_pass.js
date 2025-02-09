document.addEventListener('DOMContentLoaded', function () {
    const currentPasswordField = document.getElementById('current_password');
    const toggleCurrentPasswordIcon = document.getElementById('toggle-password-current');
    const newPasswordField = document.getElementById('new_password');
    const toggleNewPasswordIcon = document.getElementById('toggle-password-new');
    const confirmPasswordField = document.getElementById('confirm_password');
    const toggleConfirmPasswordIcon = document.getElementById('toggle-password-confirm');
// Mostrar/Ocultar contraseña actual
toggleCurrentPasswordIcon.addEventListener('click', function () {
    const isPassword = currentPasswordField.type === 'password';
    currentPasswordField.type = isPassword ? 'text' : 'password';
    toggleCurrentPasswordIcon.classList.toggle('fa-eye', !isPassword);
    toggleCurrentPasswordIcon.classList.toggle('fa-eye-slash', isPassword);
});
toggleNewPasswordIcon.addEventListener('click', function () {
    const isPassword = newPasswordField.type === 'password';
    newPasswordField.type = isPassword ? 'text' : 'password';
    toggleNewPasswordIcon.classList.toggle('fa-eye', !isPassword);
    toggleNewPasswordIcon.classList.toggle('fa-eye-slash', isPassword);
});
toggleConfirmPasswordIcon.addEventListener('click', function () {
    const isPassword = confirmPasswordField.type === 'password';
    confirmPasswordField.type = isPassword ? 'text' : 'password';
    toggleConfirmPasswordIcon.classList.toggle('fa-eye', !isPassword);
    toggleConfirmPasswordIcon.classList.toggle('fa-eye-slash', isPassword);
});

    // Indicadores de fuerza para ambas contraseñas
    const passwordStrengthBarNew = document.getElementById('strength-bar');
    const passwordStrengthTextNew = document.getElementById('strength-text');
    const passwordStrengthBarConfirm = document.createElement('div');
    const passwordStrengthTextConfirm = document.createElement('span');
    confirmPasswordField.parentNode.insertBefore(passwordStrengthBarConfirm, confirmPasswordField.nextSibling);
    passwordStrengthBarConfirm.style = 'margin-top: 10px; height: 10px; width: 100%; background-color: #ccc; border-radius: 5px;';
    const confirmStrengthBar = document.createElement('div');
    confirmStrengthBar.style = 'height: 100%; width: 0%; border-radius: 5px;';
    passwordStrengthBarConfirm.appendChild(confirmStrengthBar);
    confirmPasswordField.parentNode.insertBefore(passwordStrengthTextConfirm, passwordStrengthBarConfirm.nextSibling);
    passwordStrengthTextConfirm.style = 'font-size: 14px; color: #555; margin-top: 5px; display: inline-block;';
    passwordStrengthTextConfirm.textContent = 'Dificultad:';
    
    // Validar fuerza de las contraseñas
    function validatePasswordStrength(field, strengthBar, strengthText) {
        const password = field.value;
        const strength = calculatePasswordStrength(password);

        switch (strength) {
            case 0:
                strengthBar.style.width = '20%';
                strengthBar.style.backgroundColor = 'red';
                strengthText.textContent = 'Dificultad: Muy baja';
                break;
            case 1:
                strengthBar.style.width = '40%';
                strengthBar.style.backgroundColor = 'orange';
                strengthText.textContent = 'Dificultad: Baja';
                break;
            case 2:
                strengthBar.style.width = '60%';
                strengthBar.style.backgroundColor = 'yellow';
                strengthText.textContent = 'Dificultad: Media';
                break;
            case 3:
                strengthBar.style.width = '80%';
                strengthBar.style.backgroundColor = 'lightgreen';
                strengthText.textContent = 'Dificultad: Alta';
                break;
            case 4:
                strengthBar.style.width = '100%';
                strengthBar.style.backgroundColor = 'green';
                strengthText.textContent = 'Dificultad: Muy alta';
                break;
        }
    }
    // Comparar contraseñas
    function comparePasswords() {
        const matchMessage = document.getElementById('password-match-message') || document.createElement('div');
        matchMessage.id = 'password-match-message';
        matchMessage.style = 'font-size: 14px; margin-top: 10px;';
        confirmPasswordField.parentNode.appendChild(matchMessage);

        if (newPasswordField.value === confirmPasswordField.value) {
            matchMessage.style.color = 'green';
            matchMessage.textContent = 'Las contraseñas coinciden.';
        } else {
            matchMessage.style.color = 'red';
            matchMessage.textContent = 'Las contraseñas no coinciden.';
        }
    }
    // Actualización en tiempo real
    newPasswordField.addEventListener('input', () => {
        validatePasswordStrength(newPasswordField, passwordStrengthBarNew, passwordStrengthTextNew);
        comparePasswords();
    });
    confirmPasswordField.addEventListener('input', () => {
        validatePasswordStrength(confirmPasswordField, confirmStrengthBar, passwordStrengthTextConfirm);
        comparePasswords();
    });
    // Calcular fuerza de la contraseña
    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[@#$%^&*!]/.test(password)) strength++;
        return strength;
    }
});
