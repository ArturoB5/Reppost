document.addEventListener('DOMContentLoaded', function () {
    // Fragmento 1: Controlar el ojo en el campo de contraseña de "password"
    const passwordField = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('toggle-password');

    togglePasswordIcon.addEventListener('click', function () {
        const isPassword = passwordField.type === 'password';
        passwordField.type = isPassword ? 'text' : 'password';
        togglePasswordIcon.classList.toggle('fa-eye', !isPassword);
        togglePasswordIcon.classList.toggle('fa-eye-slash', isPassword);
    });

    // Fragmento 2: Controlar el ojo en los campos de "passwordsignup" y "confirm_password"
    const passwordSignupField = document.getElementById('passwordsignup');
    const togglePasswordSignupIcon = document.getElementById('toggle-password-signup');

    togglePasswordSignupIcon.addEventListener('click', function () {
        const isPassword = passwordSignupField.type === 'password';
        passwordSignupField.type = isPassword ? 'text' : 'password';
        togglePasswordSignupIcon.classList.toggle('fa-eye', !isPassword);
        togglePasswordSignupIcon.classList.toggle('fa-eye-slash', isPassword);
    });

    const confirmPasswordField = document.getElementById('confirm_password');
    const toggleConfirmPasswordIcon = document.getElementById('toggle-password-confirm');

    toggleConfirmPasswordIcon.addEventListener('click', function () {
        const isPassword = confirmPasswordField.type === 'password';
        confirmPasswordField.type = isPassword ? 'text' : 'password';
        toggleConfirmPasswordIcon.classList.toggle('fa-eye', !isPassword);
        toggleConfirmPasswordIcon.classList.toggle('fa-eye-slash', isPassword);
    });
});
