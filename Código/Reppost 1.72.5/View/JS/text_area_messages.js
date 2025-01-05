const messageContainer = document.getElementById('message-container');
const sendButton = document.getElementById('send_button');
const textarea = document.getElementById('my_message');
const friendId = document.getElementById('friend_id').value;
let lastMessageId = 0;
// Función para enviar un mensaje
const sendMessage = async () => {
    const message = textarea.value.trim();
    if (!message) return;
    try {
        const response = await fetch('send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ friend_id: friendId, my_message: message }),
        });
        const data = await response.json();
        if (data.status === 'success') {
            // Limpiar el área de texto
            textarea.value = '';
            messageContainer.innerHTML += data.message_html;
            messageContainer.scrollTop = messageContainer.scrollHeight;
            textarea.style.height = 'auto'; // Restablecer la altura si se usa auto-resize
            textarea.dispatchEvent(new Event('input'));  // Disparar el evento 'input'
        } else {
            console.error('Error al enviar mensaje:', data);
        }
    } catch (error) {
        console.error('Error:', error);
    }
};
// Evento para el botón de enviar
sendButton.addEventListener('click', sendMessage);
// Evento para presionar Enter
textarea.addEventListener('keypress', (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
});
// Función para obtener mensajes nuevos
const fetchMessages = async () => {
    try {
        const response = await fetch('fetch_messages.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ friend_id: friendId, last_message_id: lastMessageId }),
        });
        const messages = await response.json();
        messages.forEach((message) => {
            const isSent = message.sender_id == sessionId;
            // Formatear la fecha y hora
            const date = new Date(message.date_sended);
            const time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });  // Formato 24 horas
            const dateFormatted = date.toLocaleDateString([], { day: '2-digit', month: '2-digit', year: 'numeric' }); // Formato día/mes/año
            const messageHtml = `
                <div class="mb-3">
                    <div style="color: #fff; background-color: ${isSent ? '#008568' : '#5e7671'};
                         text-align: ${isSent ? 'right' : 'left'}; margin-${isSent ? 'right' : 'left'}: 15px;
                         padding: 15px; border-radius: 20px; max-width: 70%; margin-bottom: 20px;
                         ${isSent ? 'border-bottom-right-radius: 0;' : 'border-bottom-left-radius: 0;'}">
                        ${message.content}
                        <div class="small text-muted mt-2">
                            ${time} - ${dateFormatted}
                        </div>
                    </div>
                </div>`;
            messageContainer.innerHTML += messageHtml;
            lastMessageId = Math.max(lastMessageId, message.message_id);
        });
        messageContainer.scrollTop = messageContainer.scrollHeight;
    } catch (error) {
        console.error('Error al obtener mensajes:', error);
    }
};
// Actualización periódica de mensajes
setInterval(fetchMessages, 1000);
// Desplazar el contenedor al último mensaje
const scrollToLatestMessage = () => {
    messageContainer.scrollTop = messageContainer.scrollHeight;
};
document.addEventListener('DOMContentLoaded', scrollToLatestMessage);
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