// Función para mostrar/ocultar el picker de emoticonos
function toggleEmojiPicker() {
  const picker = document.getElementById("emoji-picker");
  picker.style.display = picker.style.display === "block" ? "none" : "block";

  // Agrega un listener para detectar clics fuera del picker
  if (picker.style.display === "block") {
    document.addEventListener("click", closeEmojiPickerOnClickOutside);
  }
}
// Función para insertar un emoji en el área de texto
function insertEmoji(emoji) {
  const textarea = document.getElementById("content-area");
  textarea.value += emoji; // Agrega el emoji al texto
  document.getElementById("emoji-picker").style.display = "none"; // Oculta el picker
  document.removeEventListener("click", closeEmojiPickerOnClickOutside); // Quita el listener
}
// Función para cerrar el picker si se hace clic fuera
function closeEmojiPickerOnClickOutside(event) {
  const picker = document.getElementById("emoji-picker");
  const button = document.querySelector(".emoji-btn");

  if (!picker.contains(event.target) && !button.contains(event.target)) {
    picker.style.display = "none"; // Oculta el picker
    document.removeEventListener("click", closeEmojiPickerOnClickOutside); // Quita el listener
  }
}
// Función para previsualizar archivos seleccionados
function previewFiles(event) {
  const files = event.target.files;
  const previewContainer = document.getElementById("preview-container");
  previewContainer.innerHTML = ""; // Limpiar contenedor
  Array.from(files).forEach((file, index) => {
    const reader = new FileReader();
    reader.onload = function (e) {
      const previewItem = document.createElement("div");
      previewItem.classList.add("preview-item");
      const img = document.createElement("img");
      img.src = e.target.result;
      const deleteBtn = document.createElement("button");
      deleteBtn.classList.add("delete-btn");
      deleteBtn.innerHTML = "x";
      deleteBtn.onclick = function () {
        removePreview(index);
      };
      previewItem.appendChild(img);
      previewItem.appendChild(deleteBtn);
      previewContainer.appendChild(previewItem);
    };
    reader.readAsDataURL(file);
  });
}
// Función para eliminar un archivo de la previsualización
function removePreview(index) {
  const fileInput = document.getElementById("file-input");
  const files = Array.from(fileInput.files);
  files.splice(index, 1); // Eliminar el archivo seleccionado
  const dataTransfer = new DataTransfer();
  files.forEach(file => dataTransfer.items.add(file));
  fileInput.files = dataTransfer.files;
  previewFiles({ target: fileInput });
}
