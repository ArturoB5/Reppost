document.addEventListener('DOMContentLoaded', function () {
    const notificationBadge = document.getElementById('notification-badge');
    const notificationList = document.getElementById('notification-list');
    const notificationButton = document.getElementById('notification-button');
    // Lista de notificaciones que ya se mostraron
    let displayedNotifications = [];
    // Función para formatear la fecha
    function formatDate(date) {
        const d = new Date(date);
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0'); // Los meses en JavaScript son 0-indexados
        const year = d.getFullYear();
        return `${hours}:${minutes} - ${day}/${month}/${year}`;
    }
    // Función para mostrar notificaciones emergentes
    function showPopupNotification(message) {
        // Crear el contenedor de la notificación emergente
        const popup = document.createElement('div');
        popup.classList.add('popup-notification');
        popup.innerHTML = `<strong>${message}</strong>`;
        // Estilo emergente
        document.body.appendChild(popup);
        // Desaparecer después de 5 segundos
        setTimeout(() => {
            popup.style.opacity = '0';  // Hace que se desvanezca
            setTimeout(() => popup.remove(), 500);  // Elimina el nodo después de la animación
        }, 5000); // Desaparece después de 5 segundos
    }
    // Función para cargar notificaciones
    function loadNotifications() {
        fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const notifications = data.notifications;
                    notificationList.innerHTML = '<li class="dropdown-header">Notificaciones</li><li class="divider"></li>';
                    if (notifications.length > 0) {
                        notificationBadge.style.display = 'inline-block';
                        notificationBadge.textContent = notifications.length;
                        notifications.forEach(notification => {
                            // Evitar que se muestren notificaciones repetidas
                            if (!displayedNotifications.includes(notification.id)) {
                                displayedNotifications.push(notification.id); // Marcar como mostrada
                                // Mostrar notificación emergente
                                showPopupNotification(notification.message);
                            }
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `
                                <a href="message.php" style="white-space: normal; padding: 10px;">
                                    <strong>${notification.message}</strong><br>
                                    <small class="text-muted">${formatDate(notification.date_created)}</small>
                                </a>
                            `;
                            notificationList.appendChild(listItem);
                        });
                    } else {
                        notificationBadge.style.display = 'none';
                        notificationList.innerHTML += '<li class="text-center text-muted">Sin notificaciones</li>';
                    }
                }
            })
            .catch(err => console.error('Error al cargar las notificaciones:', err));
    }
    // Cargar las notificaciones al iniciar
    loadNotifications();
    // Recargar cada segundo
    setInterval(loadNotifications, 1000);
});