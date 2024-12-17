document.getElementById('send_message').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el envío normal del formulario
    var formData = new FormData(this);

    // Enviar el mensaje usando AJAX
    fetch('send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Limpiar el área de texto después de enviar el mensaje
            document.querySelector('textarea[name="my_message"]').value = '';
            // Actualizar los mensajes inmediatamente
            loadMessages();
        } else {
            alert('Error al enviar el mensaje');
        }
    })
    .catch(error => console.error('Error:', error));
});

// Función para cargar los mensajes sin recargar la página
function loadMessages() {
    var friend_id = <?php echo isset($_POST['friend_id']) ? $_POST['friend_id'] : 'null'; ?>; // Obtén el id del amigo

    if (!friend_id) return;

    $.ajax({
        url: 'load_message.php', // Asegúrate de que esto está correctamente configurado
        type: 'GET',
        data: { friend_id: friend_id },
        success: function(data) {
            // Actualizar los mensajes en el contenedor
            $('#message-container').html(data);

            // Mantener el scroll al final
            var messageContainer = document.getElementById('message-container');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    });
}

// Cargar los mensajes automáticamente cada 2 segundos (ajusta este tiempo si lo deseas)
setInterval(function() {
    loadMessages();
}, 2000); // 2000 ms = 2 segundos
