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
// Lista de palabras ofensivas
const offensiveWords = [
  "puta", "puto", "verga", "chucha", "mierda", "cabrón", "cabrona", "pendejo", 
  "pendeja", "imbécil", "idiota", "estúpido", "estúpida", "zorra", "coño", 
  "maldito", "maldita", "joder", "gilipollas", "carajo", "hijo de puta", 
  "hija de puta", "bastardo", "bastarda", "culero", "culera", "picha", 
  "chingar", "chingado", "chingada", "maricón", "marica", "putazo", "mamón", 
  "mamona", "tonto", "tonta", "estupidez", "imbecilidad", "gilipollez", 
  "pelotudo", "pelotuda", "boludo", "boluda", "pajero", "pajera", "mierdero", 
  "cagón", "cagona", "ojete", "chupapijas", "cagada", "cojones", "chingón", 
  "chingona", "huevón", "huevona", "pito", "polla", "cacho", "pinche", 
  "malparido", "malparida", "parido", "parida", "perra", "perro", "gil", 
  "culo", "culazo", "culito", "culos", "estúpidos", "estúpidas", "pendejos", 
  "pendejas", "idiotas", "imbéciles", "pinga", "macaco", "mongol", "mongolo", 
  "baboso", "babosa", "tarado", "tarada","zopenca", "mierdero", "cafre", 
  "atrasado", "atrasada", "idioteces", "subnormal", "cretino", "cretina", 
  "anormal", "chupacabras", "gilipollas", "huevazo", "pedorro", "pedorra", 
  "cacas", "pelmazo", "pelmaza", "inútil", "patán", "desgraciado", 
  "desgraciada", "alimaña", "cerdo", "cerda", "mojón", "gay", "pija",
  "malnacido", "malnacida", "retardado", "retrasado", "cornudo", 
  "apestoso", "apestosa", "infeliz", "tarugo", "taruga", "cara de culo", 
  "lengua suelta", "hocicón", "hocicona", "desvergonzado", "desvergonzada", 
  "chupamedias", "arrastrado", "arrastrada", "muerto de hambre", "tirillas", 
  "don nadie", "estúpidez", "pezuño", "pezuña", "tarúpido", "troglodita",
  "cochina", "babosadas", "malaleche", "chingaquedito","cabróncete", 
  "cornuda", "calientahuevos", "imbecilidades", "cagón", "cagona" 
];
// Contador de caracteres
function updateCharacterCount() {
  const textArea = document.getElementById("content-area");
  const counter = document.getElementById("character-counter");
  const maxLength = 10000;
  const remaining = maxLength - textArea.value.length;
  counter.textContent = `${remaining} caracteres restantes`;
  if (remaining <= 0) {
      counter.style.color = "red";
  } else {
      counter.style.color = "gray";
  }
}
// Filtrar palabras ofensivas
function filterOffensiveWords() {
  const textArea = document.getElementById("content-area");
  const warning = document.getElementById("offensive-warning");
  const words = textArea.value.split(/\s+/);
  let containsOffensive = false;
  words.forEach((word) => {
      if (offensiveWords.includes(word.toLowerCase())) {
          containsOffensive = true;
      }
  });
  if (containsOffensive) {
      warning.style.display = "block";
      textArea.value = words
          .map((word) => (offensiveWords.includes(word.toLowerCase()) ? "****" : word))
          .join(" "); 
  } else {
      warning.style.display = "none";
  }
}