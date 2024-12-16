  // Detectar clic fuera de la barra de navegación y cerrar el menú si está abierto
  document.addEventListener('click', function(event) {
    var isClickInsideNavbar = document.querySelector('.navbar-collapse').contains(event.target);
    var isNavbarToggled = document.querySelector('.navbar-collapse').classList.contains('in'); // Verificar si está desplegada

    if (!isClickInsideNavbar && isNavbarToggled) {
      document.querySelector('.navbar-toggle').click(); // Cierra la barra de navegación
    }
  });