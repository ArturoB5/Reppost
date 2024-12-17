const username = "arturo.badillo.5"; // Reemplaza con tu usuario

function fetchCities(country) {
    const citySelect = document.getElementById("city");
    citySelect.innerHTML = "<option value=''>Cargando...</option>";

    if (country) {
        // Llamar a la API de GeoNames
        const url = `http://api.geonames.org/searchJSON?formatted=true&country=${country}&maxRows=100&featureClass=P&username=${username}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                citySelect.innerHTML = ""; // Limpia opciones anteriores

                if (data.geonames && data.geonames.length > 0) {
                    data.geonames.forEach(city => {
                        const option = document.createElement("option");
                        option.value = city.name;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                } else {
                    citySelect.innerHTML = "<option value=''>No se encontraron ciudades</option>";
                }
            })
            .catch(error => {
                console.error("Error al obtener ciudades:", error);
                citySelect.innerHTML = "<option value=''>Error al cargar ciudades</option>";
            });
    } else {
        citySelect.innerHTML = "<option value=''>Primero selecciona un país</option>";
    }
}
