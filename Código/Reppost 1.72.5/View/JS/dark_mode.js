// Verificar si la cookie 'darkMode' está establecida
document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;
    // Comprobar si la cookie está configurada para el modo oscuro
    if (getCookie('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
    }
    // Cambiar el modo oscuro cuando el usuario hace clic
    darkModeToggle.addEventListener('click', function () {
        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode');
            setCookie('darkMode', 'disabled', 30); // Desactivar modo oscuro y guardar cookie
        } else {
            body.classList.add('dark-mode');
            setCookie('darkMode', 'enabled', 30); // Activar modo oscuro y guardar cookie
        }
    });
    // Función para obtener el valor de una cookie
    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
    }
    // Función para establecer una cookie
    function setCookie(name, value, days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Duración de la cookie
        document.cookie = name + "=" + value + "; expires=" + date.toUTCString() + "; path=/";
    }
});