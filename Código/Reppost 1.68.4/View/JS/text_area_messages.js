document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('send_message');
  const textarea = document.getElementById('my_message');
  const sendButton = document.getElementById('send_button');
  // Función para enviar el mensaje
  async function sendMessage() {
      const friend_id = document.getElementById("friend_id").value;
      const my_message = textarea.value;
      if (my_message.trim() === '') {
          alert("El mensaje no puede estar vacío.");
          return;
      }
      try {
          // Usando fetch para enviar el mensaje
          const response = await fetch("send_message.php", {
              method: "POST",
              headers: {
                  "Content-Type": "application/x-www-form-urlencoded"
              },
              body: new URLSearchParams({
                  friend_id,
                  my_message
              })
          });
          if (response.ok) {
              const result = await response.json();
              if (result.status === "success") {
                  // Limpiar el campo y recargar el mensaje
                  textarea.value = '';
                  $('#message-container').append(result.message_html);
                  scrollToLatestMessage();
              } else {
                  alert("Hubo un problema al enviar el mensaje.");
              }
          } else {
              alert("Error al comunicarse con el servidor.");
          }
      } catch (error) {
          console.error("Error:", error);
          alert("Error al enviar el mensaje.");
      }
  }
  // Evento al presionar Enter (sin Shift)
  textarea.addEventListener('keydown', function(event) {
      if (event.key === 'Enter' && !event.shiftKey) {
          event.preventDefault(); // Evita el salto de línea
          sendMessage(); // Envía el mensaje
      }
  });
  // Evento para el botón enviar
  sendButton.addEventListener('click', function() {
      sendMessage(); // Envía el mensaje al hacer clic
  });
});
// Función para desplazar el contenedor al final
function scrollToLatestMessage() {
  const container = document.getElementById('message-container');
  if (container) {
      container.scrollTop = container.scrollHeight;
  }
}
// Llamada a la función cuando la página esté lista
document.addEventListener("DOMContentLoaded", function() {
  scrollToLatestMessage();
});
document.getElementById("search_contacts").addEventListener("input", function() {
  const searchTerm = this.value.toLowerCase(); // Obtén el texto que el usuario está escribiendo
  const contactItems = document.querySelectorAll(".contact-item"); // Selecciona todos los elementos de la lista
  contactItems.forEach(function(item) {
      const contactName = item.getAttribute("data-name"); // Obtén el nombre del amigo (en minúsculas)
      if (contactName.includes(searchTerm)) {
          item.style.display = ""; // Muestra el item si el nombre contiene el término de búsqueda
      } else {
          item.style.display = "none"; // Oculta el item si no coincide con la búsqueda
      }
  });
});
document.getElementById('search_contacts').addEventListener('input', function() {
  const searchValue = this.value.toLowerCase();
  const contacts = document.querySelectorAll('#contacts_list .contact-item');

  contacts.forEach(contact => {
      const contactName = contact.getAttribute('data-name');
      if (contactName.includes(searchValue)) {
          contact.classList.remove('hidden');
      } else {
          contact.classList.add('hidden');
      }
  });
});
// Función para mostrar el picker de emojis
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
  const textarea = document.getElementById("my_message");
  textarea.value += emoji; // Agrega el emoji al texto
  document.getElementById("emoji-picker").style.display = "none"; // Oculta el picker
  document.removeEventListener("click", closeEmojiPickerOnClickOutside); // Quita el listener
}
// Función para cerrar el picker si se hace clic fuera
function closeEmojiPickerOnClickOutside(event) {
  const picker = document.getElementById("emoji-picker");
  const button = document.querySelector("#emoji-btn");

  if (!picker.contains(event.target) && !button.contains(event.target)) {
    picker.style.display = "none"; // Oculta el picker
    document.removeEventListener("click", closeEmojiPickerOnClickOutside); // Quita el listener
  }
}
function showCategory(category) {
  // Evitar recarga de página
  event.preventDefault(); 
  // Código para mostrar la categoría correspondiente
  var categories = document.querySelectorAll('.emoji-category');
  categories.forEach(function(categoryElement) {
      categoryElement.style.display = 'none';
  });
  var activeCategory = document.querySelector('.' + category);
  if (activeCategory) {
      activeCategory.style.display = 'block';
  }
}


  