document.addEventListener('DOMContentLoaded', function () {
    const lightModeBtn = document.getElementById('lightModeBtn');
    const darkModeBtn = document.getElementById('darkModeBtn');
    const body = document.body;

    // Comprobar si hay una cookie para el modo oscuro
    if (getCookie('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
    }

    // Activar modo claro
    lightModeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        body.classList.remove('dark-mode');
        setCookie('darkMode', 'disabled', 30);
    });

    // Activar modo oscuro
    darkModeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        body.classList.add('dark-mode');
        setCookie('darkMode', 'enabled', 30);
    });

    // Función para obtener cookies
    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }

    // Función para establecer cookies
    function setCookie(name, value, days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + "=" + value + "; expires=" + date.toUTCString() + "; path=/";
    }
});

