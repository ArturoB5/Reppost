const passwordInput = document.getElementById('passwordsignup');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        const strength = calculatePasswordStrength(password);

        // Cambiar ancho, color y texto del semáforo
        switch (strength) {
            case 0:
                strengthBar.style.width = '20%';
                strengthBar.style.backgroundColor = 'green'; // Contraseña débil
                strengthText.textContent = 'Dificultad: Baja';
                break;
            case 1:
                strengthBar.style.width = '40%';
                strengthBar.style.backgroundColor = 'yellow'; // Un poco mejor
                strengthText.textContent = 'Dificultad: Baja';
                break;
            case 2:
                strengthBar.style.width = '60%';
                strengthBar.style.backgroundColor = 'orange'; // Moderada
                strengthText.textContent = 'Dificultad: Media';
                break;
            case 3:
                strengthBar.style.width = '80%';
                strengthBar.style.backgroundColor = 'orangered'; // Alta
                strengthText.textContent = 'Dificultad: Alta';
                break;
            case 4:
                strengthBar.style.width = '100%';
                strengthBar.style.backgroundColor = 'red'; // Muy alta
                strengthText.textContent = 'Dificultad: Muy Alta';
                break;
        }
    });

    function calculatePasswordStrength(password) {
        let strength = 0;

        // Reglas de validación
        if (password.length >= 8) strength++; // Longitud mínima
        if (/[A-Z]/.test(password)) strength++; // Al menos una letra mayúscula
        if (/[0-9]/.test(password)) strength++; // Al menos un número
        if (/[@#$%^&*!]/.test(password)) strength++; // Al menos un carácter especial

        return strength; // Nivel de fuerza
    }