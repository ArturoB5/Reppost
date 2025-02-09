document.addEventListener("DOMContentLoaded", function() {
    const navLinks = document.querySelectorAll(".nav-link");

    navLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const targetId = this.getAttribute("data-target");
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                // Desplazamiento suave hacia la sección objetivo
                targetSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            // Remover la clase 'active' de todos los enlaces y agregarla al seleccionado
            navLinks.forEach(link => link.classList.remove("active"));
            this.classList.add("active");
        });
    });
});