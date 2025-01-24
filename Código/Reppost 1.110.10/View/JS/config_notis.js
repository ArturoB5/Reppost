document.getElementById('delete-all-notifications').addEventListener('click', function() {
    if (confirm('¿Estás seguro de eliminar todas las notificaciones?')) {
        fetch('delete_all_notifications.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Todas las notificaciones fueron eliminadas.');
                } else {
                    alert('Error al eliminar notificaciones: ' + data.message);
                }
            })
            .catch(err => {
                console.error('Error al eliminar notificaciones:', err);
                alert('Hubo un error al procesar tu solicitud.');
            });
    }
});