function initChat(config) {
    const {
        sessionId,
        userRole,
        preOtherId
    } = config;
    const chatWidget    = document.getElementById('chatWidget');
    const chatHeader    = document.getElementById('chatHeader');
    const chatBody      = document.getElementById('chatBody');
    const chatFooter    = document.getElementById('chatFooter');
    const chatMessages  = document.getElementById('chatMessages');
    const chatInput     = document.getElementById('chatInput');
    const sendBtn       = document.getElementById('sendBtn');
    let chatOpen = false;
    let otherId  = preOtherId || 0;

    // Abrir/cerrar chat

chatHeader.addEventListener('click', toggleChat);
function toggleChat(forceOpen) {
    if (typeof forceOpen !== 'undefined') {
        chatOpen = forceOpen;
    } else {
        chatOpen = !chatOpen;
    }

    if (chatOpen) {
        chatBody.style.display = 'flex';
        chatFooter.style.display = 'flex';
        chatToggleBtn.innerHTML = '<i class="fa fa-chevron-up"></i>';
    } else {
        chatBody.style.display = 'none';
        chatFooter.style.display = 'none';
        chatToggleBtn.innerHTML = '<i class="fa fa-chevron-down"></i>';
    }
}
// Si eres admin, asocia listeners a los botones de moderadores
const modButtons = document.querySelectorAll('.chooseModBtn');
modButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        const modId = this.getAttribute('data-id');
        otherId = parseInt(modId);
        // Abre el chat (si no está abierto)
        toggleChat(true);
        // Cargar mensajes
        fetchMessages();
    });
});
// Enviar mensaje
sendBtn.addEventListener('click', sendMessage);
chatInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        sendMessage();
    }
});
function sendMessage() {
    const content = chatInput.value.trim();
    if (!content) return;
    if (!otherId) {
        alert('No hay destinatario definido');
        return;
    }
    fetch('chat_send.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            sender_id: sessionId,
            receiver_id: otherId,
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            chatInput.value = '';
            fetchMessages();
        } else {
            alert('Error al enviar mensaje: ' + data.message);
        }
    })
    .catch(err => console.error(err));
}
function fetchMessages() {
    if (!otherId) return;
    fetch('chat_fetch.php?sender_id=' + sessionId + '&receiver_id=' + otherId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderMessages(data.messages);
            }
        })
        .catch(err => console.error(err));
}
function renderMessages(messages) {
    chatMessages.innerHTML = '';
    messages.forEach(msg => {
        const div = document.createElement('div');
        div.classList.add('message');
        if (msg.sender_id == sessionId) {
            div.classList.add('self');
        } else {
            div.classList.add('other');
        }
        div.innerHTML = msg.content;
        chatMessages.appendChild(div);
    });
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
// Cargar mensajes cada 3s
setInterval(fetchMessages, 3000);
}
