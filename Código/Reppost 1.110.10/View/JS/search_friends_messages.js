const searchContacts = document.getElementById('search_contacts');
		const contactItems = document.querySelectorAll('.contact-item'); // Selecciona todos los elementos de la lista de contactos
		// Filtrar contactos
		searchContacts.addEventListener('input', (event) => {
			const searchTerm = event.target.value.toLowerCase(); // Obtén el término de búsqueda
			contactItems.forEach((contact) => {
				const contactName = contact.getAttribute('data-name').toLowerCase(); // Obtén el nombre del contacto en minúsculas
				// Añadir o quitar la clase 'hidden' según si el nombre contiene el término de búsqueda
				if (contactName.includes(searchTerm)) {
					contact.classList.remove('hidden'); // Muestra el contacto
				} else {
					contact.classList.add('hidden'); // Oculta el contacto
				}
			});
		});