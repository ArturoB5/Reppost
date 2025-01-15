// Función para previsualizar la imagen seleccionada
function previewImage(event) {
    const imageInput = event.target;
    const imagePreview = document.getElementById('imagePreview');
    const removeButton = document.getElementById('removeImageButton');
    const file = imageInput.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block'; // Mostrar la imagen
            removeButton.style.display = 'block'; // Mostrar el botón de eliminar
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.src = '#';
        imagePreview.style.display = 'none'; // Ocultar la imagen si no hay archivo seleccionado
        removeButton.style.display = 'none'; // Ocultar el botón de eliminar
    }
}

// Función para borrar la imagen seleccionada
function removeImage() {
    const imagePreview = document.getElementById('imagePreview');
    const removeButton = document.getElementById('removeImageButton');
    const imageInput = document.querySelector('input[type="file"]');

    imagePreview.src = '#';
    imagePreview.style.display = 'none'; // Ocultar la imagen
    removeButton.style.display = 'none'; // Ocultar el botón de eliminar
    imageInput.value = ''; // Resetear el input del archivo
}