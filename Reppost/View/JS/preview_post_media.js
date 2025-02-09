// Función para previsualizar archivos seleccionados
function previewFiles(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById("preview-container");
    if (!previewContainer) return; // Asegurarse de que el contenedor existe
  
    previewContainer.innerHTML = ""; // Limpiar contenedor
    Array.from(files).forEach((file) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const previewItem = document.createElement("div");
        previewItem.classList.add("preview-item");
  
        if (file.type.startsWith("video")) {
          const video = document.createElement("video");
          video.src = e.target.result;
          video.controls = true;
          previewItem.appendChild(video);
        } else {
          const img = document.createElement("img");
          img.src = e.target.result;
          previewItem.appendChild(img);
        }
  
        const deleteBtn = document.createElement("button");
        deleteBtn.classList.add("delete-btn");
        deleteBtn.textContent = "x";
        deleteBtn.onclick = () => removePreview(event.target, file.name);
        previewItem.appendChild(deleteBtn);
        previewContainer.appendChild(previewItem);
      };
      reader.onerror = (error) => console.log('Error reading file:', error);
      reader.readAsDataURL(file);
    });
  }
  // Función para eliminar un archivo de la previsualización
  function removePreview(input, fileName) {
    const files = Array.from(input.files);
    const filteredFiles = files.filter(file => file.name !== fileName);
    const dataTransfer = new DataTransfer();
    filteredFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
    previewFiles({ target: input });  // Redibujar la previsualización con los archivos restantes
  }