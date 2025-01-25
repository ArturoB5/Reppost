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
    "desgraciada", "alimaña", "cerdo", "cerda", "asqueroso", "asquerosa", 
    "malnacido", "malnacida", "mojón", "gay", "pija", "retardado", "retrasado",
    "apestoso", "apestosa", "infeliz", "tarugo", "taruga", "cara de culo", 
    "lengua suelta", "hocicón", "hocicona", "desvergonzado", "desvergonzada", 
    "chupamedias", "arrastrado", "arrastrada", "bocón", "bocona", "zopenco", 
    "infame", "caradura", "muerto de hambre", "tirillas", "lamebotas", 
    "don nadie", "estúpidez", "pezuño", "pezuña", "tarúpido", "cochino", 
    "cochina", "babosadas", "malaleche", "chingaquedito", "cornudo", 
    "cornuda", "felpudo", "calientahuevos", "desalmado", "imbecilidades", 
    "cagón", "cagona", "pichón", "sabandija", "troglodita", "cabróncete", 
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
            .map((word) => (offensiveWords.includes(word.toLowerCase()) ? "*****" : word))
            .join(" "); 
    } else {
        warning.style.display = "none";
    }
}
// Previsualizar archivos
function previewFiles(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById("preview-container");
    previewContainer.innerHTML = ""; // Limpiar previsualización anterior
    Array.from(files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "100px";
            img.style.marginRight = "5px";
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
// Alternar selector de emojis
function toggleEmojiPicker() {
    const emojiPicker = document.getElementById("emoji-picker");
    emojiPicker.style.display = emojiPicker.style.display === "none" ? "block" : "none";
}